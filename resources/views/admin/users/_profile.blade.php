<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">Personal Information</h4>
            </div><!--end col-->
        </div> <!--end row-->
    </div><!--end card-header-->
    <div class="card-body pt-0">
        <form action="" method="POST">
            @csrf
            <div class="form-group mb-3 row">
                <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">
                    First Name</label>
                <div class="col-lg-9 col-xl-8">
                    <input class="form-control" type="text" name="first_name" value="{{ $user->first_name }}">
                </div>
            </div>
            <div class="form-group mb-3 row">
                <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">
                    Last Name</label>
                <div class="col-lg-9 col-xl-8">
                    <input class="form-control" type="text"name="last_name" value="{{ $user->last_name }}">
                </div>
            </div>
            <div class="form-group mb-3 row">
                <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Company
                    Name</label>
                <div class="col-lg-9 col-xl-8">
                    <input class="form-control" type="text" name="company_name" value="{{ $user->business_name }}">
                </div>
            </div>

            <div class="form-group mb-3 row">
                <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Contact
                    Phone</label>
                <div class="col-lg-9 col-xl-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="las la-phone"></i></span>
                        <input type="text" class="form-control" value="{{ $user->phone_number }}" readonly
                            placeholder="Phone" name="phone_number" aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
            <div class="form-group mb-3 row">
                <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Email
                    Address</label>
                <div class="col-lg-9 col-xl-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="las la-at"></i></span>
                        <input type="text" class="form-control" value="{{ $user->email }}" name="email" readonly
                            placeholder="Email" aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
            {{-- <div class="form-group row">
                <div class="col-lg-9 col-xl-8 offset-lg-3">
                    <button type="submit" class="btn btn-primary">
                        <div class="spinner-border" style="display: none" role="status">
                            <span class="sr-only"></span>
                        </div>
                        <span id="text">Submit</span>
                    </button>
                </div>
            </div> --}}
        </form>
    </div><!--end card-body-->
</div>
