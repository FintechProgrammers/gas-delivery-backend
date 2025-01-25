<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;

class TransactionService
{
    /**
     * Create a transaction record.
     *
     * @param User $user
     * @param float $amount
     * @param string $action
     * @param string $type
     * @param string $status
     * @param string $reference
     * @param string $externalReference
     * @param float $openingBalance
     * @param float $closingBalance
     * @param string $narration
     * @return Transaction
     */
    public function createTransaction(
        User $user,
        float $amount,
        string $action,
        string $type,
        string $status,
        string $reference,
        float $openingBalance,
        float $closingBalance,
        string $narration,
        string $externalReference = null,
    ): Transaction {
        return $user->transactions()->create([
            'amount' => $amount,
            'action' => $action,
            'type' => $type,
            'status' => $status,
            'reference' => $reference,
            'external_reference' => $externalReference,
            'opening_balance' => $openingBalance,
            'closing_balance' => $closingBalance,
            'narration' => $narration,
        ]);
    }
}
