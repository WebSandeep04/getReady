@extends('admin.layouts.app')

@section('title', 'Manage Fabric Types')
@section('page_title', 'Manage Fabric Types')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">Fabric Types</div>
        <div class="card-body">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addFabricTypeModal">Add Fabric Type</button>
            <table class="table table-bordered table-hover" id="fabricTypesTable">
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

    <!-- Add Fabric Type Modal -->
    <div class="modal fade" id="addFabricTypeModal" tabindex="-1" aria-labelledby="addFabricTypeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addFabricTypeModalLabel">Add Fabric Type</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="addFabricTypeForm">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addFabricTypeName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="addFabricTypeName" name="name" required>
                    <div class="invalid-feedback" id="addFabricTypeError"></div>
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

    <!-- Edit Fabric Type Modal -->
    <div class="modal fade" id="editFabricTypeModal" tabindex="-1" aria-labelledby="editFabricTypeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editFabricTypeModalLabel">Edit Fabric Type</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editFabricTypeForm">
            <div class="modal-body">
                <input type="hidden" id="editFabricTypeId">
                <div class="mb-3">
                    <label for="editFabricTypeName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="editFabricTypeName" name="name" required>
                    <div class="invalid-feedback" id="editFabricTypeError"></div>
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
    // Fetch and display fabric types
    function fetchFabricTypes() {
        $.get('/admin/fabric-types/json', function(fabricTypes) {
            let rows = '';
            fabricTypes.forEach(function(fabricType) {
                rows += `<tr>
                    <td>${fabricType.id}</td>
                    <td>${fabricType.name}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${fabricType.id}" data-name="${fabricType.name}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${fabricType.id}">Delete</button>
                    </td>
                </tr>`;
            });
            $('#fabricTypesTable tbody').html(rows);
        });
    }
    fetchFabricTypes();

    // Add Fabric Type
    $('#addFabricTypeForm').on('submit', function(e) {
        e.preventDefault();
        var name = $('#addFabricTypeName').val();
        $.ajax({
            url: '{{ route('fabric_types.store') }}',
            method: 'POST',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#addFabricTypeModal').modal('hide');
                    $('#addFabricTypeForm')[0].reset();
                    $('#addFabricTypeName').removeClass('is-invalid');
                    $('#addFabricTypeError').hide();
                    fetchFabricTypes();
                }
            },
            error: function(xhr) {
                $('#addFabricTypeError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#addFabricTypeName').addClass('is-invalid');
            }
        });
    });
    $('#addFabricTypeModal').on('hidden.bs.modal', function() {
        $('#addFabricTypeForm')[0].reset();
        $('#addFabricTypeName').removeClass('is-invalid');
        $('#addFabricTypeError').hide();
    });

    // Open edit modal with pre-filled data
    $(document).on('click', '.edit-btn', function() {
        $('#editFabricTypeId').val($(this).data('id'));
        $('#editFabricTypeName').val($(this).data('name'));
        $('#editFabricTypeError').hide();
        var modal = new bootstrap.Modal(document.getElementById('editFabricTypeModal'));
        modal.show();
    });

    // Save changes (edit fabric type)
    $('#editFabricTypeForm').submit(function(e) {
        e.preventDefault();
        let id = $('#editFabricTypeId').val();
        let name = $('#editFabricTypeName').val();
        $.ajax({
            url: `/admin/fabric-types/${id}`,
            method: 'PUT',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#editFabricTypeModal').modal('hide');
                    fetchFabricTypes();
                }
            },
            error: function(xhr) {
                $('#editFabricTypeError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#editFabricTypeName').addClass('is-invalid');
            }
        });
    });
    $('#editFabricTypeModal').on('hidden.bs.modal', function() {
        $('#editFabricTypeForm')[0].reset();
        $('#editFabricTypeName').removeClass('is-invalid');
        $('#editFabricTypeError').hide();
    });

    // Delete fabric type
    $(document).on('click', '.delete-btn', function() {
        if(confirm('Are you sure you want to delete this fabric type?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/fabric-types/${id}`,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(res) {
                    fetchFabricTypes();
                }
            });
        }
    });
});
</script>
@endpush 