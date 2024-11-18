<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">Customer Information</h4>
            </div><!--end col-->
            <div class="col-auto">
                @if ($order->is_paid)
                    <span class="badge rounded text-success bg-success-subtle fs-12 p-1">Paid</span>
                @else
                    <span class="badge rounded text-warning bg-warning-subtle fs-12 p-1">Payment pending</span>
                @endif
            </div><!--end col-->
        </div> <!--end row-->
    </div><!--end card-header-->
    <div class="card-body pt-0">
        <div>
            <div class="d-flex justify-content-between mb-2">
                <p class="text-body fw-semibold"><i
                        class="iconoir-people-tag text-secondary fs-20 align-middle me-1"></i>Full Name :</p>
                <p class="text-body-emphasis fw-semibold">{{ optional($order->user)->full_name }}</p>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <p class="text-body fw-semibold"><i
                        class="iconoir-mail text-secondary fs-20 align-middle me-1"></i>Email :</p>
                <p class="text-body-emphasis fw-semibold">{{ optional($order->user)->email }}</p>
            </div>
            {{-- <div class="d-flex justify-content-between">
                <p class="text-body fw-semibold"><i
                        class="iconoir-map-pin text-secondary fs-20 align-middle me-1"></i>Address :</p>
                <p class="text-body-emphasis fw-semibold">
                    {{ optional($order->business->profile)->address }}
                </p>
            </div> --}}
        </div>
    </div><!--card-body-->
</div><!--end card-->
