<div class="mb-3">
    <label for="form-text" class="form-label fs-14 text-dark">Name</label>
    <input type="text" class="form-control" id="form-text" value="{{ isset($rank) ? $rank->name : '' }}"
        placeholder="Enter rank name" name="name">
</div>
<div class="mb-3">
    <div class="input-group ">
        <span class="input-group-text">$</span>
        <input min="0" step="any" name="benchmark" class="form-control"
            value="{{ isset($rank) ? $rank->creteria : '' }}" aria-label="Amount (to the nearest dollar)">
    </div>
    <small class="form-text text-muted">
        Please enter the benchmark value. This represents the amount of sales required
        to attain the rank. Ensure it is a non-negative number, and decimals are allowed if necessary.
    </small>
</div>
<div class="text-center" id="photoContent">
    @if (isset($rank))
        <img src="{{ $rank->file_url }}" class="img-fluid rounded" alt="...">
    @endif
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
