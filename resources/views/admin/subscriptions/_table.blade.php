@forelse ($subscriptions as $item)
    <tr>
        <td>
            <x-profile-component name="{{ $item->user->name }}" email="{{ $item->user->email }}"
                image="{{ $item->user->profile_picture }}" />
        </td>
        <td>
            {{ optional($item->service)->name }}
        </td>
        <td>
            {{ $item->start_date->format('jS,M Y H:i A') }}
        </td>
        <td>
            {{ $item->end_date->format('jS,M Y H:i A') }}
        </td>
        <td class="text-center">
            @if ($item->is_active)
                <span class="badge bg-success-transparent">Active</span>
            @else
                <span class="badge bg-warning-transparent">Expired</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center"><span class="text-warning">no data available</span></td>
    </tr>
@endforelse
<tr style="border: none;">
    <td colspan="5" style="border: none;">
        {{ $subscriptions->links('vendor.pagination.custom') }}
    </td>
</tr>
