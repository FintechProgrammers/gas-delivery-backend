<div class="card custom-card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="flex-fill">
                <p class="mb-1 fs-5 fw-semibold text-default">{{ $item->value }}</p>
                <p class="mb-0 text-muted">{{ $item->title }}</p>
                @if ($item->link)
                    <p class="mb-0 fs-11"><a href="javascript:void(0);" class="text-success text-decoration-underline">View
                            All</a></p>
                @endif
            </div>
            <div class="ms-2">
                <span class="avatar {{ $item->color }} rounded-circle fs-20"><i class="{{ $item->icon }}"></i></span>
            </div>
        </div>
    </div>
</div>
