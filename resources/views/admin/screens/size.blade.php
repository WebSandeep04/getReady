@extends('admin.layouts.app')

@section('title', 'Manage Sizes')
@section('page_title', 'Manage Sizes')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">Sizes</div>
        <div class="card-body">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSizeModal">Add Size</button>
            <table class="table table-bordered table-hover" id="sizesTable">
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

    <!-- Add Size Modal -->
    <div class="modal fade" id="addSizeModal" tabindex="-1" aria-labelledby="addSizeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addSizeModalLabel">Add Size</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="addSizeForm">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addSizeName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="addSizeName" name="name" required>
                    <div class="invalid-feedback" id="addSizeError"></div>
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

    <!-- Edit Size Modal -->
    <div class="modal fade" id="editSizeModal" tabindex="-1" aria-labelledby="editSizeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editSizeModalLabel">Edit Size</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editSizeForm">
            <div class="modal-body">
                <input type="hidden" id="editSizeId">
                <div class="mb-3">
                    <label for="editSizeName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="editSizeName" name="name" required>
                    <div class="invalid-feedback" id="editSizeError"></div>
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
    // Fetch and display sizes
    function fetchSizes() {
        $.get('/admin/sizes/json', function(sizes) {
            let rows = '';
            sizes.forEach(function(size) {
                rows += `<tr>
                    <td>${size.id}</td>
                    <td>${size.name}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${size.id}" data-name="${size.name}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${size.id}">Delete</button>
                    </td>
                </tr>`;
            });
            $('#sizesTable tbody').html(rows);
        });
    }
    fetchSizes();

    // Add Size
    $('#addSizeForm').on('submit', function(e) {
        e.preventDefault();
        var name = $('#addSizeName').val();
        $.ajax({
            url: '{{ route('sizes.store') }}',
            method: 'POST',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#addSizeModal').modal('hide');
                    $('#addSizeForm')[0].reset();
                    $('#addSizeName').removeClass('is-invalid');
                    $('#addSizeError').hide();
                    fetchSizes();
                }
            },
            error: function(xhr) {
                $('#addSizeError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#addSizeName').addClass('is-invalid');
            }
        });
    });
    $('#addSizeModal').on('hidden.bs.modal', function() {
        $('#addSizeForm')[0].reset();
        $('#addSizeName').removeClass('is-invalid');
        $('#addSizeError').hide();
    });

    // Open edit modal with pre-filled data
    $(document).on('click', '.edit-btn', function() {
        $('#editSizeId').val($(this).data('id'));
        $('#editSizeName').val($(this).data('name'));
        $('#editSizeError').hide();
        var modal = new bootstrap.Modal(document.getElementById('editSizeModal'));
        modal.show();
    });

    // Save changes (edit size)
    $('#editSizeForm').submit(function(e) {
        e.preventDefault();
        let id = $('#editSizeId').val();
        let name = $('#editSizeName').val();
        $.ajax({
            url: `/admin/sizes/${id}`,
            method: 'PUT',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#editSizeModal').modal('hide');
                    fetchSizes();
                }
            },
            error: function(xhr) {
                $('#editSizeError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#editSizeName').addClass('is-invalid');
            }
        });
    });
    $('#editSizeModal').on('hidden.bs.modal', function() {
        $('#editSizeForm')[0].reset();
        $('#editSizeName').removeClass('is-invalid');
        $('#editSizeError').hide();
    });

    // Delete size
    $(document).on('click', '.delete-btn', function() {
        if(confirm('Are you sure you want to delete this size?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/sizes/${id}`,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(res) {
                    fetchSizes();
                }
            });
        }
    });
});
</script>
@endpush 