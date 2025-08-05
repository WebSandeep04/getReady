@extends('admin.layouts.app')

@section('title', 'Manage Bottom Types')
@section('page_title', 'Manage Bottom Types')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">Bottom Types</div>
        <div class="card-body">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBottomTypeModal">Add Bottom Type</button>
            <table class="table table-bordered table-hover" id="bottomTypesTable">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data loaded by jQuery AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Bottom Type Modal -->
    <div class="modal fade" id="addBottomTypeModal" tabindex="-1" aria-labelledby="addBottomTypeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addBottomTypeModalLabel">Add Bottom Type</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="addBottomTypeForm">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addBottomTypeName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="addBottomTypeName" name="name" required>
                    <div class="invalid-feedback" id="addBottomTypeError"></div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Bottom Type Modal -->
    <div class="modal fade" id="editBottomTypeModal" tabindex="-1" aria-labelledby="editBottomTypeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editBottomTypeModalLabel">Edit Bottom Type</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editBottomTypeForm">
            <div class="modal-body">
                <input type="hidden" id="editBottomTypeId">
                <div class="mb-3">
                    <label for="editBottomTypeName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="editBottomTypeName" name="name" required>
                    <div class="invalid-feedback" id="editBottomTypeError"></div>
                </div>
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
<script>
$(function() {
    // Fetch and display bottom types
    function fetchBottomTypes() {
        $.get('/admin/bottom-types/json', function(bottomTypes) {
            let rows = '';
            bottomTypes.forEach(function(bottomType) {
                rows += `<tr>
                    <td>${bottomType.id}</td>
                    <td>${bottomType.name}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${bottomType.id}" data-name="${bottomType.name}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${bottomType.id}">Delete</button>
                    </td>
                </tr>`;
            });
            $('#bottomTypesTable tbody').html(rows);
        });
    }
    fetchBottomTypes();

    // Add Bottom Type
    $('#addBottomTypeForm').on('submit', function(e) {
        e.preventDefault();
        var name = $('#addBottomTypeName').val();
        $.ajax({
            url: '{{ route('bottom_types.store') }}',
            method: 'POST',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#addBottomTypeModal').modal('hide');
                    $('#addBottomTypeForm')[0].reset();
                    $('#addBottomTypeName').removeClass('is-invalid');
                    $('#addBottomTypeError').hide();
                    fetchBottomTypes();
                }
            },
            error: function(xhr) {
                $('#addBottomTypeError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#addBottomTypeName').addClass('is-invalid');
            }
        });
    });
    $('#addBottomTypeModal').on('hidden.bs.modal', function() {
        $('#addBottomTypeForm')[0].reset();
        $('#addBottomTypeName').removeClass('is-invalid');
        $('#addBottomTypeError').hide();
    });

    // Open edit modal with pre-filled data
    $(document).on('click', '.edit-btn', function() {
        $('#editBottomTypeId').val($(this).data('id'));
        $('#editBottomTypeName').val($(this).data('name'));
        $('#editBottomTypeError').hide();
        var modal = new bootstrap.Modal(document.getElementById('editBottomTypeModal'));
        modal.show();
    });

    // Save changes (edit bottom type)
    $('#editBottomTypeForm').submit(function(e) {
        e.preventDefault();
        let id = $('#editBottomTypeId').val();
        let name = $('#editBottomTypeName').val();
        $.ajax({
            url: `/admin/bottom-types/${id}`,
            method: 'PUT',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#editBottomTypeModal').modal('hide');
                    fetchBottomTypes();
                }
            },
            error: function(xhr) {
                $('#editBottomTypeError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#editBottomTypeName').addClass('is-invalid');
            }
        });
    });
    $('#editBottomTypeModal').on('hidden.bs.modal', function() {
        $('#editBottomTypeForm')[0].reset();
        $('#editBottomTypeName').removeClass('is-invalid');
        $('#editBottomTypeError').hide();
    });

    // Delete bottom type
    $(document).on('click', '.delete-btn', function() {
        if(confirm('Are you sure you want to delete this bottom type?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/bottom-types/${id}`,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(res) {
                    fetchBottomTypes();
                }
            });
        }
    });
});
</script>
@endpush 