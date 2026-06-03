@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Medicine Reports</h1>
            <p class="text-muted mb-0">Track corrections, review public feedback, and close medicine content issues safely.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form class="row g-3 mb-3">
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach(['new', 'reviewing', 'resolved', 'rejected'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Medicine</th>
                            <th>Issue</th>
                            <th>Reporter</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $report->medicine?->name ?: $report->medicine_name ?: 'Unknown medicine' }}</div>
                                    <div class="text-muted small">{{ $report->created_at->format('d M Y, h:i A') }}</div>
                                </td>
                                <td style="min-width: 280px;">
                                    <div class="fw-semibold">{{ $report->report_type }}</div>
                                    <div class="small text-muted">{{ \Illuminate\Support\Str::limit($report->user_message, 140) }}</div>
                                </td>
                                <td>
                                    <div>{{ $report->reporter_name ?: 'Anonymous' }}</div>
                                    <div class="text-muted small">{{ $report->reporter_email ?: $report->reporter_phone ?: 'No contact' }}</div>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ ucfirst($report->status) }}</span></td>
                                <td style="min-width: 280px;">
                                    <form method="POST" action="{{ route('admin.medicine_reports.update', $report) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="d-flex flex-column gap-2">
                                            <select name="status" class="form-select form-select-sm">
                                                @foreach(['new', 'reviewing', 'resolved', 'rejected'] as $status)
                                                    <option value="{{ $status }}" @selected($report->status === $status)>{{ ucfirst($status) }}</option>
                                                @endforeach
                                            </select>
                                            <textarea name="admin_notes" class="form-control form-control-sm" rows="2" placeholder="Admin notes">{{ $report->admin_notes }}</textarea>
                                            <button class="btn btn-sm btn-outline-primary">Save</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No medicine reports yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
