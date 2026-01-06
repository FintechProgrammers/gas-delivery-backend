<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">Order Timeline</h4>
            </div><!--end col-->
        </div> <!--end row-->
    </div><!--end card-header-->
    <div class="card-body">
        @if(isset($timeline) && count($timeline) > 0)
            <div class="timeline-vertical">
                @foreach($timeline as $index => $step)
                    <div class="timeline-item {{ $step['completed'] ? 'completed' : 'pending' }}">
                        <div class="timeline-marker">
                            <div class="timeline-icon {{ $step['completed'] ? 'bg-primary text-white' : 'bg-light text-muted' }} rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                @if($step['completed'])
                                    <i class="iconoir-check fs-18"></i>
                                @else
                                    <i class="iconoir-clock fs-18"></i>
                                @endif
                            </div>
                            @if($index < count($timeline) - 1)
                                <div class="timeline-line {{ $step['completed'] && ($timeline[$index + 1]['completed'] ?? false) ? 'bg-primary' : 'bg-light' }}" style="width: 2px; height: 100%; position: absolute; left: 19px; top: 40px;"></div>
                            @endif
                        </div>
                        <div class="timeline-content ms-3 pb-4">
                            <h6 class="mb-1 {{ $step['completed'] ? 'text-primary' : 'text-muted' }}">
                                {{ $step['label'] }}
                            </h6>
                            @if($step['description'])
                                <p class="mb-1 text-muted fs-13">{{ $step['description'] }}</p>
                            @endif
                            @if($step['timestamp'])
                                <p class="mb-0 text-muted fs-12">
                                    <i class="iconoir-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($step['timestamp'])->format('d M Y, h:i A') }}
                                </p>
                            @else
                                <p class="mb-0 text-muted fs-12">
                                    <i class="iconoir-clock me-1"></i>
                                    Pending
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">No timeline data available.</p>
        @endif
    </div><!--card-body-->
</div><!--end card-->

<style>
    .timeline-vertical {
        position: relative;
        padding-left: 0;
    }

    .timeline-item {
        position: relative;
        display: flex;
        padding-left: 0;
    }

    .timeline-marker {
        position: relative;
        flex-shrink: 0;
    }

    .timeline-content {
        flex-grow: 1;
    }
</style>
