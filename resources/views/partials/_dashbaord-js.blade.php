<!-- Defaultmenu JS -->
<script src="{{ asset('assets/js/defaultmenu.min.js') }}"></script>

<!-- Node Waves JS-->
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

<!-- Sticky JS -->
<script src="{{ asset('assets/js/sticky.js') }}"></script>

<!-- Simplebar JS -->
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>

<script src="{{ asset('assets/js/simplebar.js') }}"></script>

<script src="{{ asset('assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>
    $('.country-select').select2({
        placeholder: "Select",
        dir: "ltr"
    });

    $('input[name="datepicker"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="datepicker"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate
            .format('YYYY-MM-DD'));
    });

    $('input[name="datepicker"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    const modalBody = $('#modalBody')

    $('body').on('click', '.trigerModal', function(e) {
        e.preventDefault();

        const url = $(this).data('url');

        $.ajax({
            url: url,
            method: "GET",
            beforeSend: function() {
                modalBody.html(`<div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>`)
            },
            success: function(result) {
                modalBody.empty().html(result);
            },
            error: function(jqXHR, testStatus, error) {
                console.log(jqXHR.responseText, testStatus, error);
                displayMessage("An error occurred", "error")
            },
            timeout: 8000,
        });

    })
</script>
