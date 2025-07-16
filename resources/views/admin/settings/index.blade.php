@extends('layouts.app')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Settings</p>
        </div>
    </div>
    <form method="POST" action="{{ route('admin.settings.store') }}" id="setting-form">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h6><b>{{ __('Withdrawal Settings') }}</b></h6>
                    <div class="col-lg-4 mb-3">{{ __('Minimum Withdrawal Amount') }}</label>
                        <div class="input-group">
                            <input type="number" min="0" name="minimum_withdrawal_amount" class="form-control"
                                id="basic-url"
                                value="{{ !empty(systemSettings()->minimum_withdrawal_amount) ? systemSettings()->minimum_withdrawal_amount : 0 }}"
                                aria-describedby="basic-addon3">
                            <span class="input-group-text">NGN</span>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">{{ __('Maximum Withdrawal Amount') }}</label>
                        <div class="input-group">
                            <input type="number" min="0" name="maximum_withdrawal_amount" class="form-control"
                                id="basic-url"
                                value="{{ !empty(systemSettings()->maximum_withdrawal_amount) ? systemSettings()->maximum_withdrawal_amount : 0 }}"
                                aria-describedby="basic-addon3">
                            <span class="input-group-text">NGN</span>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">{{ __('Withdrawal Fee') }}</label>
                        <div class="input-group">
                            <input type="number" min="0" name="withdrawal_fee" class="form-control" id="basic-url"
                                value="{{ !empty(systemSettings()->withdrawal_fee) ? systemSettings()->withdrawal_fee : 0 }}"
                                aria-describedby="basic-addon3">
                            <span class="input-group-text">NGN</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <h6><b>{{ __('Delivery Settings') }}</b></h6>

                <div class="mb-3">
                    <label for="delivery_mode" class="form-label">Delivery Rate Type</label>
                    <select name="delivery_rate_type" id="delivery_mode" class="form-select"
                        onchange="toggleDeliveryFields()">
                        <option value="per_km" {{ systemSettings()?->delivery_rate_type === 'per_km' ? 'selected' : '' }}>
                            Per
                            KM Rate</option>
                        <option value="tiered" {{ systemSettings()?->delivery_rate_type === 'tiered' ? 'selected' : '' }}>
                            Tiered / Range-Based</option>
                    </select>
                </div>

                {{-- Per KM Field --}}
                <div id="perKmField" class="row mb-3">
                    <div class="col-lg-6">
                        <label class="form-label">Price Per Distance</label>
                        <div class="input-group">
                            <span class="input-group-text">NGN</span>
                            <input type="number" min="1" name="price_per_km" class="form-control"
                                value="{{ systemSettings()->price_per_km ?? 0 }}">
                            <span class="input-group-text">Per KM</span>
                        </div>
                        <small class="text-muted">{{ __('Set price per kilometer') }}</small>
                    </div>
                </div>

                {{-- Tiered Rates --}}
                <div id="tieredField" class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Tiered Distance Rates</label>
                        <div id="tieredRatesWrapper">
                            @php
                                $tiers = systemSettings()->tiered_rates ?? [['min' => '', 'max' => '', 'price' => '']];
                            @endphp
                            @foreach ($tiers as $index => $tier)
                                <div class="row mb-2 tiered-rate-item">
                                    <div class="col-md-3">
                                        <input type="number" name="tiered_rates[{{ $index }}][min]"
                                            class="form-control" placeholder="Min (km)" value="{{ $tier['min'] }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="tiered_rates[{{ $index }}][max]"
                                            class="form-control" placeholder="Max (km)" value="{{ $tier['max'] }}">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="tiered_rates[{{ $index }}][price]"
                                            class="form-control" placeholder="Price (NGN)" value="{{ $tier['price'] }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="removeTier(this)">Remove</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addTier()">Add
                            Tier</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h6><b>{{ __('Referral System Settings') }}</b></h6>

                    {{-- Toggle Referral System --}}
                    <div class="col-lg-4 mb-3">
                        <label for="referral_is_active">{{ __('Enable Referral System') }}</label>
                        <div class="form-check form-switch">
                            <input type="hidden" name="referral_is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="referral_is_active" value="1"
                                id="referral_is_active" {{ systemSettings()->referral_is_active ? 'checked' : '' }}>
                        </div>
                    </div>

                    {{-- Referral Bonus Per Purchase --}}
                    <div class="col-lg-4 mb-3">
                        <label for="referral_bonus_per_purchase">{{ __('Referral Bonus Per Purchase') }}</label>
                        <div class="input-group">
                            <input type="number" min="0" name="referral_bonus_per_purchase" class="form-control"
                                id="referral_bonus_per_purchase" value="{{ systemSettings()->referral_bonus ?? 0 }}"
                                aria-describedby="referral-addon">
                            <span class="input-group-text" id="referral-addon">NGN</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="">
                    <button class="btn btn-primary btn-block" type="submit">
                        <div class="spinner-border spinner-border-sm align-middle" style="display: none"
                            aria-hidden="true">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span id="text">Submit</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        $('#setting-form').submit(function(e) {
            e.preventDefault();

            // Remove any existing error messages
            $('.error-message').remove();

            // Serialize form data
            var formData = $(this).serialize();

            const button = $(this).find('button')
            const spinner = button.find('.spinner-border')
            const buttonText = button.find('#text')

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    buttonText.hide()
                    spinner.show()
                    button.attr('disabled', true)
                },
                success: function(response) {
                    console.log(response)
                    spinner.hide()
                    buttonText.show()
                    button.attr('disabled', false)

                    setTimeout(function() {
                        displayMessage(response.message, "success")
                    }, 2000); // 2000 milliseconds = 2 seconds

                },
                error: function(xhr, status, error) {
                    console.log(xhr)
                    spinner.hide()
                    buttonText.show()
                    button.attr('disabled', false)
                    // Handle error response
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;

                        $.each(errors, function(field, messages) {
                            // Find the corresponding field
                            var fieldInput = $('[name="' + field + '"]');
                            var fieldContainer = fieldInput.closest('.mb-3');

                            // Append error messages under the field container
                            $.each(messages, function(index, message) {
                                var errorMessage =
                                    '<div class="error-message text-danger">' +
                                    message + '</div>';
                                fieldContainer.append(errorMessage);
                            });
                        });
                    } else {
                        // Handle other error statuses
                        console.log(xhr.responseJSON)
                        displayMessage(xhr.responseJSON.message, "error")
                    }
                }
            });

        })

        document.addEventListener('DOMContentLoaded', toggleDeliveryFields);

        function toggleDeliveryFields() {
            const mode = document.getElementById('delivery_mode').value;

            const perKmField = document.getElementById('perKmField');
            const tieredField = document.getElementById('tieredField');
            const pricePerKmInput = document.querySelector('input[name="price_per_km"]');
            const tieredInputs = document.querySelectorAll('#tieredRatesWrapper input');

            if (mode === 'per_km') {
                perKmField.style.display = 'block';
                pricePerKmInput.disabled = false;

                tieredField.style.display = 'none';
                tieredInputs.forEach(input => input.disabled = true);
            } else {
                tieredField.style.display = 'block';
                tieredInputs.forEach(input => input.disabled = false);

                perKmField.style.display = 'none';
                pricePerKmInput.disabled = true;
            }
        }


        function addTier() {
            const wrapper = document.getElementById('tieredRatesWrapper');
            const index = wrapper.children.length;
            const html = `
            <div class="row mb-2 tiered-rate-item">
                <div class="col-md-3">
                    <input type="number" name="tiered_rates[${index}][min]" class="form-control" placeholder="Min (km)">
                </div>
                <div class="col-md-3">
                    <input type="number" name="tiered_rates[${index}][max]" class="form-control" placeholder="Max (km)">
                </div>
                <div class="col-md-4">
                    <input type="number" name="tiered_rates[${index}][price]" class="form-control" placeholder="Price (NGN)">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeTier(this)">Remove</button>
                </div>
            </div>`;
            wrapper.insertAdjacentHTML('beforeend', html);
        }

        function removeTier(button) {
            button.closest('.tiered-rate-item').remove();
        }
    </script>
@endpush
