<h5>Create Rank</h5>
<form action="{{ route('admin.rank.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.rank._form')
</form>
