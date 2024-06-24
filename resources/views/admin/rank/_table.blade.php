@forelse ($ranks as $item)
    <tr>
        <td>
            <div class="d-flex align-items-center lh-1">
                <div class="me-2">
                    <span class="">
                        <img src="{{ $item->file_url }}" width="60px" width="60px" alt="">
                    </span>
                </div>
                <div>
                    <span class="d-block fw-semibold mb-1">{{ $item->name }}</span>
                </div>
            </div>
        </td>
        <td class="text-center">${{ $item->creteria }}</td>
        <td>
            <a aria-label="anchor" href="javascript:void(0);" class="" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots fs-22"></i>
            </a>
            <ul class="dropdown-menu" style="">
                <li class="mb-0">
                    <a href="javascript:void(0);" class="dropdown-item trigerModal"
                        data-url="{{ route('admin.rank.edit', $item->uuid) }}" data-bs-toggle="modal"
                        data-bs-target="#primaryModal">Edit</a>
                </li>
                <li class="mb-0">
                    <a href="javascript:void(0);" class="dropdown-item btn-action"
                        data-url="{{ route('admin.rank.delete', $item->uuid) }}"
                        data-action="you want to delete {{ $item->name }}">Delete</a>
                </li>
            </ul>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="2" class="text-center"><span class="text-warning">no data available</span></td>
    </tr>
@endforelse
<tr style="border: none;">
    <td colspan="2" style="border: none;">
        {{ $ranks->links('vendor.pagination.custom') }}
    </td>
</tr>
