<div class="text-center" id="photoContent">
    <img src="{{ isset($banner) ? $banner->file_url : asset('assets/images/default.jpg') }}" class="img-fluid rounded" width="150px" height="50px" alt="...">
</div>
<div class="mb-3">
    <label for="form-text" class="form-label fs-14 text-dark">Image</label>
    <input type="file" name="image" id="photo" class="form-control">
</div>
<button class="btn btn-primary" type="submit">
    <div class="spinner-border" style="display: none" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    <span id="text">Submit</span>
</button>
