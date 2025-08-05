@extends('admin.layouts.app')

@section('title', 'Manage Body Type Fits')
@section('page_title', 'Manage Body Type Fits')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">Body Type Fits</div>
        <div class="card-body">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBodyTypeFitModal">Add Body Type Fit</button>
            <table class="table table-bordered table-hover" id="bodyTypeFitsTable">
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

    <!-- Add Body Type Fit Modal -->
    <div class="modal fade" id="addBodyTypeFitModal" tabindex="-1" aria-labelledby="addBodyTypeFitModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addBodyTypeFitModalLabel">Add Body Type Fit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="addBodyTypeFitForm">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addBodyTypeFitName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="addBodyTypeFitName" name="name" required>
                    <div class="invalid-feedback" id="addBodyTypeFitError"></div>
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

    <!-- Edit Body Type Fit Modal -->
    <div class="modal fade" id="editBodyTypeFitModal" tabindex="-1" aria-labelledby="editBodyTypeFitModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editBodyTypeFitModalLabel">Edit Body Type Fit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editBodyTypeFitForm">
            <div class="modal-body">
                <input type="hidden" id="editBodyTypeFitId">
                <div class="mb-3">
                    <label for="editBodyTypeFitName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="editBodyTypeFitName" name="name" required>
                    <div class="invalid-feedback" id="editBodyTypeFitError"></div>
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
    // Fetch and display body type fits
    function fetchBodyTypeFits() {
        $.get('/admin/body-type-fits/json', function(bodyTypeFits) {
            let rows = '';
            bodyTypeFits.forEach(function(bodyTypeFit) {
                rows += `<tr>
                    <td>${bodyTypeFit.id}</td>
                    <td>${bodyTypeFit.name}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${bodyTypeFit.id}" data-name="${bodyTypeFit.name}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${bodyTypeFit.id}">Delete</button>
                    </td>
                </tr>`;
            });
            $('#bodyTypeFitsTable tbody').html(rows);
        });
    }
    fetchBodyTypeFits();

    // Add Body Type Fit
    $('#addBodyTypeFitForm').on('submit', function(e) {
        e.preventDefault();
        var name = $('#addBodyTypeFitName').val();
        $.ajax({
            url: '{{ route('body_type_fits.store') }}',
            method: 'POST',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#addBodyTypeFitModal').modal('hide');
                    $('#addBodyTypeFitForm')[0].reset();
                    $('#addBodyTypeFitName').removeClass('is-invalid');
                    $('#addBodyTypeFitError').hide();
                    fetchBodyTypeFits();
                }
            },
            error: function(xhr) {
                $('#addBodyTypeFitError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#addBodyTypeFitName').addClass('is-invalid');
            }
        });
    });
    $('#addBodyTypeFitModal').on('hidden.bs.modal', function() {
        $('#addBodyTypeFitForm')[0].reset();
        $('#addBodyTypeFitName').removeClass('is-invalid');
        $('#addBodyTypeFitError').hide();
    });

    // Open edit modal with pre-filled data
    $(document).on('click', '.edit-btn', function() {
        $('#editBodyTypeFitId').val($(this).data('id'));
        $('#editBodyTypeFitName').val($(this).data('name'));
        $('#editBodyTypeFitError').hide();
        var modal = new bootstrap.Modal(document.getElementById('editBodyTypeFitModal'));
        modal.show();
    });

    // Save changes (edit body type fit)
    $('#editBodyTypeFitForm').submit(function(e) {
        e.preventDefault();
        let id = $('#editBodyTypeFitId').val();
        let name = $('#editBodyTypeFitName').val();
        $.ajax({
            url: `/admin/body-type-fits/${id}`,
            method: 'PUT',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#editBodyTypeFitModal').modal('hide');
                    fetchBodyTypeFits();
                }
            },
            error: function(xhr) {
                $('#editBodyTypeFitError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#editBodyTypeFitName').addClass('is-invalid');
            }
        });
    });
    $('#editBodyTypeFitModal').on('hidden.bs.modal', function() {
        $('#editBodyTypeFitForm')[0].reset();
        $('#editBodyTypeFitName').removeClass('is-invalid');
        $('#editBodyTypeFitError').hide();
    });

    // Delete body type fit
    $(document).on('click', '.delete-btn', function() {
        if(confirm('Are you sure you want to delete this body type fit?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/body-type-fits/${id}`,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(res) {
                    fetchBodyTypeFits();
                }
            });
        }
    });
});
</script>
@endpush 