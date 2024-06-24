<form method="POST" action="{{ route('admin.admins.update',$admin->uuid) }}">
    @csrf
    @include('admin.admins._form')
</form>
