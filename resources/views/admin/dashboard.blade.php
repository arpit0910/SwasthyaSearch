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
                        <div class="text-muted fw-semibold mb-1 text-uppercase fs-7">Verified Blood Banks</div>
                        <div class="h2 mb-0 fw-bold text-dark">{{ number_format($stats['verified_blood_banks']) }}</div>
                        <div class="text-danger fs-7 mt-2"><i class="fa-solid fa-droplet me-1"></i> Emergency Blood Stock</div>
                    </div>
                    <div class="p-3 bg-danger bg-opacity-10 rounded-3 text-danger fs-2">
                        <i class="fa-solid fa-droplet"></i>
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
                <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-rotate me-2 text-primary"></i>Directory Sync</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#syncAllModal">
                        <i class="fa-solid fa-cloud-arrow-down me-1"></i> Sync All
                    </button>
                </div>
                <div class="card-body p-4">
                    <div class="small text-muted mb-2">Run a full sync for hospitals, doctors and blood banks with one click.</div>
                    <div id="syncAllInlineStatus" class="alert alert-light border mb-0 py-2 px-3 small">
                        Waiting for next sync run.
                    </div>
                </div>
            </div>

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

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-clock-rotate-left me-2 text-secondary"></i>Recent Directory Sync History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Time</th>
                                    <th>City</th>
                                    <th>Status</th>
                                    <th>Fetched (H/D/B)</th>
                                    <th>Delta (H/D/B)</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($syncHistory as $row)
                                    <tr>
                                        <td class="small">{{ optional($row->completed_at)->format('d M Y, h:i A') ?? '-' }}</td>
                                        <td>{{ $row->city }}</td>
                                        <td>
                                            <span class="badge {{ $row->status === 'completed' ? 'bg-success' : ($row->status === 'failed' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                                {{ ucfirst($row->status) }}
                                            </span>
                                        </td>
                                        <td class="small">{{ $row->fetched_hospitals }}/{{ $row->fetched_doctors }}/{{ $row->fetched_blood_banks }}</td>
                                        <td class="small">{{ $row->delta_hospitals }}/{{ $row->delta_doctors }}/{{ $row->delta_blood_banks }}</td>
                                        <td class="small text-muted">{{ \Illuminate\Support\Str::limit($row->message, 80) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No sync runs recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="syncAllModal" tabindex="-1" aria-labelledby="syncAllModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title fw-bold text-dark" id="syncAllModalLabel"><i class="fa-solid fa-database me-2 text-primary"></i>Sync Full Directory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="syncAllForm" class="row g-3">
                    <div class="col-md-6">
                        <label for="syncAllCity" class="form-label fw-semibold">Select City</label>
                        <select id="syncAllCity" class="form-select" required>
                            @foreach($supportedCities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" id="syncAllSubmitBtn" class="btn btn-primary w-100">
                            <i class="fa-solid fa-play me-1"></i> Start Sync
                        </button>
                    </div>
                </form>

                <div id="syncAllProgressSection" class="mt-4" style="display:none;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold small" id="syncAllMessage">Preparing sync...</span>
                        <span class="small text-muted" id="syncAllPercentLabel">0%</span>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div id="syncAllProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;">0%</div>
                    </div>
                    <div id="syncAllReport" class="small text-muted mt-2"></div>
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

        const syncAllForm = document.getElementById('syncAllForm');
        const syncAllProgressSection = document.getElementById('syncAllProgressSection');
        const syncAllProgressBar = document.getElementById('syncAllProgressBar');
        const syncAllMessage = document.getElementById('syncAllMessage');
        const syncAllPercentLabel = document.getElementById('syncAllPercentLabel');
        const syncAllReport = document.getElementById('syncAllReport');
        const syncAllInlineStatus = document.getElementById('syncAllInlineStatus');
        const syncAllSubmitBtn = document.getElementById('syncAllSubmitBtn');
        let pollTimer = null;

        function applyProgress(data) {
            const progress = Number(data.progress || 0);
            syncAllProgressBar.style.width = progress + '%';
            syncAllProgressBar.setAttribute('aria-valuenow', progress);
            syncAllProgressBar.innerText = progress + '%';
            syncAllPercentLabel.innerText = progress + '%';
            syncAllMessage.innerText = data.message || 'Syncing...';
            syncAllInlineStatus.innerText = data.message || 'Syncing...';
        }

        function pollProgress() {
            fetch('{{ route('admin.directory.sync_all.progress') }}')
                .then((res) => res.json())
                .then((data) => {
                    applyProgress(data);
                    if (data.status === 'completed' || data.status === 'failed') {
                        clearInterval(pollTimer);
                        syncAllSubmitBtn.disabled = false;
                        syncAllProgressBar.classList.remove('progress-bar-animated');
                        syncAllProgressBar.classList.add(data.status === 'completed' ? 'bg-success' : 'bg-danger');
                        if (data.report && data.report.fetched) {
                            const f = data.report.fetched;
                            syncAllReport.innerText = `Fetched records - Hospitals: ${f.hospitals}, Doctors: ${f.doctors}, Blood Banks: ${f.blood_banks}`;
                        }
                    }
                });
        }

        syncAllForm.addEventListener('submit', function(e) {
            e.preventDefault();
            syncAllSubmitBtn.disabled = true;
            syncAllProgressSection.style.display = 'block';
            syncAllProgressBar.classList.remove('bg-success', 'bg-danger');
            syncAllProgressBar.classList.add('progress-bar-animated');
            syncAllReport.innerText = '';
            applyProgress({progress: 5, message: 'Starting sync...'});

            fetch('{{ route('admin.directory.sync_all') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ city: document.getElementById('syncAllCity').value })
            }).then((res) => res.json()).then((data) => {
                if (data.status === 'failed' || data.status === 'busy') {
                    syncAllSubmitBtn.disabled = false;
                }
            });

            if (pollTimer) clearInterval(pollTimer);
            pollTimer = setInterval(pollProgress, 1500);
        });
    });
</script>
@endpush
