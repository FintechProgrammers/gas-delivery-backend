<h5>Update package</h5>
<form action="{{ route('admin.package.update', $service->uuid) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    @include('admin.services._form')
</form>
