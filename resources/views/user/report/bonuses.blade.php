@extends('layouts.user.app')

@section('title','Bonus History')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Wallet Earning History
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table id="file-export" class="table table-bordered table-striped text-nowrap w-100">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Associated User</th>
                            <th>Category</th>
                            <th>
                                Date
                            </th>
                            <th> Level</th>
                            <th> Status</th>
                            <th> Amount</th>
                            <th> Report</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <div class="d-flex align-items-center">
                                            <span class="avatar avatar-xs me-2 online avatar-rounded">
                                                <img src="../assets/images/faces/3.jpg" alt="img">
                                            </span>Mayor Kelly
                                </div>
                            </td>

                            <td><span class="badge bg-primary-transparent">Team Bonus</span></td>

                            <td>May 8, 2024</td>
                            <td><a href="javascript:void(0);" class="text-success">1<i
                                            class="ri-arrow-up-fill ms-1"></i></a></td>
                            <td><span class="badge bg-success">Credit</span></td>
                            <td><a href="javascript:void(0);" class="text-success"> $37.50<i
                                            class="ri-add-fill ms-1"></i></a></td>

                            <td><a href="personal-sales-details.php" class="btn btn-sm btn-dark btn-wave">
                                    View details <i
                                            class="ri-arrow-right-line align-middle me-2 d-inline-block"></i>
                                </a></td>

                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                <div class="d-flex align-items-center">
                                            <span class="avatar avatar-xs me-2 online avatar-rounded">
                                                <img src="../assets/images/faces/3.jpg" alt="img">
                                            </span>Sane Roddy
                                </div>
                            </td>

                            <td><span class="badge bg-danger-transparent">Welcome bonus</span></td>

                            <td>May 8, 2024</td>
                            <td><a href="javascript:void(0);" class="text-success">1<i
                                            class="ri-arrow-up-fill ms-1"></i></a></td>
                            <td><span class="badge bg-success">Credit</span></td>
                            <td><a href="javascript:void(0);" class="text-success"> $450.00<i
                                            class="ri-add-fill ms-1"></i></a></td>

                            <td><a href="personal-sales-details.php" class="btn btn-sm btn-dark btn-wave">
                                    View details <i
                                            class="ri-arrow-right-line align-middle me-2 d-inline-block"></i>
                                </a></td>

                        </tr>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
