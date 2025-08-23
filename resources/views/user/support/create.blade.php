@extends('layouts.user.app')

@section('title','Create Ticket')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/quill/quill.bubble.css') }}">
@endpush

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Create Ticket</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <form method="POST" action="{{ route('tickets.store') }}" id="ticket-form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="form-text" class="form-label fs-14 text-dark">Subject</label>
                            <select class="subject-select js-states form-control" name="subject">
                                <option value=""></option>
                                @foreach ($subjects as $item)
                                    <option value="{{ $item->uuid }}">{{ Str::upper($item->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="">Body</label>
                            <textarea class="form-control" name="message" id="editor"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="form-text" class="form-label fs-14 text-dark">Attachement</label>
                            <input type="file" name="attachments" class="form-control">
                        </div>

                        <button class="btn btn-primary" type="submit">
                            <div class="spinner-border" style="display: none" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <span id="text">Submit</span>
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/libs/quill/quill.min.j') }}s"></script>
    <script>
        $(".user-select").select2({
            placeholder: "Select a user",
            allowClear: true,
            // dir: "ltr"
        });

        $(".subject-select").select2({
            placeholder: "Select a subject",
            allowClear: true,
            // dir: "ltr"
        });

        const quill = new Quill('#editor', {
            modules: {
                toolbar: undefined
            },
            theme: 'bubble'
        });
    </script>
    <script>
        $('#ticket-form').submit(function(e) {
            e.preventDefault();

            // Remove any existing error messages
            $('.error-message').remove();

            // Serialize form data
            var formData = new FormData($(this)[0]);

            const button = $(this).find('button')
            const spinner = button.find('.spinner-border')
            const buttonText = button.find('#text')

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from automatically processing the data
                contentType: false, // Prevent jQuery from automatically setting the content type
                beforeSend: function() {
                    buttonText.hide()
                    spinner.show()
                    button.attr('disabled', true)
                },
                success: function(response) {
                    spinner.hide()
                    buttonText.show()

                    console.log(response)

                    setTimeout(function() {
                        displayMessage(response.message, "success")

                        location.href = response.location
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
