<div class="mb-3">
    <label for="form-text" class="form-label fs-14 text-dark">Name</label>
    <input type="text" class="form-control" id="form-text" value="{{ isset($product) ? $product->name : '' }}"
        placeholder="Enter service name" name="name">
</div>
<div class="mb-3">
    <label for="form-text" class="form-label fs-14 text-dark">Description</label>
    <textarea class="form-control" name="description">
        {{ isset($product) ? $product->description : '' }}
    </textarea>
</div>
<button class="btn btn-primary" type="submit">
    <div class="spinner-border" style="display: none" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    <span id="text">Submit</span>
</button>
