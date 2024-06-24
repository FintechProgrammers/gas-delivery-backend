@extends('layouts.user.app')

@section('title', 'Sales')

@section('content')
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Sales History
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-nowrap table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Service Name</th>
                            <th scope="col">Price</th>
                            <th scope="col" width="30%">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $item)
                            <tr>
                                <td>
                                    <span class="text-success fw-semibold">{{ optional($item->service)->name }}</span>
                                </td>
                                <td>
                                    ${{ $item->amount }}
                                </td>
                                <td>
                                    {{ $item->created_at->format('jS,M Y H:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center"><span class="text-warning">no data available</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
