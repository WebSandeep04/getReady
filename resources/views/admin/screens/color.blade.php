@extends('admin.layouts.app')

@section('title', 'Manage Colors')
@section('page_title', 'Manage Colors')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">Colors</div>
        <div class="card-body">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addColorModal">Add Color</button>
            <table class="table table-bordered table-hover" id="colorsTable">
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

    <!-- Add Color Modal -->
    <div class="modal fade" id="addColorModal" tabindex="-1" aria-labelledby="addColorModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addColorModalLabel">Add Color</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="addColorForm">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addColorName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="addColorName" name="name" required>
                    <div class="invalid-feedback" id="addColorError"></div>
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

    <!-- Edit Color Modal -->
    <div class="modal fade" id="editColorModal" tabindex="-1" aria-labelledby="editColorModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editColorModalLabel">Edit Color</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editColorForm">
            <div class="modal-body">
                <input type="hidden" id="editColorId">
                <div class="mb-3">
                    <label for="editColorName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="editColorName" name="name" required>
                    <div class="invalid-feedback" id="editColorError"></div>
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
    // Fetch and display colors
    function fetchColors() {
        $.get('/admin/colors/json', function(colors) {
            let rows = '';
            colors.forEach(function(color) {
                rows += `<tr>
                    <td>${color.id}</td>
                    <td>${color.name}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${color.id}" data-name="${color.name}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${color.id}">Delete</button>
                    </td>
                </tr>`;
            });
            $('#colorsTable tbody').html(rows);
        });
    }
    fetchColors();

    // Add Color
    $('#addColorForm').on('submit', function(e) {
        e.preventDefault();
        var name = $('#addColorName').val();
        $.ajax({
            url: '{{ route('colors.store') }}',
            method: 'POST',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#addColorModal').modal('hide');
                    $('#addColorForm')[0].reset();
                    $('#addColorName').removeClass('is-invalid');
                    $('#addColorError').hide();
                    fetchColors();
                }
            },
            error: function(xhr) {
                $('#addColorError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#addColorName').addClass('is-invalid');
            }
        });
    });
    $('#addColorModal').on('hidden.bs.modal', function() {
        $('#addColorForm')[0].reset();
        $('#addColorName').removeClass('is-invalid');
        $('#addColorError').hide();
    });

    // Open edit modal with pre-filled data
    $(document).on('click', '.edit-btn', function() {
        $('#editColorId').val($(this).data('id'));
        $('#editColorName').val($(this).data('name'));
        $('#editColorError').hide();
        var modal = new bootstrap.Modal(document.getElementById('editColorModal'));
        modal.show();
    });

    // Save changes (edit color)
    $('#editColorForm').submit(function(e) {
        e.preventDefault();
        let id = $('#editColorId').val();
        let name = $('#editColorName').val();
        $.ajax({
            url: `/admin/colors/${id}`,
            method: 'PUT',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#editColorModal').modal('hide');
                    fetchColors();
                }
            },
            error: function(xhr) {
                $('#editColorError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#editColorName').addClass('is-invalid');
            }
        });
    });
    $('#editColorModal').on('hidden.bs.modal', function() {
        $('#editColorForm')[0].reset();
        $('#editColorName').removeClass('is-invalid');
        $('#editColorError').hide();
    });

    // Delete color
    $(document).on('click', '.delete-btn', function() {
        if(confirm('Are you sure you want to delete this color?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/colors/${id}`,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(res) {
                    fetchColors();
                }
            });
        }
    });
});
</script>
@endpush 