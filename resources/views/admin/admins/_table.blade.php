@forelse ($admins as $item)
    <tr>
        <td>
            <x-profile-component name="{{ $item->name }}" email="{{ $item->email }}"
                image="{{ $item->profile_picture }}" />
        </td>
        <td>

            @forelse ($item->getRoleNames() as $role)
                <span class="badge bg-info">{{ $role }}</span>
            @empty
                <div class="text-muted">No Data Available</div>
            @endforelse
        </td>
        <td>{{ $item->created_at->format('jS, M Y H:i A') }}</td>
        <td>
            @if ($item->status === 'active')
                <span class="badge bg-success">Active</span>
            @elseif ($item->status === 'suspended')
                <span class="badge bg-warning">Suspended</span>
            @endif
        </td>
        <td>
            <a aria-label="anchor" href="javascript:void(0);" class="" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="bi bi-three-dots fs-22"></i>
            </a>
            <ul class="dropdown-menu" style="">
                <li class="mb-0">
                    <a href="{{ route('admin.admins.show', $item->uuid) }}" class="dropdown-item">Profile</a>
                </li>
                <li class="mb-0">
                    <a href="javascript:void(0);" class="dropdown-item trigerModal"
                        data-url="{{ route('admin.admins.edit', $item->uuid) }}" data-bs-toggle="modal"
                        data-bs-target="#primaryModal">Edit</a>
                </li>
                @if ($item->status === 'suspended')
                    <li class="mb-0">
                        <a href="javascript:void(0);" class="dropdown-item btn-action"
                            data-url="{{ route('admin.admins.activate', $item->uuid) }}"
                            data-action="you want to activate {{ Str::upper($item->name) }}">Activate</a>
                    </li>
                @else
                    <li class="mb-0">
                        <a href="javascript:void(0);" class="dropdown-item btn-action"
                            data-url="{{ route('admin.admins.suspend', $item->uuid) }}"
                            data-action="you want to suspend {{ Str::upper($item->name) }}">Suspend</a>
                    </li>
                @endif
                <li class="mb-0">
                    <a href="javascript:void(0);" class="dropdown-item btn-action"
                        data-url="{{ route('admin.admins.delete', $item->uuid) }}"
                        data-action="you want to delete {{ Str::upper($item->name) }}">Delete</a>
                </li>
            </ul>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center"><span class="text-warning">no data available</span></td>
    </tr>
@endforelse
<tr style="border: none;">
    <td colspan="5" style="border: none;">
        {{ $admins->links('vendor.pagination.custom') }}
    </td>
</tr>
