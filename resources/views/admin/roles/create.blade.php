@extends('layouts.app')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Create Role</p>
        </div>
    </div>
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        @include('admin.roles._form')
    </form>
@endsection
@push('scripts')
@endpush
