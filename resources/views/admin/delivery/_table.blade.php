@forelse ($deliveries as $item)
    <tr>
        <td>
            <x-profile-component name="{{ $item->user->full_name }}" email="{{ $item->user->email }}"
                image="{{ $item->user->profile_picture }}" />
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>
            @if ($item->is_paid)
                <span class="badge bg-success">Paid</span>
            @else
                <span class="badge bg-warning">UnPaid</span>
            @endif
        </td>
        <td>
            @if ($item->status === 'active')
                <span class="badge bg-success">Active</span>
            @elseif ($item->status === 'dispatched')
                <span class="badge bg-warning">Dispatched</span>
            @elseif ($item->status === 'delivered')
                <span class="badge bg-warning">Delivered</span>
            @endif
        </td>
        <td>{{ $item->created_at->format('jS, M Y H:i A') }}</td>
        <td>
            <a aria-label="anchor" href="javascript:void(0);" class="" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="bi bi-three-dots fs-22"></i>
            </a>
            <ul class="dropdown-menu" style="">
                <li class="mb-0">
                    <a href="{{ route('admin.users.show', $item->uuid) }}" class="dropdown-item">Profile</a>
                </li>
            </ul>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center"><span class="text-warning">no data available</span></td>
    </tr>
@endforelse
<tr style="border: none;">
    <td colspan="10" style="border: none;">
        {{ $deliveries->links('vendor.pagination.custom') }}
    </td>
</tr>
