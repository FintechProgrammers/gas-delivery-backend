@extends('layouts.auth')

@section('content')
    <p class="h5 fw-semibold mb-2 text-center">{{ __('Administrative Sign In') }}</p>
    <form action="{{ route('admin.login.post') }}" method="POST" id="loginForm">
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
            <div class="col-xl-12 mb-3">
                <label for="signin-password" class="form-label text-default d-block">Password
                    <a href="{{ route('admin.forgot.password.index') }}"
                        class="float-end text-danger">{{ __('Forgot your password?') }}</a>
                </label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control form-control-lg" id="signin-password"
                        placeholder="password">
                    <button class="btn btn-light" type="button" onclick="createpassword('signin-password',this)"
                        id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                </div>
                <div class="mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                        <label class="form-check-label text-muted fw-normal" for="defaultCheck1">
                            {{ __('Remember me') }}
                        </label>
                    </div>
                </div>
                @error('password')
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
                    <span id="text">{{ __('Log in') }}</span>
                </button>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        $('#loginForm').submit(function(e) {
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
                        location.href = response.route
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
