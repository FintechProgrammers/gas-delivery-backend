<div class="row gap-3 justify-content-between">
    <div class="col-xl-7">
        <div class="card custom-card shadow-none mb-0 border">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile.update.password') }}" id="update-password">
                    @csrf
                    <div class="d-flex align-items-top justify-content-between">
                        <div>
                            <p class="fs-14 mb-1 fw-semibold">Reset Password</p>
                            <p class="fs-12 text-muted">Password should be min of <b class="text-success">8
                                    digits<sup>*</sup></b>,atleast <b class="text-success">One Capital
                                    letter<sup>*</sup></b> and <b class="text-success">One Special
                                    Character<sup>*</sup></b> included.</p>
                            <div class="mb-3">
                                <label for="current-password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current-password" name="current_password"
                                    placeholder="Current Password">
                                @error('current_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="new-password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new-password" placeholder="New Password"
                                    name="password">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="confirm-password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm-password"
                                    name="password_confirmation" placeholder="Confirm PAssword">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sm" type="submit">
                        <div class="spinner-border" style="display: none" role="status">
                            <span class="sr-only">updating..</span>
                        </div>
                        <span id="text">{{ __('Submit') }}</span>
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
