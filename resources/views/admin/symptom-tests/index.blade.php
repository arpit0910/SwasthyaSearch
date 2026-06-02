@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 p-lg-5 rounded-4 border shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #0f766e 100%);">
                <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
                    <div>
                        <h1 class="h3 mb-2 fw-bold text-white">Symptom Test Reports</h1>
                        <p class="text-white-50 mb-0">Track how users start symptom checks, what they report, and which likely conditions surface most often.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge rounded-pill text-bg-light text-dark px-3 py-2">Last {{ $days }} days trend</span>
                        <span class="badge rounded-pill text-bg-light text-dark px-3 py-2">{{ number_format($stats['total_submissions']) }} total submissions</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-muted fw-semibold mb-2 text-uppercase fs-7">Total Submissions</div>
                    <div class="d-flex align-items-end justify-content-between gap-3">
                        <div class="h2 mb-0 fw-bold text-dark">{{ number_format($stats['total_submissions']) }}</div>
                        <i class="fa-solid fa-notes-medical fs-2 text-primary"></i>
                    </div>
                    <div class="small text-muted mt-2">All symptom test records stored so far.</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-muted fw-semibold mb-2 text-uppercase fs-7">Last 7 Days</div>
                    <div class="d-flex align-items-end justify-content-between gap-3">
                        <div class="h2 mb-0 fw-bold text-dark">{{ number_format($stats['last_7_days']) }}</div>
                        <i class="fa-solid fa-calendar-week fs-2 text-success"></i>
                    </div>
                    <div class="small text-muted mt-2">Shows recent user engagement.</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-muted fw-semibold mb-2 text-uppercase fs-7">Last 30 Days</div>
                    <div class="d-flex align-items-end justify-content-between gap-3">
                        <div class="h2 mb-0 fw-bold text-dark">{{ number_format($stats['last_30_days']) }}</div>
                        <i class="fa-solid fa-calendar-days fs-2 text-warning"></i>
                    </div>
                    <div class="small text-muted mt-2">Useful for trend checks and growth.</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-muted fw-semibold mb-2 text-uppercase fs-7">Average Age</div>
                    <div class="d-flex align-items-end justify-content-between gap-3">
                        <div class="h2 mb-0 fw-bold text-dark">{{ $stats['avg_age'] > 0 ? number_format($stats['avg_age'], 1) : '—' }}</div>
                        <i class="fa-solid fa-user-group fs-2 text-info"></i>
                    </div>
                    <div class="small text-muted mt-2">Helps us understand the audience profile.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-chart-column me-2 text-primary"></i>Submission Trend</h5>
                        <div class="small text-muted">Daily volume of symptom tests over the selected period.</div>
                    </div>
                    <form method="GET" class="d-flex align-items-center gap-2">
                        <label class="small text-muted mb-0" for="days">Range</label>
                        <select name="days" id="days" class="form-select form-select-sm" style="min-width: 110px" onchange="this.form.submit()">
                            <option value="7" @selected($days === 7)>7 days</option>
                            <option value="14" @selected($days === 14)>14 days</option>
                            <option value="30" @selected($days === 30)>30 days</option>
                            <option value="60" @selected($days === 60)>60 days</option>
                            <option value="90" @selected($days === 90)>90 days</option>
                        </select>
                        @if(request()->filled('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request()->filled('gender'))
                            <input type="hidden" name="gender" value="{{ request('gender') }}">
                        @endif
                    </form>
                </div>
                <div class="card-body p-4" style="height: 360px;">
                    <canvas id="submissionsTrendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-venus-mars me-2 text-primary"></i>Gender Breakdown</h5>
                    <div class="small text-muted">Captured from the symptom test form.</div>
                </div>
                <div class="card-body p-4" style="height: 360px;">
                    <canvas id="genderBreakdownChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-stethoscope me-2 text-success"></i>Top Symptoms</h5>
                    <div class="small text-muted">Most common symptoms selected or entered by users.</div>
                </div>
                <div class="card-body p-4" style="height: 380px;">
                    <canvas id="topSymptomsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-virus me-2 text-danger"></i>Top Likely Conditions</h5>
                    <div class="small text-muted">Conditions most often surfaced by the analysis engine.</div>
                </div>
                <div class="card-body p-4">
                    @forelse($topDiseases as $item)
                        <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                            <div class="fw-semibold text-dark">{{ $item['label'] }}</div>
                            <span class="badge text-bg-primary rounded-pill">{{ number_format($item['total']) }}</span>
                        </div>
                    @empty
                        <div class="text-muted">No analyzed disease matches yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-lg-6">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-filter me-2 text-secondary"></i>Recent Submissions</h5>
                    <div class="small text-muted">Search and review the latest symptom checks from users.</div>
                </div>
                <div class="col-12 col-lg-6">
                    <form method="GET" class="d-flex flex-column flex-md-row gap-2 justify-content-lg-end">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search symptoms, gender, or locale">
                        <select name="gender" class="form-select" style="max-width: 180px">
                            <option value="">All genders</option>
                            <option value="male" @selected(request('gender') === 'male')>Male</option>
                            <option value="female" @selected(request('gender') === 'female')>Female</option>
                            <option value="other" @selected(request('gender') === 'other')>Other</option>
                        </select>
                        <input type="hidden" name="days" value="{{ $days }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> Filter
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>Symptoms</th>
                        <th>Likely Condition</th>
                        <th>Department</th>
                        <th>Locale</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSubmissions as $submission)
                        <tr>
                            <td class="small text-muted">{{ optional($submission->created_at)->format('d M Y, h:i A') ?? '-' }}</td>
                            <td>
                                <div class="fw-semibold">{{ $submission->age ? $submission->age . ' yrs' : 'Age N/A' }}</div>
                                <div class="small text-muted text-capitalize">{{ $submission->gender ?: 'Unspecified' }}</div>
                            </td>
                            <td style="min-width: 260px">
                                <div class="small fw-semibold text-dark">{{ \Illuminate\Support\Str::limit($submission->symptom_text ?: 'No free-text symptoms', 110) }}</div>
                                <div class="mt-2 d-flex flex-wrap gap-1">
                                    @foreach(array_slice($submission->selected_symptoms ?? [], 0, 4) as $symptom)
                                        <span class="badge rounded-pill text-bg-light border text-dark">{{ $symptom }}</span>
                                    @endforeach
                                    @if(count($submission->selected_symptoms ?? []) > 4)
                                        <span class="badge rounded-pill text-bg-light border text-dark">+{{ count($submission->selected_symptoms) - 4 }} more</span>
                                    @endif
                                </div>
                            </td>
                            <td style="min-width: 220px">
                                @if($submission->topDisease)
                                    <div class="fw-semibold text-dark">{{ $submission->topDisease->getTranslation('name', 'en') ?: $submission->topDisease->getTranslation('name', 'hi') }}</div>
                                    <div class="small text-muted">{{ number_format((float) $submission->top_score, 2) }} match score</div>
                                @else
                                    <span class="text-muted">No condition found</span>
                                @endif
                            </td>
                            <td>
                                {{ $submission->recommendedDepartment ? ($submission->recommendedDepartment->getTranslation('name', 'en') ?: $submission->recommendedDepartment->getTranslation('name', 'hi')) : 'N/A' }}
                            </td>
                            <td class="text-capitalize">{{ $submission->locale ?: 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-1 d-block mb-3"></i>
                                No symptom submissions yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($recentSubmissions->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $recentSubmissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const trendCtx = document.getElementById('submissionsTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [{
                    label: 'Submissions',
                    data: @json($trendValues),
                    borderColor: '#0f766e',
                    backgroundColor: 'rgba(20, 184, 166, 0.15)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3,
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    const genderCtx = document.getElementById('genderBreakdownChart');
    if (genderCtx) {
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($genderBreakdown)),
                datasets: [{
                    data: @json(array_values($genderBreakdown)),
                    backgroundColor: ['#0f766e', '#2563eb', '#f59e0b', '#8b5cf6', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    const symptomsCtx = document.getElementById('topSymptomsChart');
    if (symptomsCtx) {
        new Chart(symptomsCtx, {
            type: 'bar',
            data: {
                labels: @json(collect($topSymptoms)->pluck('label')->all()),
                datasets: [{
                    label: 'Mentions',
                    data: @json(collect($topSymptoms)->pluck('total')->all()),
                    backgroundColor: 'rgba(59, 130, 246, 0.75)',
                    borderRadius: 8,
                    barThickness: 18
                }]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }
});
</script>
@endpush
