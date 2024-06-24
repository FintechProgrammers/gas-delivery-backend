
<h5>Create Package</h5>
<form action="{{ route('admin.package.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.services._form')
</form>
