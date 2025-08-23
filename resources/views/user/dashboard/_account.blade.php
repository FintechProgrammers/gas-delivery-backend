@if (Auth::user()->is_ambassador)
    <div class="card custom-card overflow-hidden hrm-main-card danger">
        <div class="card-body p-0">
            <div class="p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="mb-3">
                        <span class="d-block fw-semibold fs-15">Total Earnings</span>
                        <!--       <span class="text-muted fs-12 d-block">87 Points</span>-->
                    </div>
                    <div>

                        <a href="{{ route('withdrawal.index') }}" class="btn btn-primary label-btn">
                            <i class="bi bi-arrow-down fs-18 label-btn-icon me-2"></i>
                            Withdraw Balance
                        </a>
                    </div>
                </div>
                <div>
                    <p class="mb-0">
                        <span
                            class="fs-24 fw-semibold text-success">${{ number_format(Auth::user()->bonuWallet ? Auth::user()->bonuWallet->amount * systemSettings()->bv_equivalent : 0.0) }}</span>
                        <span class="text-muted fs-12 ms-1">{{ number_format(Auth::user()->bonuWallet? Auth::user()->bonuWallet->amount : 0.00) }} BV</span>
                    </p>
                    <!--    <span class="text-muted">0.9 Lt more to reach goal</span>-->
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="card custom-card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-4">
                <div class="me-1">
                    <h6 class="fs-15 mg-b-3">Bonus Wallet</h6>
                </div>
            </div>
            <p class="fs-24 fw-semibold">{{ number_format(Auth::user()->bonuWallet? Auth::user()->bonuWallet->amount : 0.00) }} BV</p>
            <p class="mb-1 fs-14">${{ number_format(Auth::user()->bonuWallet ? Auth::user()->bonuWallet->amount * systemSettings()->bv_equivalent : 0.0) }}</p>
        </div>
    </div> --}}
@endif
