<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Role Permissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <button class="btn btn-primary float-end mb-3" data-bs-toggle="modal"
                        data-bs-target="#createRolePermissionModal">
                        Add New RolePermission
                    </button>

                    <table id="rolePermissionTbl" class="display nowrap">
                        <thead>
                            <tr>
                                <th>Name </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('role-permission._create')
    @push('scripts')
        <script>
            $(document).ready(function() {
                let rolePermissionTbl = new DataTable('#rolePermissionTbl', {
                    serverSide: false,
                    processing: false,
                    ajax: {
                        url: "{{ route('role-permission.data') }}",
                        dataSrc: ""
                    },
                    columns: [{
                            data: 'name',
                            name: 'name'
                        }
                    ]
                });

                $('#btnCreateRolePermission').on('click', function(e) {
                    e.preventDefault();

                    var formData = new FormData();

                    formData.append('name', $('#name').val());
                    $('input[name="permission[]"]:checked').each(function() {
                        formData.append('permission[]', $(this).val());
                    });

                    $.ajax({
                        url: "{{ route('role-permission.store') }}",
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
                                $('#createRolePermissionModal input[type=checkbox]').prop('checked', false);
                                $('#createRolePermissionModal').modal('hide');
                                rolePermissionTbl.ajax.reload(); // reload data table
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
                                    rolePermissionTbl.ajax.reload(); // Reload DataTable
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

            });
        </script>
    @endpush
</x-app-layout>
