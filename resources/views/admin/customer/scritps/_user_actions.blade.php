<script>
    $(document).ready(function() {
        $(document).on('click', '#table-body a.btn-action', function(e) {
            console.log('hello')
            e.preventDefault();

            var actionUrl = $(this).data('url');
            var actionType = $(this).data('action');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + actionType + ' user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: actionType
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform AJAX request based on action type
                    $.ajax({
                        url: actionUrl,
                        type: 'POST', // Assuming you're using GET method
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Assuming success message is returned from the server
                            loadTable()
                            displayMessage(response.message, "success")
                            // Assuming you want to reload the table after action is performed
                            // You can customize this part based on your requirement
                            // window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            displayMessage(
                                'An error occurred while performing the action.',
                                "error")
                            // Handle error response
                            // You can display error messages or handle the error based on your requirement
                        }
                    });
                }
            });
        });
    });
</script>
