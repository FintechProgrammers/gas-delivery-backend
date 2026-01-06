<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">Transaction #{{ $transaction->reference }}</h4>
                <p class="mb-0 text-muted mt-1">{{ $transaction->created_at ? $transaction->created_at->format('d F Y \a\t h:i a') : 'N/A' }}</p>
            </div><!--end col-->
            <div class="col-auto">
                @if ($transaction->status === 'pending')
                    <span class="badge bg-transparent border border-warning text-warning">Pending</span>
                @elseif ($transaction->status === 'completed')
                    <span class="badge bg-transparent border border-success text-success">Completed</span>
                @elseif ($transaction->status === 'failed')
                    <span class="badge bg-transparent border border-danger text-danger">Failed</span>
                @endif
            </div>
        </div> <!--end row-->
    </div><!--end card-header-->
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table mb-2">
                <thead class="table-light">
                    <tr>
                        <th class="">Type</th>
                        <th class="">Action</th>
                        <th class="">Amount</th>
                        <th class="">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="">
                            @if ($transaction->type === 'credit')
                                <span class="badge bg-success">Credit</span>
                            @else
                                <span class="badge bg-danger">Debit</span>
                            @endif
                        </td>
                        <td class="">
                            <span class="badge bg-transparent border border-secondary text-secondary">{{ ucfirst($transaction->action) }}</span>
                        </td>
                        <td class="">{{ number_format($transaction->amount, 2) }} NGN</td>
                        <td class="">
                            @if ($transaction->status === 'pending')
                                <span class="badge bg-transparent border border-warning text-warning">Pending</span>
                            @elseif ($transaction->status === 'completed')
                                <span class="badge bg-transparent border border-success text-success">Completed</span>
                            @elseif ($transaction->status === 'failed')
                                <span class="badge bg-transparent border border-danger text-danger">Failed</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($transaction->narration)
            <div class="mt-3">
                <h6 class="text-muted mb-2">Narration</h6>
                <p class="text-body-emphasis">{{ $transaction->narration }}</p>
            </div>
        @endif
    </div><!--card-body-->
</div><!--end card-->
