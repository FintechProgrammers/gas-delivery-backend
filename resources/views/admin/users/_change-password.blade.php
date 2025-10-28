<div class="card">
    <div class="card-header">
        <h4 class="card-title">Change Password</h4>
    </div><!--end card-header-->
    <div class="card-body pt-0">
        <form method="POST">
            @csrf
            <div class="form-group mb-3 row">
                <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">New
                    Password</label>
                <div class="col-lg-9 col-xl-8">
                    <input class="form-control" type="password" name="password" placeholder="New Password">
                </div>
            </div>
            <div class="form-group mb-3 row">
                <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Confirm
                    Password</label>
                <div class="col-lg-9 col-xl-8">
                    <input class="form-control" type="password" name="password_confirmation" placeholder="Re-Password">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-9 col-xl-8 offset-lg-3">
                    <button type="submit" class="btn btn-primary">
                        <div class="spinner-border" style="display: none" role="status">
                            <span class="sr-only"></span>
                        </div>
                        <span id="text">Change Password</span>
                    </button>
                    <button type="button" class="btn btn-danger">Cancel</button>
                </div>
            </div>
        </form>
    </div><!--end card-body-->
</div><!--end card-->
