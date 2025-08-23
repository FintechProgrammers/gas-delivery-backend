<div class="p-sm-3 p-0">
    <div class="row">
        <div class="col-lg-3">
            @include('profile.partials._profile-photo')
        </div>
        <div class="col-lg-9">

            <h6 class="fw-semibold mb-3">
                Profile :
            </h6>
            <form method="POST" action="{{ route('profile.update') }}" id="update-profile">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="first-name" class="form-label">Fullname</label>
                    <input type="text" class="form-control" id="first-name" name="name"
                        value="{{ $user->name }}" placeholder="Name" readonly>
                </div>
                <div class="mb-3">
                    <label for="last-name" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" value="{{ $user->username }}"
                        placeholder="Username" readonly>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="email-address" class="form-label">Email Address :</label>
                        <input type="text" class="form-control" name="email" id="email-address" value="{{ $user->email }}"
                            readonly placeholder="Email">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Phone Number :</label>
                        <input type="text" class="form-control" value="{{ $user->phone_number }}"
                            placeholder="Enter Phone number">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 mb-3">
                        <label for="">Country</label>
                        <x-country-select value="{{ $user->userProfile->country_code }}"/>
                    </div>
                    <div class="col-xl-4 mb-3">
                        <label for="">State</label>
                        <input type="text" name="state" class="form-control" placeholder="State"
                            value="{{ $user->userProfile->state }}" />
                    </div>
                    <div class="col-xl-4 mb-3">
                        <label for="">City</label>
                        <input type="text" name="city" class="form-control" placeholder="City"
                            value="{{ $user->userProfile->city }}" />
                    </div>
                </div>
                <div class="col-lg-12 mb-3">
                    <label class="form-label">Address:</label>
                    <textarea class="form-control" name="address">{{ $user->userProfile->address }}</textarea>
                </div>

                <button class="btn btn-primary" type="submit">
                    <div class="spinner-border" style="display: none" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <span id="text">Submit</span>
                </button>

            </form>

        </div>
    </div>
</div>
