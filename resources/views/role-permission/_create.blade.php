<div class="modal fade" id="createRolePermissionModal" tabindex="-1" aria-labelledby="createRolePermissionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRolePermissionModalLabel">Create Role Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label>Role Name</label>
                            <input name="name" id="name" required class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            @foreach ($permissions as $item)
                                <div class="checkbox">
                                    <label> <input type="checkbox" value="{{ $item->name }}" name="permission[]">
                                        {{ $item->name }} </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnCreateRolePermission">Save</button>
                </div>
            </div>
        </div>
    </div>
