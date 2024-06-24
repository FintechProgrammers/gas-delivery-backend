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
                    <div class="col-lg-6">
                        <h6>{{ __('Ambassador Settings') }}</h6>
                        <div class="mb-3">
                            <label for="">Ambassador Fee</label>
                            <div class="input-group ">
                                <span class="input-group-text">$</span>
                                <input type="number" min="0" step="any" name="ambassador_fee"
                                    value="{{ !empty(systemSettings()->ambassador_fee) ? systemSettings()->ambassador_fee : 0 }}"
                                    class="form-control" value="" aria-label="Amount (to the nearest dollar)">
                            </div>
                            <small class="text-muted">{{ __('The fee to become an Ambassador.') }} </small>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="basic-url" class="form-label">BV Settings</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon3">1BV is equivalent to </span>
                            <input type="number" min="1" name="bv_equivalent" class="form-control" id="basic-url"
                                value="{{ !empty(systemSettings()->bv_equivalent) ? systemSettings()->bv_equivalent : 0 }}"
                                aria-describedby="basic-addon3">
                            <span class="input-group-text">USD</span>
                        </div>
                        <small class="text-muted">{{ __('Set BV Equivalent in USD') }} </small>
                    </div>
                </div>
                <div class="row">
                    <h6><b>{{ __('Withdrawal Settings') }}</b></h6>
                    <div class="col-lg-6 mb-3">{{ __('Minimum Withdrawal Amount') }}</label>
                        <div class="input-group">
                            <input type="number" min="1" name="minimum_withdrawal_amount" class="form-control" id="basic-url"
                                value="{{ !empty(systemSettings()->minimum_withdrawal_amount) ? systemSettings()->minimum_withdrawal_amount : 0 }}"
                                aria-describedby="basic-addon3">
                            <span class="input-group-text">USD</span>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">{{ __('Maximum Withdrawal Amount') }}</label>
                        <div class="input-group">
                            <input type="number" min="1" name="maximum_withdrawal_amount" class="form-control" id="basic-url"
                                value="{{ !empty(systemSettings()->maximum_withdrawal_amount) ? systemSettings()->maximum_withdrawal_amount : 0 }}"
                                aria-describedby="basic-addon3">
                            <span class="input-group-text">USD</span>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">{{ __('Withdrawal Fee') }}</label>
                        <div class="input-group">
                            <input type="number" min="1" name="bv_equivalent" class="form-control" id="basic-url"
                                value="{{ !empty(systemSettings()->withdrawal_fee) ? systemSettings()->withdrawal_fee : 0 }}"
                                aria-describedby="basic-addon3">
                            <span class="input-group-text">USD</span>
                        </div>
                    </div>
                </div>
                <div class="">
                    <button class="btn btn-primary btn-block" type="submit">
                        <div class="spinner-border spinner-border-sm align-middle" style="display: none" aria-hidden="true">
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
                    spinner.hide()
                    buttonText.show()
                    button.attr('disabled', false)

                    setTimeout(function() {
                        displayMessage(response.message, "success")
                    }, 2000); // 2000 milliseconds = 2 seconds

                },
                error: function(xhr, status, error) {
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
    </script>
@endpush
