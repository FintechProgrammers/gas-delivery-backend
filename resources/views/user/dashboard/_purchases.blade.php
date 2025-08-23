<div class="card custom-card">
    <div class="card-header justify-content-between">
        <div class="card-title">
            Package History
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-nowrap table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Service Name</th>
                        <th scope="col">End Date</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (auth()->user()->subscriptions as $item)
                        <tr>
                            <td>
                                <span class="text-success fw-semibold">{{ optional($item->service)->name }}</span>
                            </td>
                            <td>
                                {{ $item->end_date->format('jS,M Y H:i A') }}
                            </td>
                            <td>
                                @if ($item->is_active)
                                    <span class="badge bg-success-transparent">Active</span>
                                @else
                                    <span class="badge bg-warning-transparent">Expired</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
