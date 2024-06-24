<form method="POST" action="{{ route('admin.commission.update', $commission->uuid) }}">
    <h5>Update Commission</h5>
    @csrf
    @method('PUT')
    @include('admin.commission._form')
</form>
