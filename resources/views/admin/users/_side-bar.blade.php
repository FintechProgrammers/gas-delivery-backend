<div class="card mb-3">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">Personal Information</h4>
            </div><!--end col-->

        </div> <!--end row-->
    </div><!--end card-header-->
    <div class="card-body pt-0">

        <ul class="list-unstyled mb-0">
            <li class=""><i class="las la-birthday-cake me-2 text-secondary fs-22 align-middle"></i> <b>
                    Birth Date </b> : {{ $user->date_of_birth }}</li>
            <li class="mt-2"><i class="las la-phone me-2 text-secondary fs-22 align-middle"></i> <b> Phone
                </b> : {{ $user->phone_number }}</li>
            <li class="mt-2"><i class="las la-envelope text-secondary fs-22 align-middle me-2"></i> <b> Email
                </b> : {{ $user->email }}</li>
        </ul>
    </div><!--end card-body-->
</div><!--end card-->
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">Wallet Balance</h4>
            </div><!--end col-->

        </div> <!--end row-->
    </div><!--end card-header-->
    <div class="card-body pt-0">
        <h4>₦{{ number_format($user?->wallet?->balance ?? 0, 2) }}</h4>
    </div><!--end card-body-->
</div><!--end card-->
