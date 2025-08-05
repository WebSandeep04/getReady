@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Admin Dashboard')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">Clothes Approval</div>
        <div class="card-body">
            <table class="table table-bordered table-hover" id="clothesTable">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Owner</th>
                        <th>Gender</th>
                        <th>Size</th>
                        <th>Condition</th>
                        <th>Rent Price</th>
                        <th>Security Deposit</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data loaded by jQuery AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    function loadClothes() {
        $.get("{{ route('clothes.fetch') }}", function(clothes) {
            let rows = '';
            clothes.forEach(function(cloth) {
                let image = cloth.images && cloth.images.length > 0
                    ? `<img src='/storage/${cloth.images[0].image_path}' alt='${cloth.title}' style='width:60px;height:60px;object-fit:cover;border-radius:6px;'>`
                    : `<img src='/images/1.jpg' alt='${cloth.title}' style='width:60px;height:60px;object-fit:cover;border-radius:6px;'>`;
                let status = cloth.is_approved ? '<span class="badge bg-success">Approved</span>' : '<span class="badge bg-warning text-dark">Pending</span>';
                rows += `<tr>
                    <td>${cloth.id}</td>
                    <td>${image}</td>
                    <td>${cloth.title}</td>
                    <td>${cloth.category}</td>
                    <td>${cloth.user ? cloth.user.name : ''}</td>
                    <td>${cloth.gender}</td>
                    <td>${cloth.size}</td>
                    <td>${cloth.condition}</td>
                    <td>₹${cloth.rent_price}</td>
                    <td>₹${cloth.security_deposit}</td>
                    <td>${status}</td>
                    <td>
                        <button class="btn btn-sm btn-success approve-btn" data-id="${cloth.id}" ${cloth.is_approved ? 'disabled' : ''}>Approve</button>
                        <button class="btn btn-sm btn-danger reject-btn" data-id="${cloth.id}" ${!cloth.is_approved ? 'disabled' : ''}>Reject</button>
                    </td>
                </tr>`;
            });
            $('#clothesTable tbody').html(rows);
        });
    }
    loadClothes();

    // Approve
    $(document).on('click', '.approve-btn', function() {
        let id = $(this).data('id');
        $.post(`{{ url('/admin/clothes/approve') }}/${id}`, {_token: '{{ csrf_token() }}'}, function(res) {
            loadClothes();
        });
    });
    // Reject
    $(document).on('click', '.reject-btn', function() {
        let id = $(this).data('id');
        $.post(`{{ url('/admin/clothes/reject') }}/${id}`, {_token: '{{ csrf_token() }}'}, function(res) {
            loadClothes();
        });
    });
});
</script>
@endpush
