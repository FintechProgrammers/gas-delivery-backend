<form method="POST" action="{{ route('admin.banner.store') }}">
    @csrf
    @include('admin.banner._form')
</form>
