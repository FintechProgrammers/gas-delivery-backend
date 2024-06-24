
<h5>Create Product</h5>
<form action="{{ route('admin.product.store') }}" method="POST">
    @csrf
    @include('admin.products._form')
</form>
