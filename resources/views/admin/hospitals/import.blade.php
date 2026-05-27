<!-- Import Modal -->
<div class="modal" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Import Hospitals (CSV)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.hospitals.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Upload CSV File</label>
                        <input type="file" name="file" class="form-control" accept=".csv" required>
                        <div class="form-text mt-2">
                            Ensure your CSV file matches the required columns.
                            <a href="{{ route('sample.download', ['type' => 'hospitals']) }}" class="text-primary fw-semibold d-inline-block mt-1">
                                <i class="fa-solid fa-download me-1"></i> Download Sample CSV Template
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>


