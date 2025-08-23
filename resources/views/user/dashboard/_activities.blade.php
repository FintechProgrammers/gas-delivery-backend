<div class="card custom-card">
    <div class="card-header justify-content-between">
        <div class="card-title">
            Recent Activity
        </div>
    </div>
    <div class="card-body">
        <div>
            <ul class="list-unstyled mb-0 crm-recent-activity" id="recent-activity">
                @foreach (Auth::user()->activities as $item)
                    <li class="crm-recent-activity-content">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-xs bg-primary-transparent avatar-rounded">
                                    <i class="bi bi-circle-fill fs-8"></i>
                                </span>
                            </div>
                            <div class="crm-timeline-content">
                                <span class="fw-semibold">{{ $item->log }}</span>
                            </div>
                            <div class="flex-fill text-end">
                                <span class="d-block text-muted fs-11 op-7">{{ $item->created_at->format('H:i A') }}</span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
