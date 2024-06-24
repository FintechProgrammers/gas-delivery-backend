<script>
    $(document).ready(function() {
        loadTable()
    })

    function loadTable() {

        const table = $('#banner-content')

        $.ajax({
            url: '/admin/banner/filter',
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
