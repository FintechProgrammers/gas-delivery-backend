<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">User Information</h4>
            </div><!--end col-->
        </div> <!--end row-->
    </div><!--end card-header-->
    <div class="card-body pt-0">
        <div>
            @if($transaction->user)
                <div class="d-flex justify-content-between mb-2">
                    <p class="text-body fw-semibold"><i class="iconoir-people-tag text-secondary fs-20 align-middle me-1"></i>Full Name :</p>
                    <p class="text-body-emphasis fw-semibold">{{ $transaction->user->full_name }}</p>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <p class="text-body fw-semibold"><i class="iconoir-mail text-secondary fs-20 align-middle me-1"></i>Email :</p>
                    <p class="text-body-emphasis fw-semibold">{{ $transaction->user->email }}</p>
                </div>
                @if($transaction->user->phone_number)
                    <div class="d-flex justify-content-between mb-2">
                        <p class="text-body fw-semibold"><i class="iconoir-phone text-secondary fs-20 align-middle me-1"></i>Phone :</p>
                        <p class="text-body-emphasis fw-semibold">{{ $transaction->user->phone_number }}</p>
                    </div>
                @endif
            @else
                <p class="text-muted">No user information available</p>
            @endif
        </div>
    </div><!--card-body-->
</div><!--end card-->
