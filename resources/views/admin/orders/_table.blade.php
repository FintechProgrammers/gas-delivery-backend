@forelse ($orders as $item)
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
            @if (!empty($item->business))
                <x-profile-component name="{{ $item->business->full_name }}" email="{{ $item->business->email }}"
                    image="{{ $item->business->profile_picture }}" />
            @endif
        </td>
        <td>
            @if (!empty($item->rider))
                <x-profile-component name="{{ $item->rider->full_name }}" email="{{ $item->rider->email }}"
                    image="{{ $item->rider->profile_picture }}" />
            @else
                -----------
            @endif
        </td>
        <td>
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-semibold">{{ number_format($item->total_amount, 2) }} NGN</p>
            </div>
        </td>
        <td>
            @if ($item->is_paid)
                <span class="badge bg-success">Paid</span>
            @else
                <span class="badge bg-warning">Pending</span>
            @endif
        </td>
        <td>
            @if ($item->status === 'pending')
                <span class="badge bg-transparent border border-warning text-warning">Pending</span>
            @elseif ($item->status === 'active')
                <span class="badge bg-transparent border border-secondary text-secondary">Active</span>
            @elseif ($item->status === 'completed')
                <span class="badge bg-transparent border border-success text-success">Completed</span>
            @elseif ($item->status === 'cancelled')
                <span class="badge bg-transparent border border-danger text-danger">Cancelled</span>
            @endif
        </td>
        <td>
            <div class="align-items-center">
                <p class="mb-0">{{ $item->created_at->format('d-m-Y h:i A') }}</p>
            </div>
        </td>
        <td>
            <a class="btn btn-primary btn-sm rounded " href="{{ route('admin.order.show', $item->uuid) }}">
                Details
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="text-center"><span class="text-warning">no data available</span></td>
    </tr>
@endforelse
<tr style="border: none;">
    <td colspan="9" style="border: none;">
        {{ $orders->links('vendor.pagination.custom') }}
    </td>
</tr>
