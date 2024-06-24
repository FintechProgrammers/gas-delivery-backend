<form method="POST" action="{{ route('admin.admins.store') }}">
    @csrf
    @include('admin.admins._form')
</form>
