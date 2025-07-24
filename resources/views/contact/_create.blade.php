<div class="modal fade" id="createContactModal" tabindex="-1" aria-labelledby="createContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createContactModalLabel">Create Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="phone" class="form-control" id="phone">
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
                            <input type="text" class="form-control" id="company_name">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="landmark" class="form-label">Landmark</label>
                            <input type="text" class="form-control" id="landmark">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="number" class="form-control" id="pincode">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnCreateContact">Save</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#btnCreateContact').on('click', function(e) {
                e.preventDefault();

                var formData = new FormData();

                formData.append('name', $('#name').val());
                formData.append('email', $('#email').val());
                formData.append('phone', $('#phone').val());
                formData.append('gender', $('input[name="gender"]:checked').val());
                formData.append('company_name', $('#company_name').val());
                formData.append('city', $('#city').val());
                formData.append('landmark', $('#landmark').val());
                formData.append('pincode', $('#pincode').val());
                formData.append('state', $('#state').val());
                formData.append('country', $('#country').val());
                formData.append('textarea', $('#address').val());

                var image = $('#image')[0].files[0];
                if (image) {
                    formData.append('image', image);
                }

                var doc = $('#doc')[0].files[0];
                if (doc) {
                    formData.append('doc', doc);
                }

                $.ajax({
                    url: "{{ route('contact.store') }}",
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
                            $('#createContactModal input, #createContactModal textarea').val(
                                '');
                            $('#createContactModal input[type=radio]').prop('checked', false);
                            $('#createContactModal input[type=file]').val('');
                            $('#createContactModal').modal('hide');
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
