@extends('admin.layouts.app')

@section('title', 'Manage Garment Conditions')
@section('page_title', 'Manage Garment Conditions')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">Garment Conditions</div>
        <div class="card-body">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addGarmentConditionModal">Add Garment Condition</button>
            <table class="table table-bordered table-hover" id="garmentConditionsTable">
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

    <!-- Add Garment Condition Modal -->
    <div class="modal fade" id="addGarmentConditionModal" tabindex="-1" aria-labelledby="addGarmentConditionModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addGarmentConditionModalLabel">Add Garment Condition</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="addGarmentConditionForm">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addGarmentConditionName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="addGarmentConditionName" name="name" required>
                    <div class="invalid-feedback" id="addGarmentConditionError"></div>
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

    <!-- Edit Garment Condition Modal -->
    <div class="modal fade" id="editGarmentConditionModal" tabindex="-1" aria-labelledby="editGarmentConditionModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editGarmentConditionModalLabel">Edit Garment Condition</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editGarmentConditionForm">
            <div class="modal-body">
                <input type="hidden" id="editGarmentConditionId">
                <div class="mb-3">
                    <label for="editGarmentConditionName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="editGarmentConditionName" name="name" required>
                    <div class="invalid-feedback" id="editGarmentConditionError"></div>
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
    // Fetch and display garment conditions
    function fetchGarmentConditions() {
        $.get('/admin/garment-conditions/json', function(garmentConditions) {
            let rows = '';
            garmentConditions.forEach(function(garmentCondition) {
                rows += `<tr>
                    <td>${garmentCondition.id}</td>
                    <td>${garmentCondition.name}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${garmentCondition.id}" data-name="${garmentCondition.name}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${garmentCondition.id}">Delete</button>
                    </td>
                </tr>`;
            });
            $('#garmentConditionsTable tbody').html(rows);
        });
    }
    fetchGarmentConditions();

    // Add Garment Condition
    $('#addGarmentConditionForm').on('submit', function(e) {
        e.preventDefault();
        var name = $('#addGarmentConditionName').val();
        $.ajax({
            url: '{{ route('garment_conditions.store') }}',
            method: 'POST',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#addGarmentConditionModal').modal('hide');
                    $('#addGarmentConditionForm')[0].reset();
                    $('#addGarmentConditionName').removeClass('is-invalid');
                    $('#addGarmentConditionError').hide();
                    fetchGarmentConditions();
                }
            },
            error: function(xhr) {
                $('#addGarmentConditionError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#addGarmentConditionName').addClass('is-invalid');
            }
        });
    });
    $('#addGarmentConditionModal').on('hidden.bs.modal', function() {
        $('#addGarmentConditionForm')[0].reset();
        $('#addGarmentConditionName').removeClass('is-invalid');
        $('#addGarmentConditionError').hide();
    });

    // Open edit modal with pre-filled data
    $(document).on('click', '.edit-btn', function() {
        $('#editGarmentConditionId').val($(this).data('id'));
        $('#editGarmentConditionName').val($(this).data('name'));
        $('#editGarmentConditionError').hide();
        var modal = new bootstrap.Modal(document.getElementById('editGarmentConditionModal'));
        modal.show();
    });

    // Save changes (edit garment condition)
    $('#editGarmentConditionForm').submit(function(e) {
        e.preventDefault();
        let id = $('#editGarmentConditionId').val();
        let name = $('#editGarmentConditionName').val();
        $.ajax({
            url: `/admin/garment-conditions/${id}`,
            method: 'PUT',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#editGarmentConditionModal').modal('hide');
                    fetchGarmentConditions();
                }
            },
            error: function(xhr) {
                $('#editGarmentConditionError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#editGarmentConditionName').addClass('is-invalid');
            }
        });
    });
    $('#editGarmentConditionModal').on('hidden.bs.modal', function() {
        $('#editGarmentConditionForm')[0].reset();
        $('#editGarmentConditionName').removeClass('is-invalid');
        $('#editGarmentConditionError').hide();
    });

    // Delete garment condition
    $(document).on('click', '.delete-btn', function() {
        if(confirm('Are you sure you want to delete this garment condition?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/garment-conditions/${id}`,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(res) {
                    fetchGarmentConditions();
                }
            });
        }
    });
});
</script>
@endpush 