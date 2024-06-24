@extends('layouts.auth')

@section('content')
    <p class="h5 fw-semibold mb-2 text-center">{{ __('Sign Up') }}</p>
    <form action="{{ route('register') }}" method="POST" id="registerForm">
        @csrf
        <div class="mb-3">
            <label for="signin-username" class="form-label text-default">Name</label>
            <input type="text" class="form-control form-control-lg" name="name" id="signin-username" placeholder="Name">
        </div>
        <div class="mb-3">
            <label for="signin-username" class="form-label text-default">Username</label>
            <input type="text" class="form-control form-control-lg" name="username" id="signin-username"
                placeholder="Username">
        </div>
        <div class="mb-3">
            <label for="signin-email" class="form-label text-default">Email</label>
            <input type="email" class="form-control form-control-lg" name="email" id="signin-username"
                placeholder="Email">
        </div>
        <div class="mb-3">
            <label for="signin-email" class="form-label text-default">Referral Code(Optional)</label>
            <input type="text" class="form-control form-control-lg" name="referral_code"
                value="{{ $request->query('code') }}" id="signin-username" placeholder="Referral Code">
        </div>
        <div class="mb-3">
            <label for="signin-password" class="form-label text-default d-block">{{ __('Password') }}</label>
            <div class="input-group">
                <input type="password" name="password" class="form-control form-control-lg" id="signin-password"
                    placeholder="password">
                <button class="btn btn-light" type="button" onclick="createpassword('signin-password',this)"
                    id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
            </div>
        </div>
        <div class="mb-3">
            <label for="confirm-password" class="form-label text-default d-block">{{ __('Confirm Password') }}</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" class="form-control form-control-lg"
                    id="confirm-password" placeholder="password">
                <button class="btn btn-light" type="button" onclick="createpassword('confirm-password',this)"
                    id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
            </div>
        </div>
        <div class="col-xl-12 d-grid mt-2">
            <button class="btn btn-lg btn-primary" type="submit">
                <div class="spinner-border" style="display: none" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <span id="text">{{ __('Submit') }}</span>
            </button>
        </div>
    </form>
    <div class="text-center">
        <p class="fs-12 text-muted mt-3">Already have an account? <a href="{{ route('login') }}" class="text-primary">Sign
                In</a></p>
    </div>
@endsection
@push('scripts')
    <script>
        $('#registerForm').submit(function(e) {
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

                    setTimeout(function() {
                        location.href = response.data.route
                    }, 2000); // 2000 milliseconds = 2 seconds
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
