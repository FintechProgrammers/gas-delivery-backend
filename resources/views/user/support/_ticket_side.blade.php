<div class="card">
    <div class="card-body">
        <h6 class="mb-0">Ticket Info</h6>
        <hr />
        <h6>Ticket ID</h6>
        <p>{{ $ticket->ticket_code }}</p>
        <h6>Subject</h6>
        <p>{{ optional($ticket->subject)->name }}</p>
        <h6>Date Created</h6>
        <p>{{ $ticket->created_at->format('jS M, Y') }} at {{ $ticket->created_at->format('h:i A') }}</p>
        <h6>Status</h6>
        <p>
            @if ($ticket->status === 'open')
                <span class="badge bg-success">Open</span>
            @elseif ($ticket->status === 'pending')
                <span class="badge bg-warning">Pending</span>
            @elseif ($ticket->status === 'closed')
                <span class="badge bg-danger">Closed</span>
            @endif
        </p>

    </div>
</div>
