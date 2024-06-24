<div class="row">
    @foreach ($stats as $item)
        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-12 {{ !$item->show ? 'd-none':'' }}">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-top gap-2">
                        <div class="me-1">
                            <span class="avatar avatar-lg {{ $item->color }}">
                                <i class="{{ $item->icon }} fs-20"></i>
                            </span>
                        </div>
                        <div class="flex-fill">
                            <h5 class="d-block fw-semibold fs-18 mb-1">{{ $item->value }}</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted fs-12">{{ $item->title }}</div>
                                {{-- <div class="text-success">
                                    <i class="ti ti-trending-up fs-16 me-1 align-middle d-inline-flex"></i>+2.02%
                                </div> --}}
                            </div>
                            {{-- <a href="javascript:void(0);" class="text-primary fs-12">View All<i
                                    class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
