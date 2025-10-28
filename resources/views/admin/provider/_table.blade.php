@forelse ($providers as $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->name }}</td>

        {{-- is_default switch --}}
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input feature-switch" type="checkbox" role="switch"
                    id="is_default_{{ $item->id }}"
                    data-url="{{ route('admin.providers.toggle.feature', $item->uuid) }}" data-feature="is_default"
                    {{ $item->is_default ? 'checked' : '' }}>
                <label class="form-check-label" for="is_default_{{ $item->id }}">
                    Default
                </label>
            </div>
        </td>

        {{-- has_transaction switch --}}
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input feature-switch" type="checkbox" role="switch"
                    id="has_transaction_{{ $item->id }}"
                    data-url="{{ route('admin.providers.toggle.feature', $item->uuid) }}" data-feature="has_transaction"
                    {{ $item->has_transaction ? 'checked' : '' }}>
                <label class="form-check-label" for="has_transaction_{{ $item->id }}">
                    Transactions
                </label>
            </div>
        </td>

        {{-- has_account switch --}}
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input feature-switch" type="checkbox" role="switch"
                    id="has_account_{{ $item->id }}"
                    data-url="{{ route('admin.providers.toggle.feature', $item->uuid) }}" data-feature="has_account"
                    {{ $item->has_account ? 'checked' : '' }}>
                <label class="form-check-label" for="has_account_{{ $item->id }}">
                    Accounts
                </label>
            </div>
        </td>

        <td>
            {{-- Actions here --}}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center">
            <span class="text-warning">No data available</span>
        </td>
    </tr>
@endforelse

<tr style="border: none;">
    <td colspan="6" style="border: none;">
        {{ $providers->links('vendor.pagination.custom') }}
    </td>
</tr>
