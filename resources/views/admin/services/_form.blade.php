<div class="mb-3">
    <label for="form-text" class="form-label fs-14 text-dark">Name</label>
    <input type="text" class="form-control" id="form-text" value="{{ isset($service) ? $service->name : '' }}"
        placeholder="Enter package name" name="name">
</div>
<div class="mb-3">
    <label for="form-text" class="form-label fs-14 text-dark">Price ($)</label>
    <input type="number" step="any" class="form-control" id="form-text"
        value="{{ isset($service) ? $service->price : '' }}" placeholder="Enter package price" name="price">
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="mb-3">
            <label for="form-text" class="form-label fs-14 text-dark">Duration</label>
            <input type="number" class="form-control" id="form-text" placeholder="Enter duration"
                value="{{ isset($service) ? convertDaysToUnit($service->duration, $service->duration_unit) : '' }}"
                name="duration">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-3">
            <label for="form-text" class="form-label fs-14 text-dark">Duration Unit</label>
            <select id="inputCountry" class="form-select" name="duration_unit">
                <option value="">--select--</option>
                @foreach (durationUnit() as $item)
                    <option value="{{ $item }}" @selected(isset($service) && $service->duration_unit == $item)>{{ Str::upper($item) }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="mb-3">
    <label for="form-text" class="form-label fs-14 text-dark">Products</label>
    <div class="row" data-bs-spy="scroll" data-bs-offset="0" data-bs-smooth-scroll="true" tabindex="0">
        @foreach ($products as $key => $item)
            <div class="col-xl-12">
                <div class="custom-toggle-switch d-flex align-items-center mb-4">
                    <input id="toggleswitch{{ $key }}" name="products[]" value="{{ $item->id }}"
                        @checked(isset($serviceProducts) && isset($service) && in_array($item->id, $serviceProducts)) type="checkbox">
                    <label for="toggleswitch{{ $key }}" class="label-primary"></label><span
                        class="ms-3">{{ ucfirst($item->name) }}</span>
                </div>
            </div>
        @endforeach
    </div>
    <small>{{ __('Select product the package is serviced for') }}</small>
</div>
<div class="mb-3">
    <label for="form-text" class="form-label fs-14 text-dark">Description</label>
    <textarea class="form-control" name="description">
        {{ isset($service) ? $service->description : '' }}
    </textarea>
</div>
<div class="text-center" id="photoContent">
    <img src="{{ isset($service) ? $service->image : asset('assets/images/default.jpg') }}" class="img-fluid rounded" width="150px" height="50px" alt="...">
</div>
<div class="mb-3">
    <label for="form-text" class="form-label fs-14 text-dark">Image</label>
    <input type="file" name="image" id="photo" class="form-control">
</div>
<div class="form-check form-check-lg form-switch mb-3">
    <input class="form-check-input" type="checkbox" role="switch" id="switch-lg" name="auto_renewal"
        @checked(isset($service) && $service->auto_renewal)>
    <label class="form-check-label" for="switch-lg">Auto Renewal</label>
</div>

<div class="form-check form-check-lg form-switch mb-3">
    <input class="form-check-input" type="checkbox" role="switch" id="switch-lg" name="is_published"
        @checked(isset($service) && $service->is_published)>
    <label class="form-check-label" for="switch-lg">Publish</label>
</div>

<button class="btn btn-primary" type="submit">
    <div class="spinner-border" style="display: none" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    <span id="text">Submit</span>
</button>
