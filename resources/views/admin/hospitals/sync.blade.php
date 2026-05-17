<!-- Sync Hospitals Modal -->
<div class="modal fade" id="syncModal" tabindex="-1" aria-labelledby="syncModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold text-dark" id="syncModalLabel"><i class="fa-solid fa-rotate me-2 text-primary"></i>Sync City Hospitals Directory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Input Form Section -->
                <div id="syncFormSection">
                    <p class="text-muted mb-4">Enter the city name to live-scrape and synchronize verified hospitals and clinics. Existing records will be updated automatically.</p>
                    <form id="syncHospitalsForm">
                        <div class="mb-4">
                            <label for="syncCityInput" class="form-label fw-bold text-dark">City Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="syncCityInput" placeholder="e.g. Jaipur, Delhi, Mumbai, Bangalore" value="Jaipur" required>
                            <div class="form-text">The scraper will fetch directly from public hospital directories for this city.</div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4" id="syncSubmitBtn"><i class="fa-solid fa-cloud-arrow-down me-2"></i>Start Syncing</button>
                        </div>
                    </form>
                </div>

                <!-- Live Progress Section -->
                <div id="syncProgressSection" style="display: none;" class="py-3 text-center">
                    <div class="mb-3">
                        <i class="fa-solid fa-cloud-arrow-down fa-bounce fs-1 text-primary mb-3"></i>
                        <h6 class="fw-bold text-dark mb-1">Live Synchronization in Progress...</h6>
                        <p id="syncStatusMessage" class="text-muted fs-7 mb-4">Connecting to hospital directories...</p>
                    </div>
                    <div class="progress mb-3" style="height: 24px; border-radius: 12px;">
                        <div id="syncProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary fw-bold fs-7" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">10%</div>
                    </div>
                    <p class="text-muted fs-8 mb-0">Please do not close this window or refresh the page.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const syncForm = document.getElementById('syncHospitalsForm');
    if (!syncForm) return;

    syncForm.addEventListener('submit', function (e) {
        e.preventDefault();
        
        const city = document.getElementById('syncCityInput').value;
        const formSection = document.getElementById('syncFormSection');
        const progressSection = document.getElementById('syncProgressSection');
        const progressBar = document.getElementById('syncProgressBar');
        const statusMsg = document.getElementById('syncStatusMessage');
        const submitBtn = document.getElementById('syncSubmitBtn');

        formSection.style.display = 'none';
        progressSection.style.display = 'block';
        submitBtn.disabled = true;

        // Start Sync Request
        fetch('{{ route('admin.hospitals.sync') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ city: city })
        })
        .then(response => response.json())
        .then(data => {
            // Handled by poll
        })
        .catch(error => console.error('Error:', error));

        // Poll Progress
        const interval = setInterval(() => {
            fetch('{{ route('admin.hospitals.sync.progress') }}')
            .then(res => res.json())
            .then(data => {
                if (data.progress) {
                    progressBar.style.width = data.progress + '%';
                    progressBar.setAttribute('aria-valuenow', data.progress);
                    progressBar.innerText = data.progress + '%';
                }
                if (data.message) {
                    statusMsg.innerText = data.message;
                }
                if (data.status === 'completed') {
                    clearInterval(interval);
                    progressBar.classList.remove('progress-bar-animated');
                    progressBar.classList.add('bg-success');
                    statusMsg.innerHTML = '<span class="text-success fw-bold fs-6"><i class="fa-solid fa-circle-check me-1"></i> ' + data.message + '</span>';
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            })
            .catch(err => console.error('Poll error:', err));
        }, 1000);
    });
});
</script>
