@extends('layouts.user.app')

@section('title', 'Ranks History')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Ranks
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="datatable-basic" class="table table-bordered table-striped table-hover text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Rank</th>
                                    <th>
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-xs me-2  avatar-rounded">
                                                <img src="https://cdn.deltadigital.pro/media/rank/1650862645-SLCZ_crop_52--p--83_63--p--95_1800--p--00_1800--p--00_0--p--00.jpeg"
                                                    alt="img">
                                            </span>Silver
                                        </div>
                                    </td>
                                    <td> May 6, 2024</td>
                                </tr>





                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
