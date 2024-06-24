@extends('layouts.auth')

@section('content')
    <p class="h5 fw-semibold mb-2 text-center">{{ __('Forgot Password') }}</p>
    <form action="{{ route('admin.forgot.password.store') }}" method="POST" id="forgotForm">
        @csrf
        <div class="row gy-3">
            <div class="col-xl-12 mb-3">
                <label for="signin-username" class="form-label text-default">Email</label>
                <input type="text" class="form-control form-control-lg" name="email" id="signin-username"
                    placeholder="Email Address">
                @error('email')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-xl-12 d-grid mt-2">
                <button class="btn btn-lg btn-primary" type="submit">
                    <div class="spinner-border" style="display: none" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <span id="text">{{ __('Submit') }}</span>
                </button>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        $('#forgotForm').submit(function(e) {
            e.preventDefault();


            // Remove any existing error messages
            $('.error-message').remove();

            // Serialize form data
            var formData = $(this).serialize();

            const button = $(this).find('button')
            const spinner = button.find('.spinner-border')
            const buttonTest = button.find('#text')

            // Send AJAX request
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    buttonTest.hide()
                    spinner.show()
                    button.attr('disabled', true)
                },
                success: function(response) {

                    displayMessage(response.message, "success")

                    $('#forgotForm')[0].reset();

                    spinner.hide()
                    buttonTest.show()
                    button.attr('disabled', false)

                },
                error: function(xhr, status, error) {
                    spinner.hide()
                    buttonTest.show()
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
                        // console.log(xhr.responseJSON)
                        displayMessage(xhr.responseJSON.message, "error")
                    }
                }
            });
        })
    </script>
@endpush
