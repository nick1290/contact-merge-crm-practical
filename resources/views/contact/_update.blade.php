<div class="modal fade" id="updateContactModal" tabindex="-1" aria-labelledby="updateContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateContactModalLabel">Update Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="contact_id" id="contact_id">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="phone" class="form-control" id="editPhone">
                        </div>
                    </div>
                    <div class="col-6 pt-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1"
                                value="M" checked>
                            <label class="form-check-label" for="flexRadioDefault1">
                                Male
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault2"
                                value="F">
                            <label class="form-check-label" for="flexRadioDefault2">
                                Female
                            </label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="doc" class="form-label">Doc</label>
                            <input type="file" class="form-control" id="doc">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="dob" class="form-label">DOB</label>
                            <input type="date" class="form-control" id="dob">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="editCompany_name">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnUpdateContact">Update</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#btnUpdateContact').on('click', function(e) {
                e.preventDefault();

                var formData = new FormData();

                formData.append('name', $('#editName').val());
                formData.append('email', $('#editEmail').val());
                formData.append('phone', $('#editPhone').val());
                formData.append('gender', $('input[name="gender"]:checked').val());
                formData.append('company_name', $('#editCompany_name').val());
                formData.append('contact_id', $('#contact_id').val());

                var image = $('#image')[0].files[0];
                if (image) {
                    formData.append('image', image);
                }

                var doc = $('#doc')[0].files[0];
                if (doc) {
                    formData.append('doc', doc);
                }

                $.ajax({
                    url: "{{ route('contact.update') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            $('#updateContactModal').modal('hide');
                            contactTbl.ajax.reload(); // reload data table
                            // Optional: Reload contact list or table via AJAX
                        } else {
                            alert('Something went wrong!');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert(JSON.parse(xhr.responseText).message);
                    }
                });
            });
        });
    </script>
@endpush
