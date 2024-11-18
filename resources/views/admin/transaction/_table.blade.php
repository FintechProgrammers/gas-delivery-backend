@forelse ($transactions as $item)
    <tr>
        <td>
            {{ $loop->iteration }}
        </td>
        <td>
            @if (!empty($item->user))
                <x-profile-component name="{{ $item->user->full_name }}" email="{{ $item->user->email }}"
                    image="{{ $item->user->profile_picture }}" />
            @endif
        </td>
        <td>
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-semibold">{{ number_format($item->amount, 2) }} NGN</p>
            </div>
        </td>
        <td>
            @if ($item->type === 'credit')
                <span class="badge bg-success">Credit</span>
            @else
                <span class="badge bg-danger">Debit</span>
            @endif
        </td>
        <td>
            <span class="badge bg-transparent border border-secondary text-secondary">{{ $item->action }}</span>
        </td>
        <td>
            @if ($item->status === 'pending')
                <span class="badge bg-transparent border border-warning text-warning">Pending</span>
            @elseif ($item->status === 'completed')
                <span class="badge bg-transparent border border-success text-success">Completed</span>
            @elseif ($item->status === 'failed')
                <span class="badge bg-transparent border border-danger text-danger">Cancelled</span>
            @endif
        </td>
        <td>
            <div class="align-items-center">
                <p class="mb-0">{{ $item->created_at->format('d-m-Y h:i A') }}</p>
            </div>
        </td>
        <td>
            <a class="btn btn-primary btn-sm rounded " href="{{ route('admin.transactions.show', $item->uuid) }}">
                Details
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center"><span class="text-warning">no data available</span></td>
    </tr>
@endforelse
<tr style="border: none;">
    <td colspan="7" style="border: none;">
        {{ $transactions->links('vendor.pagination.custom') }}
    </td>
</tr>
