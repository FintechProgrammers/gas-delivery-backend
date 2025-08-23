@extends('layouts.user.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/glightbox/css/glightbox.min.css') }}">
@endpush

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Ticket Details</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            @include('user.support._ticket_side')
        </div>
        <div class="col-lg-9">
            <div class="card" style="height: 400px">
                <div class="card-header">
                    <h5 class="mb-0">Ticket Replies</h5>
                </div>
                <div class="card-body scrollspy-example" data-bs-spy="scroll" data-bs-root-margin="0px 0px -40%"
                    data-bs-smooth-scroll="true" tabindex="0">
                    <div id="repliesBody"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('tickets.reply', $ticket->uuid) }}" method="POST"
                        enctype="multipart/form-data" id="replyForm">
                        @csrf
                        <h6>Write a Reply</h6>
                        <div class="mb-3">
                            <textarea class="form-control" name="message"></textarea>
                        </div>
                        <div class="mb-3">
                            <input type="file" class="form-control" name="attachments">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">
                                <div class="spinner-border" style="display: none" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span id="text">Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/libs/glightbox/js/glightbox.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            loadMessage()

            var lightboxVideo = GLightbox({
                selector: '.glightbox'
            });
            lightboxVideo.on('slide_changed', ({
                prev,
                current
            }) => {
                console.log('Prev slide', prev);
                console.log('Current slide', current);

                const {
                    slideIndex,
                    slideNode,
                    slideConfig,
                    player
                } = current;
            });

        })

        $('#replyForm').submit(function(e) {

            e.preventDefault(); // Prevent default form submission

            // Remove any existing error messages
            $('.error-message').remove();

            var formData = new FormData(this);

            const button = $(this).find('button')

            const spinner = button.find('.spinner-border')
            const buttonTest = button.find('#text')

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    buttonTest.hide()
                    spinner.show()
                    button.attr('disabled', true)
                },
                success: function(response) {
                    // Handle success response
                    loadMessage()

                    spinner.hide()
                    buttonTest.show()

                    $('#replyForm')[0].reset()

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

        })

        function loadMessage() {
            const table = $('#repliesBody')

            $.ajax({
                url: '/tickets/replies/{{ $ticket->uuid }}',
                type: 'GET',
                beforeSend: function() {
                    table.html(`
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                       `)
                },
                success: function(response) {
                    table.empty().html(response);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseJSON)
                }
            });
        }
    </script>
@endpush
