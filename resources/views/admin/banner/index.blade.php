@extends('layouts.app')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Banner Management</p>
        </div>
        <div class="btn-list mt-md-0 mt-2">
            <button type="button" class="btn btn-primary btn-wave trigerModal" data-url="{{ route('admin.banner.create') }}"
                data-bs-toggle="modal" data-bs-target="#primaryModal">
                <i class="las la-user-plus me-2 align-middle d-inline-block"></i>Create Banner
            </button>
        </div>
    </div>
    <div id="banner-content">

    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/libs/glightbox/js/glightbox.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            function initializeLightbox() {
                // Initialize GLightbox
                var lightboxVideo = GLightbox({
                    selector: '.glightbox'
                });

                lightboxVideo.on('slide_changed', ({ prev, current }) => {
                    console.log('Prev slide', prev);
                    console.log('Current slide', current);

                    const { slideIndex, slideNode, slideConfig, player } = current;
                });
            }

            // Initial call to initialize GLightbox
            initializeLightbox();
        });
    </script>
    @include('admin.banner.scritps._load-table')
    @include('admin.banner.scritps._submit-form')
    @include('admin.banner.scritps._actions')
@endpush
