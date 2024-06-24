<script>
    $(document).ready(function() {
        $('#modalBody').on('click', '#continue', function(event) {
            event.preventDefault(); // Prevent default form submission

            const button = $(this)

            const spinner = button.find('.spinner-border')
            const buttonTest = button.find('#text')

            // Send AJAX request
            $.ajax({
                url: "{{ route('ambassedor.payment.process') }}",
                type: 'POST',
                data: {_token: '{{ csrf_token() }}'},
                beforeSend: function() {
                    buttonTest.hide()
                    spinner.show()
                    button.attr('disabled', true)
                },
                success: function(response) {
                    location.href = response.data.route
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
