@forelse ($orders as $item)
    <tr>
        <td>
            <div class="d-flex align-items-center">
                <div class="lh-1">
                    <span class="avatar avatar-md online avatar-rounded me-2">
                        <img src="../assets/images/faces/4.jpg" alt="">
                    </span>
                </div>
                <div class="align-items-center">
                    <span class="fs-12 text-muted">Name</span>
                    <p class="mb-0">Amanda Nanes</p>
                </div>
            </div>
        </td>
        <td>
            <div class="align-items-center">
                <span class="fs-12 text-muted">Price</span>
                <p class="mb-0 fw-semibold">$229.99</p>
            </div>
        </td>
        <td>
            <div class="align-items-center">
                <span class="fs-12 text-muted">Delivery Date</span>
                <p class="mb-0">24 May 2022</p>
            </div>
        </td>
        <td>
            <span class="avatar avatar-md">
                <img src="../assets/images/ecommerce/png/1.png" alt="">
            </span>
        </td>
        <td>
            <div class="align-items-center">
                <span class="fs-12 text-muted">Status</span>
                <p class="mb-0">24 May 2022</p>
            </div>
        </td>
        <td>
            <a aria-label="anchor" href="javascript:void(0);">
                <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center"><span class="text-warning">no data available</span></td>
    </tr>
@endforelse
<tr style="border: none;">
    <td colspan="6" style="border: none;">
        {{ $orders->links('vendor.pagination.custom') }}
    </td>
</tr>
