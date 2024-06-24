<div class="d-flex flex-column align-items-center h-100">
    <img src="{{ asset('assets/images/no-data.png') }}" width="400px" height="300px" alt="">
    @if (!empty($title))
        <h3 class="text-uppercase text-center"><b>{{ $title }}</b></h3>
    @endif
</div>
