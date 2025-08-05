<div class="sidebar position-fixed d-flex flex-column p-3 vh-100 bg-light shadow">
    <h4 class="text-center mb-4 title">Get Ready</h4>

    <button id="toggleSidebar" class="btn btn-outline-dark btn-sm ms-2 mb-3">
        <i class="bi bi-list"></i>
    </button>

    <div class="side-items">
        <!-- Main Menu Dropdown -->
        <a class="d-flex justify-content-between align-items-center text-decoration-none mb-2 text-dark" data-bs-toggle="collapse" href="#mainMenu" role="button" aria-expanded="false" aria-controls="mainMenu">
            <span><i class="bi bi-house-door me-3"></i>Main</span>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ms-3 mb-2" id="mainMenu">
            <a href="{{ route('admin.dashboard') }}" class="d-block py-1 ps-4 text-dark">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </div>
        <!-- Setup Menu -->
        <a class="d-flex justify-content-between align-items-center text-decoration-none mb-2 text-dark" data-bs-toggle="collapse" href="#setupMenu" role="button" aria-expanded="false" aria-controls="setupMenu">
            <span><i class="bi bi-gear me-3"></i>Setup</span>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ms-3 mb-2" id="setupMenu">
            <a href="{{ route('user.index') }}" class="d-block py-1 ps-4 text-dark"><i class="bi bi-people me-2"></i>User</a>
            <a href="{{ route('categories.index') }}" class="d-block py-1 ps-4 text-dark"><i class="bi bi-tags me-2"></i>Category</a>
            <a href="{{ route('fabric_types.index') }}" class="d-block py-1 ps-4 text-dark"><i class="bi bi-patch-check me-2"></i>Fabric Type</a>
            <a href="{{ route('colors.index') }}" class="d-block py-1 ps-4 text-dark"><i class="bi bi-palette me-2"></i>Color</a>
            <a href="{{ route('bottom_types.index') }}" class="d-block py-1 ps-4 text-dark"><i class="bi bi-slack me-2"></i>Bottom Type</a>
            <a href="{{ route('sizes.index') }}" class="d-block py-1 ps-4 text-dark"><i class="bi bi-arrows-expand me-2"></i>Size</a>
            <a href="{{ route('body_type_fits.index') }}" class="d-block py-1 ps-4 text-dark"><i class="bi bi-person-bounding-box me-2"></i>Body Type Fit</a>
            <a href="{{ route('garment_conditions.index') }}" class="d-block py-1 ps-4 text-dark"><i class="bi bi-shield-check me-2"></i>Garment Condition</a>
            <a href="{{ route('admin.frontend') }}" class="d-block py-1 ps-4 text-dark"><i class="bi bi-globe me-2"></i>Frontend</a>
        </div>
        <!-- Approval Menu -->
        <a class="d-flex justify-content-between align-items-center text-decoration-none mb-2 text-dark" data-bs-toggle="collapse" href="#approvalMenu" role="button" aria-expanded="false" aria-controls="approvalMenu">
            <span><i class="bi bi-check2-square me-3"></i>Approval</span>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ms-3 mb-2" id="approvalMenu">
            <a href="{{ route('admin') }}" class="d-block py-1 ps-4 text-dark"><i class="bi bi-bag-check me-2"></i>Clothes</a>
        </div>
    </div>
</div>

