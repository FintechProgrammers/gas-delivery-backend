<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<!-- Popper JS -->

<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
{{-- <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/pages/index.init.js') }}"></script> --}}
<script src="{{ asset('assets/js/app.js') }}"></script>

@include('partials.scripts._copy-scripts')

<script>
    function displayMessage(message, type) {
        const Toast = swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 8000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
        });
        Toast.fire({
            icon: type,
            title: message,
        });
    }
</script>
