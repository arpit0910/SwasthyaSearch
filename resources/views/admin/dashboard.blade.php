@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2 fw-bold text-dark">Dashboard Overview</h1>
            <p class="text-muted">Welcome back, Administrator. Here is the current status of the SwasthyaSearch platform.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted fw-semibold mb-1 text-uppercase fs-7">Verified Doctors</div>
                        <div class="h2 mb-0 fw-bold text-dark">{{ number_format($stats['verified_doctors']) }}</div>
                        <div class="text-success fs-7 mt-2"><i class="fa-solid fa-arrow-trend-up me-1"></i> Active Professionals</div>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 rounded-3 text-primary fs-2">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted fw-semibold mb-1 text-uppercase fs-7">Verified Hospitals</div>
                        <div class="h2 mb-0 fw-bold text-dark">{{ number_format($stats['verified_hospitals']) }}</div>
                        <div class="text-success fs-7 mt-2"><i class="fa-solid fa-check-double me-1"></i> Registered Centers</div>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 rounded-3 text-success fs-2">
                        <i class="fa-solid fa-hospital"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted fw-semibold mb-1 text-uppercase fs-7">Medical Articles</div>
                        <div class="h2 mb-0 fw-bold text-dark">{{ number_format($stats['articles_count']) }}</div>
                        <div class="text-info fs-7 mt-2"><i class="fa-solid fa-file-lines me-1"></i> Expert Publications</div>
                    </div>
                    <div class="p-3 bg-info bg-opacity-10 rounded-3 text-info fs-2">
                        <i class="fa-solid fa-book-medical"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted fw-semibold mb-1 text-uppercase fs-7">Active Specialties</div>
                        <div class="h2 mb-0 fw-bold text-dark">{{ number_format($stats['active_departments']) }}</div>
                        <div class="text-warning fs-7 mt-2"><i class="fa-solid fa-circle-nodes me-1"></i> AI Matching Depts</div>
                    </div>
                    <div class="p-3 bg-warning bg-opacity-10 rounded-3 text-warning fs-2">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Chart -->
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-chart-column me-2 text-primary"></i> Doctors per Specialty</h5>
                </div>
                <div class="card-body p-4" style="position: relative; height:400px;">
                    <canvas id="doctorsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-shield-halved me-2 text-success"></i> System Status</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4 p-3 bg-light rounded-3">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-database fs-3 text-secondary"></i>
                            <div>
                                <div class="fw-bold">Database Connection</div>
                                <div class="text-muted fs-7">High-Speed SQLite Engine</div>
                            </div>
                        </div>
                        <span class="badge bg-success">Optimal</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4 p-3 bg-light rounded-3">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-bolt fs-3 text-warning"></i>
                            <div>
                                <div class="fw-bold">Session Driver</div>
                                <div class="text-muted fs-7">Filesystem Optimized</div>
                            </div>
                        </div>
                        <span class="badge bg-success">Active</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-language fs-3 text-info"></i>
                            <div>
                                <div class="fw-bold">Bilingual Infrastructure</div>
                                <div class="text-muted fs-7">EN / HI Dynamic Matching</div>
                            </div>
                        </div>
                        <span class="badge bg-success">Verified</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('doctorsChart').getContext('2d');
        const labels = {!! json_encode($chartData['labels']) !!};
        const data = {!! json_encode($chartData['data']) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Registered Doctors',
                    data: data,
                    backgroundColor: '#14b8a6',
                    borderColor: '#0d9488',
                    borderWidth: 1,
                    borderRadius: 6,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>
@endpush
