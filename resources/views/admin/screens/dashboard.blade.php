@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@push('styles')
<style>
    /* Prevent charts from growing continuously */
    #clothesPieChart,
    #clothesBarChart {
        max-height: 300px !important;
    }

    .card-body canvas {
        width: 100% !important;
        height: 250px !important;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Users</h6>
                    <h2 id="usersCount">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Clothes</h6>
                    <h2 id="clothesCount">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Approved Clothes</h6>
                    <h2 id="approvedCount">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Pending Clothes</h6>
                    <h2 id="pendingCount">0</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">Clothes by Status</div>
                <div class="card-body">
                    <canvas id="clothesPieChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">Clothes Added Per Month</div>
                <div class="card-body">
                    <canvas id="clothesBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let pieChartInstance = null;
let barChartInstance = null;

$(document).ready(function () {
    $.get("{{ url('/admin/dashboard/stats') }}", function(data) {
        // Update stats
        $('#usersCount').text(data.users);
        $('#clothesCount').text(data.clothes);
        $('#approvedCount').text(data.approved);
        $('#pendingCount').text(data.pending);

        // Destroy old chart instances if they exist
        if (pieChartInstance) pieChartInstance.destroy();
        if (barChartInstance) barChartInstance.destroy();

        // Fix canvas size before drawing charts
        document.getElementById('clothesPieChart').height = 250;
        document.getElementById('clothesBarChart').height = 250;

        // Pie Chart
        pieChartInstance = new Chart(document.getElementById('clothesPieChart'), {
            type: 'pie',
            data: {
                labels: ['Approved', 'Pending'],
                datasets: [{
                    data: [data.approved, data.pending],
                    backgroundColor: ['#28a745', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                animation: false
            }
        });

        // Bar Chart
        barChartInstance = new Chart(document.getElementById('clothesBarChart'), {
            type: 'bar',
            data: {
                labels: data.months,
                datasets: [{
                    label: 'Clothes Added',
                    data: data.monthly,
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                animation: false
            }
        });
    });
});
</script>
@endpush
