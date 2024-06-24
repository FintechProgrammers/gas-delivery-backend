<h6 class="fw-semibold mb-3 text-center">
    Photo :
</h6>
<form method="POST" action="{{ route('admin.profile.update.image') }}" enctype="multipart/form-data" id="profileImageForm"
    class="d-flex flex-column align-items-center">
    @csrf
    <div class="d-flex flex-column align-items-center">
        <div class="mb-3">
            <span class="avatar avatar-xxl avatar-rounded">
                <img src="{{ $user->profile_picture }}" alt="Profile Picture" id="profile-img">
            </span>
        </div>
        <div class="mb-3">
            <input type="file" name="image" class="form-control" id="profile-change">
            @error('image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-xl-12 d-grid mt-2">
            <button class="btn btn-primary" type="submit">
                <div class="spinner-border" style="display: none" role="status">
                    <span class="sr-only">Uploading..</span>
                </div>
                <span id="text">{{ __('Upload') }}</span>
            </button>
        </div>
    </div>
</form>
