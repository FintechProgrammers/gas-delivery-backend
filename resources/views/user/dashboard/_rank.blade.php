@if (Auth::user()->is_ambassador)
<div class="card custom-card overflow-hidden hrm-main-card secondary">
    <div class="card-body mb-3">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h6 class="fw-semibold text-primary mb-3">Current Rank</h6>
                @if (!empty(Auth::user()->rank))
                    <span class="fs-25 d-flex align-items-center">{{ Auth::user()->rank->name }}</span>
                @else
                    <span class="fs-25 d-flex align-items-center">No Rank</span>
                @endif
            </div>
            <div>
                <span class="avatar avatar-md bg-primary rounded-5">
                    <i class="ri-verified-badge-fill"></i>
                </span>
            </div>
        </div>
        @if (!empty(Auth::user()->rank))
        <div class="flex-fill">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="d-block fw-semibold">Next Rank Progress</span>
                <span class="d-block text-secondary">Delta 500</span>
            </div>
            <div class="progress progress-animate progress-xs" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-striped bg-secondary" style="width: 80%"></div>
            </div>
        </div>
        @endif
    </div>

    {{-- <div id="analytics-bouncerate" class="mt-1 w-100"></div> --}}
</div>
@else
    <div class="card custom-card overflow-hidden">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-center">
                <div class="filemanager-upgrade-storage d-flex align-items-center"
                    style="flex-direction: column; justify-content: space-between">

                    <img src="https://img.icons8.com/cute-clipart/64/diamond.png" alt="">

                    <div class="text-default text-center mt-3">
                        <span class="fs-15 fw-semibold">Upgrade with Ambassador Package to get access to Full
                            system</span>
                    </div>
                    <div class="mt-3 d-grid">
                        <button type="button" data-url="{{ route('ambassedor.payment.confirm') }}"
                            data-bs-toggle="modal" data-bs-target="#primaryModal"
                            class="btn btn-primary-gradient trigerModal">{{ __('Upgrade') }}</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif
