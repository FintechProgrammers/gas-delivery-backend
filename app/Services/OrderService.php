<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Http\Resources\RiderResource;
use App\Jobs\AssignRiderToOrder;
use App\Models\DeliveryAddress;
use App\Models\GasOrder;
use App\Models\Revenue;
use App\Models\Setting;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Complete an order.
     *
     * @param GasOrder $order
     * @return array
     */
    public function completeOrder(GasOrder $order): array
    {
        DB::beginTransaction();

        try {
            // Ensure the order is marked as completed
            $order->update(['status' => 'completed']);

            // Get the user, rider, and vendor
            $user = $order->user;
            $rider = $order->rider;
            $vendor = $order->business;
            $riderAmount = 0;
            $vendorAmount = 0;
            $totalAmount = $order->total_amount;

            // Calculate the expected amounts for the rider and vendor
            $deliveryAmount = $order->delivery_fee;

            if ($order->payment_method === 'WALLET') {
                // Debit the user's wallet
                $user->wallet->update([
                    'balance' => $user->wallet->balance - $order->total_amount,
                ]);

                // Create transaction history for the user (debit)
                $this->transactionService->createTransaction(
                    $user,
                    $order->total_amount,
                    'purchase',
                    'debit',
                    'completed',
                    'TX' . uniqid(),
                    $user->wallet->balance,
                    $user->wallet->balance - $order->total_amount,
                    'Payment for order #' . $order->id
                );
            }

            //get rider percentage from settings
            $riderPercentage = systemSettings()->rider_percentage ?? 0;
            if ($riderPercentage > 0) {

                $riderCut = ($riderPercentage / 100) * $deliveryAmount;
                $riderAmount = $riderCut;

                $rider->wallet->update([
                    'balance' => $rider->wallet->balance + $riderAmount,
                ]);

                // Create transaction history for the rider (credit)
                $this->transactionService->createTransaction(
                    $rider,
                    $riderAmount,
                    'deposit',
                    'credit',
                    'completed',
                    'TX' . uniqid(),
                    $rider->wallet->balance,
                    $rider->wallet->balance + $riderAmount,
                    'Earnings from order #' . $order->id
                );
            }

            //get amount too take from  vendor from the vendor account
            $vendorFee = $vendor->vendor_fee ?? 0;

            if ($vendorFee > 0) {
                $gasAmount =  $order->gas_amount;

                $vendorAmount = $gasAmount - $vendorFee;

                // Credit the vendor's wallet
                $vendor->wallet->update([
                    'balance' => $vendor->wallet->balance + $vendorAmount,
                ]);

                // Create transaction history for the vendor (credit)
                $this->transactionService->createTransaction(
                    $vendor,
                    $vendorAmount,
                    'deposit',
                    'credit',
                    'completed',
                    'TX' . uniqid(),
                    $vendor->wallet->balance,
                    $vendor->wallet->balance + $vendorAmount,
                    'Earnings from order #' . $order->id
                );
            }

            // ✅ Check if referral is active and user has a parent
            $settings = Setting::first();
            $referralBonus = $settings->referral_is_active ? floatval($settings->referral_bonus) : 0;

            if ($referralBonus > 0 && $user->parent_id) {
                $parent = $user->parent;

                if ($parent && $parent->wallet) {
                    $oldBalance = $parent->wallet->balance;
                    $newBalance = $oldBalance + $referralBonus;

                    // Credit parent's wallet
                    $parent->wallet->update([
                        'balance' => $newBalance,
                    ]);

                    // Record parent's credit transaction
                    $this->transactionService->createTransaction(
                        $parent,
                        $referralBonus,
                        'bonus',
                        'credit',
                        'completed',
                        'TX' . uniqid(),
                        $oldBalance,
                        $newBalance,
                        'Referral bonus from order #' . $order->reference
                    );

                    $riderAmount = $riderAmount - $referralBonus;
                }
            }

            //record platform earnings
            $platformEarnings = $totalAmount - ($riderAmount + $vendorAmount);

            Revenue::create([
                'order_id' => $order->id,
                'amount' => $platformEarnings,
                'rider_amount' => $riderAmount,
                'vendor_amount' => $vendorAmount,
                'referral_amount' => $referralBonus,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Order completed successfully',
                'status' => Response::HTTP_OK,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to complete order: " . $e->getMessage(), ['exception' => $e]);

            return [
                'success' => false,
                'message' => 'Failed to complete order',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ];
        }
    }

    /**
     * Place a new order.
     *
     * @param User $user
     * @param array $requestData
     * @return array
     */
    public function placeOrder(User $user, array $requestData): array
    {
        DB::beginTransaction();

        try {
            $wallet = $user->wallet;

            // Get vendor
            $business = User::with('profile')->whereUuid($requestData['vendor'])->first();

            if (!$business) {
                return [
                    'success' => false,
                    'message' => 'Invalid vendor selected',
                    'status' => 404,
                ];
            }

            // Get user delivery information
            $deliveryAddress = DeliveryAddress::whereUuid($requestData['delivery_address'])->first();

            if (!$deliveryAddress) {
                return [
                    'success' => false,
                    'message' => 'Invalid delivery address',
                    'status' => 404,
                ];
            }

            // Calculate distance
            $stationAddress = [
                'longitude' => $business->profile->longitude,
                'latitude' => $business->profile->latitude,
            ];

            $userAddress = [
                'longitude' => $deliveryAddress->longitude,
                'latitude' => $deliveryAddress->latitude,
            ];

            $distance = calculateDistance(
                $userAddress['latitude'],
                $userAddress['longitude'],
                $stationAddress['latitude'],
                $stationAddress['longitude']
            );

            $distance = round($distance, 2);

            // Calculate delivery fee and gas amount
            $deliveryFee = calculateDeliveryFee($distance);

            $pricePerKg = $business->pricePerKg->price;
            $gasAmount = $pricePerKg * $requestData['gas_amount'];

            $totalAmount = $gasAmount + $deliveryFee;

            if ($requestData['payment_method'] === 'WALLET') {
                // Check if the user has sufficient balance
                if ($wallet->balance < $totalAmount) {
                    return [
                        'success' => false,
                        'message' => 'Insufficient balance',
                        'status' => 400,
                    ];
                }
            }

            // Check if the user has sufficient balance
            if ($wallet->balance < $totalAmount) {
                return [
                    'success' => false,
                    'message' => 'Insufficient balance',
                    'status' => 400,
                ];
            }

            // Create the order
            $order = GasOrder::create([
                'reference' => generateReference(),
                'user_id' => $user->id,
                'delivery_address_id' => $deliveryAddress->id,
                'business_id' => $business->id,
                'to_distination' => json_encode($stationAddress),
                'from_distination' => json_encode($userAddress),
                'distance' => $distance,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmount,
                'gas_amount' => $gasAmount,
                'gas_size' => $requestData['gas_amount'] . 'kg',
                'cylinder_size' => $requestData['cylinder_size'] . 'kg',
                'price_per_km' => 0,
                'payment_method' => $requestData['payment_method'],
            ]);

            DB::commit();

            dispatch(new AssignRiderToOrder($order));

            // Fetch nearby available riders
            $availableRiders = getNearbyAvailableRiders($deliveryAddress->latitude, $deliveryAddress->longitude);

            return [
                'success' => true,
                'data' => [
                    'order' => new OrderResource($order),
                    'available_riders' => RiderResource::collection($availableRiders),
                ],
                'message' => 'Order placed successfully',
                'status' => Response::HTTP_CREATED,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to place order: " . $e->getMessage(), ['exception' => $e]);

            return [
                'success' => false,
                'message' => 'Failed to place order',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ];
        }
    }
}
