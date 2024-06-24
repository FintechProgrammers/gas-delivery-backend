<script>
    $(document).ready(function() {
        $('#modalBody').on('submit', 'form', function(event) {
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
                    // Handle success response
                    loadTable()

                    $('#primaryModal').modal('hide')

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
                        displayMessage(xhr.responseJSON.message,"error")
                    }
                }
            });
        });
    });
</script>
