@extends('layouts.user.app')

@section('title','Profile')

@section('content')
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <p class="fw-semibold fs-18 mb-0">Profile</p>
    </div>
</div>
<div class="row mb-5">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header d-sm-flex d-block">
                <ul class="nav nav-tabs nav-tabs-header mb-0 d-sm-flex d-block" role="tablist">
                    <li class="nav-item m-1">
                        <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page"
                            href="#personal-info" aria-selected="true">Personal Information</a>
                    </li>
                    <li class="nav-item m-1">
                        <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#security"
                            aria-selected="true">Security</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="personal-info" role="tabpanel">
                        @include('profile.partials._personal-profile')
                    </div>
                    <div class="tab-pane p-0" id="security" role="tabpanel">
                        @include('profile.partials._security')
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@push('scripts')
    @include('profile.scripts._update-profile-image')
    @include('profile.scripts._change-password')
    @include('profile.scripts._update-profile')
@endpush
