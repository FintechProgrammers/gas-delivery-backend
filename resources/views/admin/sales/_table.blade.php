@forelse ($sales as $item)
    <tr>
        <td>
            <x-profile-component name="{{ $item->user->name }}" email="{{ $item->user->email }}"
                image="{{ $item->user->profile_picture }}" />
        </td>
        <td>
            {{ optional($item->service)->name }}
        </td>
        <td class="text-center">${{ $item->amount }}</td>
        <td class="text-center">
            {{ $item->created_at->format('jS, M Y H:i A') }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center"><span class="text-warning">no data available</span></td>
    </tr>
@endforelse
<tr style="border: none;">
    <td colspan="4" style="border: none;">
        {{ $sales->links('vendor.pagination.custom') }}
    </td>
</tr>
