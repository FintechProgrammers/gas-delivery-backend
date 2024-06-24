<h5>Update Rank</h5>
<form action="{{ route('admin.rank.update', $rank->uuid) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.rank._form')
</form>
