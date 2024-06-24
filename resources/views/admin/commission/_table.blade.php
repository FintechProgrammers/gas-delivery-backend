@forelse ($commissions as $item)
    <tr>
        <td>{{ ucfirst($item->name) }}</td>
        <td class="text-center">{{ $item->level}}</td>
        <td class="text-center">%{{ $item->commission_percentage}}</td>
        <td>
            <a aria-label="anchor" href="javascript:void(0);" class="" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots fs-22"></i>
            </a>
            <ul class="dropdown-menu" style="">
                <li class="mb-0">
                    <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" data-url="{{ route('admin.commission.edit', $item->uuid) }}" class="dropdown-item trigerCanvas">Edit</a>
                </li>
                <li class="mb-0">
                    <a href="javascript:void(0);" class="dropdown-item btn-action"
                        data-url="{{ route('admin.commission.delete', $item->uuid) }}"
                        data-action="you want to delete {{ $item->name }}">Delete</a>
                </li>
            </ul>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="text-center"><span class="text-warning">no data available</span></td>
    </tr>
@endforelse
