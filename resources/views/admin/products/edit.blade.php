<h5>Update Product</h5>
<form action="{{ route('admin.product.update', $product->uuid) }}" method="POST">
    @csrf
    @method('PATCH')
    @include('admin.products._form')
</form>
