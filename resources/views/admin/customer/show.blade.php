@extends('layouts.app')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Profile</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">User Profile</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-4 col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-sm-flex align-items-top p-4 border-bottom-0 main-profile-cover">
                        <div>
                            <span class="avatar avatar-xxl avatar-rounded online me-3">
                                <img src="{{ $user->profile_picture }}" alt="">
                            </span>
                        </div>
                        <div class="flex-fill main-profile-info">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fw-semibold mb-1 text-fixed-white">{{ Str::upper($user->name) }}</h6>
                            </div>
                            <p class="mb-1 text-muted text-fixed-white op-7">
                                @if ($user->is_ambassador)
                                    <span class="badge bg-blue">Ambassador</span>
                                @else
                                    <span class="badge bg-black">Customer</span>
                                @endif
                            </p>
                            <p class="fs-12 text-fixed-white mb-4 op-5">
                                <span class="me-3"><i class="fe fe-mail me-1 align-middle"></i>{{ $user->email }}</span>
                                <span class="me-3"><i
                                        class="fe fe-user me-1 align-middle"></i>{{ $user->username }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-8 col-xl-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-body p-0">
                            <div
                                class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                                <div>
                                    <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="myTab"
                                        role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="activity-tab" data-bs-toggle="tab"
                                                data-bs-target="#activity-tab-pane" type="button" role="tab"
                                                aria-controls="activity-tab-pane" aria-selected="true"><i
                                                    class="ri-gift-line me-1 align-middle d-inline-block"></i>Activity</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="posts-tab" data-bs-toggle="tab"
                                                data-bs-target="#posts-tab-pane" type="button" role="tab"
                                                aria-controls="posts-tab-pane" aria-selected="false"><i
                                                    class="ri-bill-line me-1 align-middle d-inline-block"></i>Posts</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="followers-tab" data-bs-toggle="tab"
                                                data-bs-target="#followers-tab-pane" type="button" role="tab"
                                                aria-controls="followers-tab-pane" aria-selected="false"><i
                                                    class="ri-money-dollar-box-line me-1 align-middle d-inline-block"></i>Friends</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="gallery-tab" data-bs-toggle="tab"
                                                data-bs-target="#gallery-tab-pane" type="button" role="tab"
                                                aria-controls="gallery-tab-pane" aria-selected="false"><i
                                                    class="ri-exchange-box-line me-1 align-middle d-inline-block"></i>Gallery</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="p-3">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane show active fade p-0 border-0" id="activity-tab-pane"
                                        role="tabpanel" aria-labelledby="activity-tab" tabindex="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
