<ul class="list-unstyled mb-0 notification-container">
    @forelse ($replies as $item)
        <li>
            <div class="card custom-card un-read">
                <div class="card-body p-3">
                    <a href="javascript:void(0);">
                        <div class="d-flex align-items-top mt-0 flex-wrap">
                            <div class="lh-1">
                                <span class="avatar avatar-md online me-3 avatar-rounded">
                                    @if (!empty($item->admin))
                                        <img alt="avatar" src="{{ $item->admin->profile_picture }}">
                                    @else
                                        <img alt="avatar" src="{{ $item->ticket->user->profile_picture }}">
                                    @endif
                                </span>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mt-sm-0 mt-2">
                                        <p class="mb-0 fs-14 fw-semibold">
                                            {{ !empty($item->admin) ? $item->admin->name : $item->ticket->user->name }}
                                        </p>
                                        <span
                                            class="mb-0 d-block text-muted fs-12">{{ $item->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="float-end badge bg-light text-muted">
                                            {{ $item->created_at->format('jS, M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                    </a>

                    <p class="mb-0 text-muted mb-3">
                        {!! $item->message !!}
                    </p>
                    @if (!empty($item->attachments))
                        <div class="row">
                            @foreach ($item->attachments as $file)
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <a href="{{ $file->file_url }}" target="_blank" class="glightbox"
                                        data-gallery="gallery1">
                                        <img src="{{ $file->file_url }}" alt="image" class="img-thumbnail">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </li>
    @empty
        <li>
            <div class="card">
                <p class="mb-0 text-warning">no replies available</p>
            </div>
        </li>
    @endforelse

</ul>
