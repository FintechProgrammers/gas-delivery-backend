@forelse ($products as $item)
    <tr>
        <td>
            {{ $item->name }}
        </td>
        <td>
            <a aria-label="anchor" href="javascript:void(0);" class="" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots fs-22"></i>
            </a>
            <ul class="dropdown-menu" style="">
                <li class="mb-0">
                    <a href="javascript:void(0);" class="dropdown-item trigerModal"
                        data-url="{{ route('admin.product.edit', $item->uuid) }}" data-bs-toggle="modal"
                        data-bs-target="#primaryModal">Edit</a>
                </li>
                <li class="mb-0">
                    <a href="javascript:void(0);" class="dropdown-item btn-action"
                        data-url="{{ route('admin.product.delete', $item->uuid) }}"
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
        {{ $products->links('vendor.pagination.custom') }}
    </td>
</tr>
