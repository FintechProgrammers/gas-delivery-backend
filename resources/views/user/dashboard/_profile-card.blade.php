<div class="card custom-card">
    <div class="card-body">
        <div class="d-flex align-items-top justify-content-between mb-4">
            <div>
                <span class="d-block fs-15 fw-semibold">My Profile</span>
                <span class="d-block fs-12 text-muted">{{ Auth::user()->profile_completion_percentage }}% Completed
                    @if (Auth::user()->profile_completion_percentage < 100)
                        <a href="{{ route('profile.edit') }}" class="text-center text-primary"> - Click Here<i
                                class="bi bi-box-arrow-up-right fs-10 ms-2 align-middle"></i></a>
                    @endif
                </span>
            </div>
        </div>
        <div class="text-center mb-4">
            <div class="mb-3">
                <span class="avatar avatar-xxl avatar-rounded circle-progress p-1">
                    <img src="{{ auth()->user()->profile_picture }}" alt="">
                </span>
            </div>
            <div class="mb-2">
                <h5 class="fw-semibold mb-0">{{ ucfirst(auth()->user()->name) }}</h5>
                <span class="fs-13 text-muted">{{ auth()->user()->email }}</span>
            </div>
            @if (Auth::user()->is_ambassador)
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Recipient's username"
                        value="{{ auth()->user()->referral_code }}" readonly aria-label="Recipient's username"
                        aria-describedby="button-addon2">
                    <button class="btn btn-primary copy_btn"
                        copy_value="{{ route('register') }}?code={{ auth()->user()->referral_code }}" type="button"
                        id="button-addon2">Copy</button>
                </div>
            @endif
        </div>
        <div class="btn-list text-center">
            <a href="profile.edit" class="btn btn-primary-light btn-sm">
                Profile
            </a>
        </div>
    </div>
</div>
