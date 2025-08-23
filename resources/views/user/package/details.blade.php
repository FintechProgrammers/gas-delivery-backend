@extends('layouts.user.app')

@section('title', 'Package')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">{{ __('Package Details') }}</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-9">
                    <p class="fs-18 fw-semibold">{{ $package->name }}</p>
                    <div class="mb-4">
                        <p class="fs-15 fw-semibold mb-1">{{ __('Description') }} :</p>
                        <p class="text-muted mb-0">
                            {{ $package->description }}
                        </p>
                    </div>
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-xl-10">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th scope="row" class="fw-semibold">
                                                    {{ __('Duration') }}
                                                </th>
                                                <td>{{ convertDaysToUnit($package->duration, $package->duration_unit) . ' ' . $package->duration_unit }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-semibold">
                                                    {{ __('Price') }}
                                                </th>
                                                <td>
                                                    ${{ $package->price }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-semibold">
                                                    Auto Renewable
                                                </th>
                                                <td>
                                                    @if ($package->auto_renewal)
                                                        <i class="bx bx-badge-check text-success fa-2x"></i>
                                                    @else
                                                        <i class="las la-times-circle text-danger fa-2x"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-semibold">
                                                    Service Features
                                                </th>
                                                <td>
                                                    @if ($package->serviceProduct->isNotEmpty())
                                                        {{ $package->serviceProduct->pluck('product.name')->implode(', ') }}
                                                    @else
                                                        No products available.
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="text-center mb-3">
                        <a href="javascript:void(0);">
                            <img src="{{ $package->image }}" alt="" class="img-fluid rounded bg-light">
                        </a>
                    </div>
                    @if (in_array($package->id, Auth::user()->subscriptions()->pluck('service_id')->toArray()))
                        <div class="d-grid">
                            <a class="btn btn-primary disabled" tabindex="-1" role="button"
                                aria-disabled="true">Subscribed</a>
                        </div>
                    @else
                        <div class="d-grid">
                            <button class="btn btn-success mb-2" id="purchase"
                                data-url="{{ route('package.purchase', $package->uuid) }}">
                                <div class="spinner-border" style="display: none" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span id="text">{{ __('Purchase Now') }}</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#purchase').click(function(e) {
            e.preventDefault();

            const button = $(this)
            const spinner = button.find('.spinner-border')
            const buttonTest = button.find('#text')

            // Send AJAX request
            $.ajax({
                url: $(this).data('url'),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                beforeSend: function() {
                    buttonTest.hide()
                    spinner.show()
                    button.attr('disabled', true)
                },
                success: function(response) {

                    location.href = response.data.route

                },
                error: function(xhr, status, error) {
                    spinner.hide()
                    buttonTest.show()
                    button.attr('disabled', false)
                    // Handle error response
                    // var errors = xhr.responseJSON.errors;

                    displayMessage(xhr.responseJSON.message, "error")
                }
            });
        })
    </script>
@endpush
