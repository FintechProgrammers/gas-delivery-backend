@forelse ($revenues as $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-semibold">{{ number_format($item->amount, 2) }} NGN</p>
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-semibold">{{ number_format($item->vendor_fee, 2) }} NGN</p>
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-semibold">{{ number_format($item->rider_fee, 2) }} NGN</p>
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-semibold">{{ number_format($item->referral_fee, 2) }} NGN</p>
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-semibold">{{ $item->created_at->format('jS, M Y H:i A') }}</p>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center"><span class="text-warning">no data available</span></td>
    </tr>
@endforelse
<tr style="border: none;">
    <td colspan="6" style="border: none;">
        {{ $revenues->links('vendor.pagination.custom') }}
    </td>
</tr>
