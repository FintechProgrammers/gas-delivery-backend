@extends('layouts.app')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Role Management</p>
        </div>
        <div class="btn-list mt-md-0 mt-2">
            <a class="btn btn-primary btn-wave" href="{{ route('admin.roles.create') }}">
                <i class="las la-user-plus me-2 align-middle d-inline-block"></i>Create Role
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <table class="table table-bordered text-nowrap w-100">
                        {{-- id="scroll-vertical" --}}
                        <thead>
                            <tr>
                                <th>Role Name</th>
                                <th width="10">Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @forelse ($roles as $item)
                                <tr>
                                    <td class="text-capitalize">{{ $item->name }}</td>
                                    <td>
                                        @if (!in_array($item['name'], ['super admin', 'default']))
                                            <a href="{{ route('admin.roles.edit', $item->uuid) }}" class="btn btn-primary">
                                                Edit
                                            </a>
                                            <a href="#" class="btn btn-danger delete"
                                                data-url="{{ route('admin.roles.delete', $item->uuid) }}">
                                                Delete
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <span class="text-warning">no data available</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.delete').click(function(e) {
            e.preventDefault();

            const url = $(this).data('url');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        method: "DELETE",
                        success: function(result) {
                            if (result.success) {
                                displayMessage(
                                    result.message,
                                    "success"
                                );
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                displayMessage(
                                    "Error occurred while trying to delete Role",
                                    "error"
                                );
                            }

                        },
                        error: function(jqXHR, testStatus, error) {

                            console.log(jqXHR.responseText, testStatus, error);

                            if (jqXHR.status === 404) {
                                // Handle 404 error here
                                displayMessage(
                                    "Role not found.",
                                    "error"
                                );
                            } else {
                                // Handle other errors
                                displayMessage(
                                    "Error occurred while trying to delete role",
                                    "error"
                                );
                            }
                        },
                        timeout: 8000,
                    });
                }
            })
        })
    </script>
@endpush
