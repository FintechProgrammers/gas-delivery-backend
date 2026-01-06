<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">Transaction Details</h4>
            </div><!--end col-->
        </div> <!--end row-->
    </div><!--end card-header-->
    <div class="card-body pt-0">
        <div>
            <div class="d-flex justify-content-between mb-2">
                <p class="text-body fw-semibold"><i class="iconoir-receipt text-secondary fs-20 align-middle me-1"></i>Reference :</p>
                <p class="text-body-emphasis fw-semibold">{{ $transaction->reference }}</p>
            </div>

            @if($transaction->external_reference)
                <div class="d-flex justify-content-between mb-2">
                    <p class="text-body fw-semibold"><i class="iconoir-link text-secondary fs-20 align-middle me-1"></i>External Reference :</p>
                    <p class="text-body-emphasis fw-semibold">{{ $transaction->external_reference }}</p>
                </div>
            @endif

            <div class="d-flex justify-content-between mb-2">
                <p class="text-body fw-semibold"><i class="iconoir-wallet text-secondary fs-20 align-middle me-1"></i>Opening Balance :</p>
                <p class="text-body-emphasis fw-semibold">{{ number_format($transaction->opening_balance, 2) }} NGN</p>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <p class="text-body fw-semibold"><i class="iconoir-wallet text-secondary fs-20 align-middle me-1"></i>Closing Balance :</p>
                <p class="text-body-emphasis fw-semibold">{{ number_format($transaction->closing_balance, 2) }} NGN</p>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <p class="text-body fw-semibold"><i class="iconoir-calendar text-secondary fs-20 align-middle me-1"></i>Transaction Date :</p>
                <p class="text-body-emphasis fw-semibold">{{ $transaction->created_at ? $transaction->created_at->format('d M Y, h:i A') : 'N/A' }}</p>
            </div>
        </div>
    </div><!--card-body-->
</div><!--end card-->
