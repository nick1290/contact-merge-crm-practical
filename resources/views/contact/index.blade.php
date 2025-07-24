<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Contacts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-3 px-3 mb-3">
                <div class="row">
                    <div class="col-3">
                        <div class="mb-3">
                            <input id="filterName" class="form-control" placeholder="Filter by name">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <input id="filterEmail" class="form-control" placeholder="Filter by email">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <select id="filterGender" class="form-select">
                                <option value="">Gender</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <button class="btn btn-info" id="applyFilter">Filter</button>
                        <button class="btn btn-danger" id="clearFilter">Clear</button>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <button class="btn btn-primary float-end mb-3" data-bs-toggle="modal"
                        data-bs-target="#createContactModal">
                        Add New Contact
                    </button>

                    <table id="contactTbl" class="display nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>DOB</th>
                                <th>Company Name</th>
                                <th>Status</th>
                                <th>Merged At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('contact._merge-contact')
    @include('contact._create')
    @include('contact._update')

    @push('scripts')
        <script>
            let contactTbl;

            function loadContact(filters = {}) {
                if (contactTbl) {
                    contactTbl.destroy();
                }
                contactTbl = new DataTable('#contactTbl', {
                    serverSide: false,
                    processing: false,
                    ajax: {
                        url: "{{ route('contact.data') }}",
                        dataSrc:"",
                        data: filters
                    },
                    columns: [{
                            data: 'image',
                            name: 'image',
                            render: function(data, type, row, meta) {
                                return `<img src="/storage/${row.image}">`;
                            }
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'gender',
                            name: 'gender'
                        },
                        {
                            data: 'dob',
                            name: 'dob'
                        },
                        {
                            data: 'company_name',
                            name: 'company_name'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'merged_at',
                            name: 'merged_at'
                        },
                        {
                            data: 'id',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return `<button class="btn btn-success px-3 py-1 rounded mr-2 btnEditContact" data-id="${data}"><span class="material-symbols-outlined">edit</span></button>
                                    <button class="btn btn-danger text-white px-3 py-1 rounded deleteContact" data-id="${data}"><span class="material-symbols-outlined">delete</span></button>
                                    @role('Super Admin')
                                        <button class="btn btn-warning text-white px-3 py-1 rounded btnMergeContact" data-id="${data}" data-name="${row.name}"><span class="material-symbols-outlined">merge</span></button>
                                    @endrole
                                `;
                            }
                        }
                    ]
                });
            }
            
            $(document).ready(function() {
                loadContact();

                $('#applyFilter').on('click', function() {
                    const filters = {
                        name: $('#filterName').val(),
                        email: $('#filterEmail').val(),
                        gender: $('#filterGender').val()
                    };

                    loadContact(filters);
                });

                $('#clearFilter').on('click', function() {
                    const filters = {
                        name: "",
                        email: "",
                        gender: ""
                    };

                    loadContact(filters);
                });

                $(document).on('click', '.btnEditContact', function() {
                    let contactId = $(this).data('id');
                    $.get("/contact/detail/"+contactId, function (res, textStatus, jqXHR) {
                        $('#editName').val(res.name);
                        $('#editEmail').val(res.email);
                        $('#editPhone').val(res.phone);
                        $("input[name=gender][value='"+res.gender+"']").prop("checked",true);
                        $('#editCompany_name').val(res.company_name);
                        $('#contact_id').val(contactId);
                        $('#updateContactModal').modal('show');
                    });
                });
                

                $(document).on('click', '.deleteContact', function() {
                    let contactId = $(this).data('id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to delete this data!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/contact/${contactId}`, // Adjust route if necessary
                                type: 'DELETE',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    );
                                    contactTbl.ajax.reload(); // Reload DataTable
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Error!',
                                        'Something went wrong.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });


                $('#btnSaveMergeContact').click(function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to merge this contact!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, Merge it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let data = {
                                master_id: $('#master_id').val(),
                                secondary_id: $('#secondary_id').val(),
                            };
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.post("{{ route('contact.merge') }}", data, function(res, textStatus,
                                jqXHR) {
                                Swal.fire(
                                    'Contact Merged!',
                                    res.message,
                                    'success'
                                );
                                $('#mergeContactModal').modal('hide');
                                contactTbl.ajax.reload(); // Reload DataTable
                            });
                        }
                    });
                });

            });
        </script>
    @endpush
</x-app-layout>
