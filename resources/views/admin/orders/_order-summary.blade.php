<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">Orders #{{ $order->reference }}</h4>
                <p class="mb-0 text-muted mt-1">{{ $order->created_at->format('d F Y \a\t h:i a') }}</p>
            </div><!--end col-->
            <div class="col-auto">
                @if ($order->status === 'pending')
                    <span class="badge bg-transparent border border-warning text-warning">Pending</span>
                @elseif ($order->status === 'active')
                    <span class="badge bg-transparent border border-secondary text-secondary">Active</span>
                @elseif ($order->status === 'completed')
                    <span class="badge bg-transparent border border-success text-success">Completed</span>
                @elseif ($order->status === 'cancelled')
                    <span class="badge bg-transparent border border-danger text-danger">Cancelled</span>
                @endif
            </div>
        </div> <!--end row-->
    </div><!--end card-header-->
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table mb-2">
                <thead class="table-light">
                    <tr>
                        <th class="">Price</th>
                        <th class="">Quantity</th>
                        <th class="">Delivery</th>
                        <th class="">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="">{{ number_format($order->gas_amount, 2) }} NGN</td>
                        <td class="">{{ $order->gas_size }}</td>
                        <td class="">{{ number_format($order->delivery_fee, 2) }} NGN</td>
                        <td class="">{{ number_format($order->total_amount, 2) }} NGN</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div><!--card-body-->
</div><!--end card-->
