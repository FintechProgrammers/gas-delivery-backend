<script>
    $(document).ready(function() {
        loadTable()
    })

    function loadTable() {

        const table = $('#table-body')

        $.ajax({
            url: '/tickets/filter',
            type: 'GET',
            beforeSend: function() {
                table.html(`<tr>
                    <td class="text-center" colspan="5">
                        <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                        </td>
                    </tr>`)
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
