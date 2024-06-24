<form method="POST" action="{{ route('admin.commission.store') }}">
    <h5>Create Commission</h5>
    @csrf
    @include('admin.commission._form')
</form>
