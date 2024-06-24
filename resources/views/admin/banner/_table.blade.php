<div class="row">
    @forelse ($banners as $item)
        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
            <div class="product-card">
                <div class="card-body rounded">
                    <a href="#" class="product-image">
                        <img src="{{ $item->file_url }}" class="card-img mb-3" height="150px" alt="...">
                    </a>
                    <div class="product-icons">
                        <a href="javascript:void(0);" class="wishlist btn-action"
                            data-url="{{ route('admin.banner.delete', $item->uuid) }}"
                            data-action="you want to delete  banner"><i class="bx bx-trash"></i></a>
                        <a href="#" class="cart trigerModal" data-url="{{ route('admin.banner.edit', $item->uuid) }}"
                            data-bs-toggle="modal" data-bs-target="#primaryModal"><i class="bx bx-pencil"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-lg-12">
            <x-no-datacomponent title="no banner created" />
        </div>
    @endforelse
</div>
