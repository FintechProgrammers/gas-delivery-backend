@csrf
<div class="mb-3">
    <label for="form-text1" class="form-label fs-14 text-dark">Fullname</label>
    <input type="text" class="form-control" name="fullname" id="form-text1" placeholder="Enter fullname" value="{{ isset($admin) ? $admin->name : '' }}">
</div>
<div class="mb-3">
    <label for="form-text1" class="form-label fs-14 text-dark">Email</label>
    <input type="text" class="form-control" id="form-text1" name="email" placeholder="Enter email" value="{{ isset($admin) ? $admin->email : '' }}" {{ isset($admin) ? 'readonly' : '' }}>
</div>

<div class="mb-3">
    <label for="form-text1" class="form-label fs-14 text-dark">Role</label>
    <select name="roles[]" class="form-control " >
        <option value="">--select--roles--</option>
        @foreach ($roles as $item)
            <option value="{{ $item->name }}"
                {{ isset($admin) ? (in_array($item['name'], json_decode($admin->getRoleNames())) ? 'selected' : null) : '' }}>
                {{ $item->name }}</option>
        @endforeach
    </select>
</div>

<button class="btn btn-primary" type="submit">
    <div class="spinner-border" style="display: none" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    <span id="text">Submit</span>
</button>
