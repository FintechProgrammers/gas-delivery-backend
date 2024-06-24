<form method="POST" action="{{ route('admin.banner.update', $banner->uuid) }}">
    @csrf
    @method('PATCH')
    @include('admin.banner._form')
</form>
