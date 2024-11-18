@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                            <div class="d-flex align-items-center flex-row flex-wrap">
                                <div class="position-relative me-3">
                                    <img src="{{ $user->profile_picture }}" alt="" height="120"
                                        class="rounded-circle">
                                    <a href="#"
                                        class="thumb-md justify-content-center d-flex align-items-center bg-primary text-white rounded-circle position-absolute end-0 bottom-0 border border-3 border-card-bg">
                                        <i class="fas fa-camera"></i>
                                    </a>
                                </div>
                                <div class="">
                                    <h5 class="fw-semibold fs-22 mb-1">{{ $user->full_name }}</h5>
                                    <p class="mb-0 text-muted fw-medium">{{ $user->account_type }}</p>
                                </div>
                            </div>
                        </div><!--end col-->

                        @if ($user->account_type === 'RIDER')
                            <div class="col-lg-4 ms-auto align-self-center">
                                <div class="d-flex justify-content-center">
                                    <div class="border-dashed rounded border-theme-color p-2 me-2 flex-grow-1 flex-basis-0">
                                        <h5 class="fw-semibold fs-22 mb-1">75</h5>
                                        <p class="text-muted mb-0 fw-medium">Projects</p>
                                    </div>
                                    <div class="border-dashed rounded border-theme-color p-2 me-2 flex-grow-1 flex-basis-0">
                                        <h5 class="fw-semibold fs-22 mb-1">68%</h5>
                                        <p class="text-muted mb-0 fw-medium">Success Rate</p>
                                    </div>
                                    <div class="border-dashed rounded border-theme-color p-2 me-2 flex-grow-1 flex-basis-0">
                                        <h5 class="fw-semibold fs-22 mb-1">$8620</h5>
                                        <p class="text-muted mb-0 fw-medium">Earning</p>
                                    </div>
                                </div>
                            </div><!--end col-->
                        @endif
                    </div><!--end row-->
                </div><!--end card-body-->
            </div><!--end card-->
        </div> <!--end col-->
    </div><!--end row-->

    <div class="row justify-content-center">
        <div class="col-md-4">
            @include('admin.users._side-bar', ['user' => $user])
        </div> <!--end col-->
        <div class="col-md-8">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link fw-medium active" data-bs-toggle="tab" href="#settings" role="tab"
                        aria-selected="false">Profile</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link fw-medium" data-bs-toggle="tab" href="#security" role="tab"
                        aria-selected="false">Security</a>
                </li> --}}
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane p-3 active" id="settings" role="tabpanel">
                    @include('admin.users._profile')
                </div>
                <div class="tab-pane " id="security" role="tabpanel">
                    @include('admin.users._change-password')
                </div>
            </div>
        </div> <!--end col-->
    </div><!--end row-->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

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


                        spinner.hide()
                        buttonTest.show()

                        setTimeout(function() {
                            displayMessage(response.message, "success")
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
            });
        });
    </script>
@endpush
