@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Manage Users')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">Users</div>
        <div class="card-body">
            <table class="table table-bordered table-hover" id="usersTable">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Gender</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data loaded by jQuery AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editUserForm">
            <div class="modal-body">
                <input type="hidden" id="editUserId">
                <div class="mb-3">
                    <label for="editName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="editName" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="editEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="editEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="editPhone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="editPhone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="editAddress" class="form-label">Address</label>
                    <input type="text" class="form-control" id="editAddress" name="address">
                </div>
                <div class="mb-3">
                    <label for="editGender" class="form-label">Gender</label>
                    <select class="form-select" id="editGender" name="gender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div id="editUserErrors" class="text-danger small"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    // Fetch and display users
    function loadUsers() {
        $.get("{{ route('user.fetch') }}", function(users) {
            let rows = '';
            users.forEach(function(user) {
                rows += `<tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.phone}</td>
                    <td>${user.address ?? ''}</td>
                    <td>${user.gender ?? ''}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${user.id}" data-name="${user.name}" data-email="${user.email}" data-phone="${user.phone}" data-address="${user.address ?? ''}" data-gender="${user.gender ?? ''}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${user.id}">Delete</button>
                    </td>
                </tr>`;
            });
            $('#usersTable tbody').html(rows);
        });
    }
    loadUsers();

    // Open edit modal with pre-filled data
    $(document).on('click', '.edit-btn', function() {
        $('#editUserId').val($(this).data('id'));
        $('#editName').val($(this).data('name'));
        $('#editEmail').val($(this).data('email'));
        $('#editPhone').val($(this).data('phone'));
        $('#editAddress').val($(this).data('address'));
        $('#editGender').val($(this).data('gender'));
        $('#editUserErrors').html('');
        var modal = new bootstrap.Modal(document.getElementById('editUserModal'));
        modal.show();
    });

    // Save changes (edit user)
    $('#editUserForm').submit(function(e) {
        e.preventDefault();
        let id = $('#editUserId').val();
        let data = {
            name: $('#editName').val(),
            email: $('#editEmail').val(),
            phone: $('#editPhone').val(),
            address: $('#editAddress').val(),
            gender: $('#editGender').val(),
            _token: '{{ csrf_token() }}'
        };
        $.post(`{{ url('/admin/user/update') }}/${id}`, data)
        .done(function(res) {
            $('#editUserModal').modal('hide');
            loadUsers();
        })
        .fail(function(xhr) {
            if(xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorHtml = '';
                for(let key in errors) {
                    errorHtml += errors[key][0] + '<br>';
                }
                $('#editUserErrors').html(errorHtml);
            }
        });
    });

    // Delete user
    $(document).on('click', '.delete-btn', function() {
        if(confirm('Are you sure you want to delete this user?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `{{ url('/admin/user/delete') }}/${id}`,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(res) {
                    loadUsers();
                }
            });
        }
    });
});
</script>
@endpush
