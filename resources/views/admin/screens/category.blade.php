@extends('admin.layouts.app')

@section('title', 'Manage Categories')
@section('page_title', 'Manage Categories')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">Categories</div>
        <div class="card-body">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>
            <table class="table table-bordered table-hover" id="categoriesTable">
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

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="addCategoryForm">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addCategoryName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="addCategoryName" name="name" required>
                    <div class="invalid-feedback" id="addCategoryError"></div>
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

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editCategoryForm">
            <div class="modal-body">
                <input type="hidden" id="editCategoryId">
                <div class="mb-3">
                    <label for="editCategoryName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="editCategoryName" name="name" required>
                    <div class="invalid-feedback" id="editCategoryError"></div>
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
    // Fetch and display categories
    function loadCategories() {
        $.get("{{ route('categories.index') }}", function(html) {
            // Parse categories from the rendered view (or use a dedicated fetch route for JSON in real app)
            // For now, let's fetch via AJAX to a new endpoint that returns JSON
        });
    }
    // Use a dedicated endpoint for fetching categories as JSON
    function fetchCategories() {
        $.get('/admin/categories/json', function(categories) {
            let rows = '';
            categories.forEach(function(category) {
                rows += `<tr>
                    <td>${category.id}</td>
                    <td>${category.name}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${category.id}" data-name="${category.name}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${category.id}">Delete</button>
                    </td>
                </tr>`;
            });
            $('#categoriesTable tbody').html(rows);
        });
    }
    fetchCategories();

    // Add Category
    $('#addCategoryForm').on('submit', function(e) {
        e.preventDefault();
        var name = $('#addCategoryName').val();
        $.ajax({
            url: '{{ route('categories.store') }}',
            method: 'POST',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#addCategoryModal').modal('hide');
                    $('#addCategoryForm')[0].reset();
                    $('#addCategoryName').removeClass('is-invalid');
                    $('#addCategoryError').hide();
                    fetchCategories();
                }
            },
            error: function(xhr) {
                $('#addCategoryError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#addCategoryName').addClass('is-invalid');
            }
        });
    });
    $('#addCategoryModal').on('hidden.bs.modal', function() {
        $('#addCategoryForm')[0].reset();
        $('#addCategoryName').removeClass('is-invalid');
        $('#addCategoryError').hide();
    });

    // Open edit modal with pre-filled data
    $(document).on('click', '.edit-btn', function() {
        $('#editCategoryId').val($(this).data('id'));
        $('#editCategoryName').val($(this).data('name'));
        $('#editCategoryError').hide();
        var modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
        modal.show();
    });

    // Save changes (edit category)
    $('#editCategoryForm').submit(function(e) {
        e.preventDefault();
        let id = $('#editCategoryId').val();
        let name = $('#editCategoryName').val();
        $.ajax({
            url: `/admin/categories/${id}`,
            method: 'PUT',
            data: { name: name, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.success) {
                    $('#editCategoryModal').modal('hide');
                    fetchCategories();
                }
            },
            error: function(xhr) {
                $('#editCategoryError').text(xhr.responseJSON.errors?.name?.[0] || 'Error').show();
                $('#editCategoryName').addClass('is-invalid');
            }
        });
    });
    $('#editCategoryModal').on('hidden.bs.modal', function() {
        $('#editCategoryForm')[0].reset();
        $('#editCategoryName').removeClass('is-invalid');
        $('#editCategoryError').hide();
    });

    // Delete category
    $(document).on('click', '.delete-btn', function() {
        if(confirm('Are you sure you want to delete this category?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/categories/${id}`,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(res) {
                    fetchCategories();
                }
            });
        }
    });
});
</script>
@endpush 