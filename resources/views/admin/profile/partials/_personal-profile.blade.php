<div class="p-sm-3 p-0">
    <div class="row">
        <div class="col-lg-3">
            @include('admin.profile.partials._profile-photo')
        </div>
        <div class="col-lg-9">

            <h6 class="fw-semibold mb-3">
                Profile :
            </h6>
            <form method="POST">
                @csrf
                <div class="row gy-4 mb-3">
                    <div class="col-xl-12">
                        <label for="first-name" class="form-label">Fullname</label>
                        <input type="text" class="form-control" id="first-name" name="name"
                            value="{{ $user->name }}" placeholder="Firt Name">
                    </div>
                    @if (Auth::check())
                        <div class="col-xl-12">
                            <label for="last-name" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="{{ $user->username }}"
                                placeholder="Last Name">
                        </div>
                    @endif
                    <div class="col-xl-12">
                        <label for="email-address" class="form-label">Email Address :</label>
                        <input type="text" class="form-control" id="email-address" value="{{ $user->email }}"
                            readonly placeholder="xyz@gmail.com">
                    </div>
                </div>
                {{-- <button class="btn btn-primary m-1">
                    Save Changes
                </button> --}}
            </form>

        </div>
    </div>
</div>
