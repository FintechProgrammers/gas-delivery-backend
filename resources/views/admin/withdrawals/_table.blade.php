@forelse ($withdrawals as $item)
    <tr>
        <td>
            <x-profile-component name="{{ $item->user->name }}" email="{{ $item->user->email }}"
                image="{{ $item->user->profile_picture }}" />
        </td>
        <td>
            <span class="text-success fw-semibold">{{ $item->reference }}</span>
        </td>
        <td>
            ${{ $item->amount }}
        </td>
        <td>
            @if ($item->status === 'completed')
                <span class="badge bg-success">Completed</span>
            @elseif ($item->status === 'declined')
                <span class="badge bg-danger">Declined</span>
            @else
                <span class="badge bg-warning">Pending</span>
            @endif
        </td>
        <td>
            {{ $item->created_at->format('jS,M Y H:i A') }}
        </td>
        <td>
            <button type="button" class="btn btn-primary trigerModal"
                data-url="{{ route('admin.withdrawals.details', $item->uuid) }}" data-bs-toggle="modal"
                data-bs-target="#primaryModal">Details
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center">
            <span class="text-warning">no data available</span>
        </td>
    </tr>
@endforelse
<tr style="border: none;">
    <td colspan="6" style="border: none;">
        {{ $withdrawals->links('vendor.pagination.custom') }}
    </td>
</tr>
