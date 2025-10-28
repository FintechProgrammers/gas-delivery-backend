<div id="create-users">
    <h5>Create User</h5>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="mb-3">
            <label for="form-text1" class="form-label fs-14 text-dark">Fullname</label>
            <div class="input-group">
                <div class="input-group-text"><i class="ri-user-line"></i></div>
                <input type="text" class="form-control" name="fullname" id="form-text1" placeholder="Enter fullname">
            </div>
        </div>
        <div class="mb-3">
            <label for="form-text1" class="form-label fs-14 text-dark">Username</label>
            <div class="input-group">
                <div class="input-group-text">@</div>
                <input type="text" class="form-control" name="username" id="form-text1" placeholder="Enter username">
            </div>
        </div>
        <div class="mb-3">
            <label for="form-text1" class="form-label fs-14 text-dark">Email</label>
            <div class="input-group">
                <div class="input-group-text"><i class="ri-mail-line"></i></div>
                <input type="text" class="form-control" id="form-text1" name="email" placeholder="Enter email">
            </div>
        </div>

        <button class="btn btn-primary" type="submit">
            <div class="spinner-border" style="display: none" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span id="text">Submit</span>
        </button>
    </form>
</div>
