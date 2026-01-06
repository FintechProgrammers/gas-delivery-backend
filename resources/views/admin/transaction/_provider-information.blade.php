<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">Provider Information</h4>
            </div><!--end col-->
        </div> <!--end row-->
    </div><!--end card-header-->
    <div class="card-body pt-0">
        <div>
            @if($transaction->provider)
                <div class="d-flex justify-content-between mb-2">
                    <p class="text-body fw-semibold"><i class="iconoir-people-tag text-secondary fs-20 align-middle me-1"></i>Business Name :</p>
                    <p class="text-body-emphasis fw-semibold">{{ $transaction->provider->business_name ?? 'N/A' }}</p>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <p class="text-body fw-semibold"><i class="iconoir-mail text-secondary fs-20 align-middle me-1"></i>Email :</p>
                    <p class="text-body-emphasis fw-semibold">{{ $transaction->provider->email ?? 'N/A' }}</p>
                </div>
                @if($transaction->provider->phone_number)
                    <div class="d-flex justify-content-between mb-2">
                        <p class="text-body fw-semibold"><i class="iconoir-phone text-secondary fs-20 align-middle me-1"></i>Phone :</p>
                        <p class="text-body-emphasis fw-semibold">{{ $transaction->provider->phone_number }}</p>
                    </div>
                @endif
            @else
                <p class="text-muted">No provider information available</p>
            @endif
        </div>
    </div><!--card-body-->
</div><!--end card-->
