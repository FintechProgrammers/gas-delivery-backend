<div class="card">
    <div class="card-body p-4">
        <div class="mb-3">
            <label for="example-text-input" class="form-label">Role Name</label>
            <input class="form-control" type="text" name="role_name" value="{{ isset($role) ? $role->name : '' }}">
            @error('role_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h4>Permissions</h4>
                <div>
                    <button id="select-all" class="btn btn-sm btn-light ft-sm border mr-2">Select all</button>
                    <button id="unselect-all" class="btn btn-sm btn-light ft-sm border">Unselect all</button>
                </div>
            </div>
            <hr />
            <div class="col-lg-12 mb-4">
                <ul class="list-unstyled info-details-div-4 ml-5">
                    @forelse ($permissions as $permission)
                        <li>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="permissions[]"
                                    {{ old('permissions[]') == $permission->id ? 'checked' : '' }}
                                    value="{{ $permission->name }}" id="{{ Str::slug($permission->id) }}"
                                    {{ isset($role_permissions) && in_array($permission->id, $role_permissions) ? 'checked' : '' }}
                                    class="custom-control-input checkbox-item">
                                <label class="custom-control-label" for="{{ Str::slug($permission->id) }}">
                                    <strong class="text-capitalize"> {{ $permission->name }}</strong>
                                </label>
                            </div>
                        </li>
                    @empty
                        <div class="text-muted">No Data Available</div>
                    @endforelse
                </ul>

            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary w-lg">Submit</button>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // Get the select all checkbox and all other checkboxes
        const selectAllCheckbox = document.querySelector('#select-all');
        const selectUnselect = document.querySelector('#unselect-all');
        const checkboxes = document.querySelectorAll('.checkbox-item');

        // Add event listener to select all checkbox
        selectAllCheckbox.addEventListener('click', function(e) {
            e.preventDefault();
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        });

        selectUnselect.addEventListener('click', function(e) {
            e.preventDefault();

            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });

        })

        // Add event listener to all other checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // If any checkbox is unchecked, uncheck the select all checkbox
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    // If all other checkboxes are checked, check the select all checkbox
                    const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            });
        });

        $(document).ready(function() {
            // Event handler for group-select checkbox
            $('.group-select').change(function() {
                // Get the parent group element
                var $group = $(this).closest('h6');

                // Find all checkbox items within the group
                var $checkboxes = $group.next('.info-details-div-4').find('.checkbox-item');

                // Check or uncheck the checkbox items based on the group-select checkbox state
                $checkboxes.prop('checked', $(this).is(':checked'));
            });
        });
    </script>
@endpush
