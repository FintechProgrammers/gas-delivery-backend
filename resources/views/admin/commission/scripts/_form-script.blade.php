<script>
    const canvasBody = $('#canvasBody')

    $('body').on('click', '.trigerCanvas', function(e) {
        e.preventDefault();

        const url = $(this).data('url');

        $.ajax({
            url: url,
            method: "GET",
            beforeSend: function() {
                canvasBody.html(`<div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>`)
            },
            success: function(result) {
                canvasBody.empty().html(result);
            },
            error: function(jqXHR, testStatus, error) {
                console.log(jqXHR.responseText, testStatus, error);
                displayMessage("An error occurred", "error")
            },
            timeout: 8000,
        });

    })

    $('#canvasBody').on('submit', 'form', function(event) {
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

                closeCanvas()

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

    // Handle the click event on the direct-enable radio button
    $('body').on('change', '#canvasBody form #commissionType', function(e) {
        e.preventDefault();

        const value = $(this).val();

        if(value === '') {
            $('#canvasBody form button[type="submit"]').prop('disabled', true);
            return;
        }

        if (value == 'direct') {
            $('#confBlock').hide();
        } else {
            $('#confBlock').show();
        }

        $('#canvasBody form button[type="submit"]').prop('disabled', false);
    });


    // Function to check if any radio button is checked and enable/disable the submit button
    function closeCanvas() {
        var offcanvasElement = document.getElementById('offcanvasRight');
        var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
        if (!offcanvasInstance) {
            offcanvasInstance = new bootstrap.Offcanvas(offcanvasElement);
        }
        offcanvasInstance.hide();
    }
</script>
