@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Video Consultations</h1>
            <p class="text-muted mb-0">Monitor live browser-based consultation requests and join them directly from the dashboard.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('consultations.index') }}" target="_blank" class="btn btn-outline-primary">
                <i class="fa-solid fa-up-right-from-square me-2"></i>Open patient lobby
            </a>
            <a href="{{ route('admin.consultations') }}" class="btn btn-primary">
                <i class="fa-solid fa-rotate-right me-2"></i>Refresh list
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase small fw-semibold mb-2">Pending</div>
                    <div class="display-6 fw-bold text-warning mb-0">{{ $pendingConsultations->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase small fw-semibold mb-2">Active</div>
                    <div class="display-6 fw-bold text-success mb-0">{{ $activeConsultations->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase small fw-semibold mb-2">Recently completed</div>
                    <div class="display-6 fw-bold text-secondary mb-0">{{ $completedConsultations->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h2 class="h5 mb-0 fw-bold text-dark">Pending and active rooms</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Patient</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $liveConsultations = $pendingConsultations->concat($activeConsultations)->sortByDesc('updated_at');
                        @endphp
                        @forelse($liveConsultations as $consultation)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $consultation->patient_name }}</div>
                                    <div class="small text-muted">{{ $consultation->uuid }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $consultation->status === 'active' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ucfirst($consultation->status) }}
                                    </span>
                                </td>
                                <td class="small text-muted">{{ optional($consultation->created_at)->format('d M Y, h:i A') }}</td>
                                <td class="small text-muted">{{ optional($consultation->updated_at)->diffForHumans() }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.consultations.join', $consultation->uuid) }}" class="btn btn-sm btn-primary">
                                        <i class="fa-solid fa-video me-1"></i>Join call
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-video-slash fs-3 d-block mb-3"></i>
                                    No live consultation requests right now.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h2 class="h5 mb-0 fw-bold text-dark">Recent completed calls</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Patient</th>
                            <th>Completed</th>
                            <th>Room ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($completedConsultations as $consultation)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $consultation->patient_name }}</td>
                                <td class="small text-muted">{{ optional($consultation->updated_at)->format('d M Y, h:i A') }}</td>
                                <td class="small text-muted">{{ $consultation->uuid }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No completed consultation history yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
