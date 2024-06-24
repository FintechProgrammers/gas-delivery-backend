<h6>Withdrawal</h6>
<hr />
<div class="row text-start ft-md">
    <div class="col-lg-12">
        <p class="make-text-bold mb-0">Reference</p>
        <p class="make-text-medium ft-sm wrap-text">{{ $withdrawal->reference ?? '--------' }}
        </p>
    </div>
    <div class="col-lg-12">
        <p class="make-text-bold mb-0">Date</p>
        <p class="make-text-medium ft-sm">
            {{ $withdrawal->created_at ? $withdrawal->created_at->format('jS, M Y H:i A') : 'unknown' }}
        </p>
    </div>
    <div class="col-lg-6">
        <p class="make-text-bold mb-0">Amount</p>
        <p class="make-text-medium ft-sm">
            {{ $withdrawal->amount ? '$' . $withdrawal->amount : 'unknown' }}
        </p>
    </div>
    <div class="col-lg-6">
        <p class="make-text-bold mb-0">Status</p>
        <p class="make-text-medium ft-sm">
            @if ( $withdrawal->status === 'completed')
                <span class="badge bg-success">Completed</span>
            @elseif ( $withdrawal->status === 'declined')
                <span class="badge bg-danger">Declined</span>
            @else
                <span class="badge bg-warning">Pending</span>
            @endif
        </p>
    </div>
</div>
<div class="row text-start ft-md">
    <div class="col-lg-12">
        <p class="make-text-bold mb-0"><strong>Recipient Details</strong></p>
    </div>
    @if ($details->account_number)
        <div class="col-lg-6">
            <p class="make-text-bold mb-0">Account Number</p>
            <p class="make-text-medium ft-sm">
                {{ $details->account_number }}
            </p>
        </div>
    @endif
    @if ($details->account_name)
        <div class="col-lg-6">
            <p class="make-text-bold mb-0">Account Name</p>
            <p class="make-text-medium ft-sm">
                {{ $details->account_name }}
            </p>
        </div>
    @endif
</div>
