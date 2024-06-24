<div class="mb-3">
    <label for="form-text" class="form-label fs-14 text-dark">{{ __('Name') }}</label>
    <input type="text" class="form-control" id="form-text" value="{{ isset($commission) ? $commission->name : '' }}"
        placeholder="Enter plan name" name="name">
</div>
<div class="mb-3">
    <label for="">{{ __('Commision Percentage') }}</label>
    <div class="input-group ">
        <span class="input-group-text">%</span>
        <input min="0" step="any" name="commission_percentage" class="form-control"
            value="{{ isset($commission) ? $commission->commission_percentage : '' }}"
            aria-label="Amount (to the nearest dollar)">
    </div>
</div>
<div class="mb-3">
    <label for="">Commission Type</label>
    <select name="commission_type" class="form-control" id="commissionType">
        <option value="">--select--</option>
        <option value="direct" @selected(isset($commission) && $commission->is_direct ?: false)>Direct</option>
        <option value="secondary" @selected(isset($commission) && !$commission->is_direct ?: false)>Secondary</option>
    </select>
</div>
<div id="confBlock" style="display:{{ isset($commission) && !$commission->is_direct ? 'block' : 'none' }}">
    <div class="mb-3">
        <label for="form-text" class="form-label fs-14 text-dark">{{ __('Level') }}</label>
        <input type="number" class="form-control" id="form-text" min="1"
            value="{{ isset($commission) ? $commission->level : '' }}" placeholder="Enter plan name" name="level">
    </div>
    <h6><b>Requirement Configuration</b></h6>
    <div class="mb-3">
        <label for="form-text" class="form-label fs-14 text-dark">{{ __('Required Direct BV') }}</label>
        <div class="input-group ">
            <span class="input-group-text">%</span>
            <input type="number" class="form-control" id="form-text" min="1"
                value="{{ isset($requirement) ? $requirement->direct_bv : '' }}" name="direct_bv">
        </div>
    </div>
    <div class="mb-3">
        <label for="form-text" class="form-label fs-14 text-dark">{{ __('Required Sponsored BV') }}</label>
        <div class="input-group ">
            <span class="input-group-text">%</span>
            <input type="number" class="form-control" id="form-text" min="1"
                value="{{ isset($requirement) ? $requirement->sponsored_bv : '' }}" name="sponsored_bv">
        </div>

    </div>
    <div class="mb-3">
        <label for="form-text" class="form-label fs-14 text-dark">{{ __('Required Sponsored Count') }}</label>
        <input type="number" class="form-control" id="form-text" min="1"
            value="{{ isset($requirement) ? $requirement->sponsored_count : '' }}" name="sponsored_count">
    </div>
</div>
<div class="d-grid gap-2 col-6 mx-auto">
    <button class="btn btn-primary btn-block" type="submit">
        <div class="spinner-border spinner-border-sm align-middle" style="display: none" aria-hidden="true">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span id="text">Submit</span>
    </button>
</div>
