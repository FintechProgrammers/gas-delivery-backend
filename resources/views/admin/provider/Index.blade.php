@extends('layouts.app')

@section('title', 'Providers')

@push('styles')
@endpush

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Providers</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Is Default</th>
                            <th>Has Transaction</th>
                            <th>Has Bank Account</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                    </tbody>
                </table>
            </div>
            <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
        </div>
    </div>
@endsection
@push('scripts')
    @include('admin.provider.scripts._load-table')
    <script>
        $(document).ready(function() {
            $('body').on('change', '.feature-switch', function() {
                const $el = $(this);
                const url = $el.data('url');
                const feature = $el.data('feature');
                const isChecked = $el.is(':checked');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        feature: feature,
                        value: isChecked ? 1 : 0
                    },
                    success: function(response) {
                        // console.log('Feature updated:', response.message);

                        loadTable()
                        displayMessage(response.message, "success")
                    },
                    error: function(xhr) {

                        displayMessage(xhr.responseJSON?.message || 'Failed to update feature.',
                            "error")
                        $el.prop('checked', !isChecked); // revert switch
                    }
                });
            });
        });
    </script>
@endpush
