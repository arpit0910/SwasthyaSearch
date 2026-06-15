<style>
    .consult-shell {
        display: grid;
        gap: 1.5rem;
    }
    .consult-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .consult-eyebrow {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #0f766e;
    }
    .consult-title {
        margin: 0.35rem 0 0;
        font-size: clamp(1.7rem, 3vw, 2.2rem);
        font-weight: 800;
        color: #0f172a;
    }
    .consult-subtitle {
        margin: 0.45rem 0 0;
        color: #475569;
    }
    .consult-header-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.75rem;
    }
    .consult-hidden {
        display: none !important;
    }
    .consult-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 2.5rem;
        padding: 0.55rem 1rem;
        border-radius: 999px;
        font-size: 0.92rem;
        font-weight: 700;
        background: #e2e8f0;
        color: #0f172a;
    }
    .consult-link,
    .consult-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        min-height: 2.9rem;
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        border: 1px solid transparent;
        text-decoration: none;
        font-weight: 700;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        cursor: pointer;
    }
    .consult-link:hover,
    .consult-btn:hover {
        transform: translateY(-1px);
    }
    .consult-link {
        background: #ffffff;
        border-color: #cbd5e1;
        color: #334155;
    }
    .consult-btn--soft {
        background: #ffffff;
        border-color: #99f6e4;
        color: #0f766e;
    }
    .consult-btn--danger {
        background: #dc2626;
        color: #ffffff;
    }
    .consult-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: minmax(0, 1.7fr) minmax(300px, 1fr);
    }
    .consult-prejoin {
        border: 1px solid #dbe7f3;
        border-radius: 1.75rem;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 18px 50px rgba(15, 23, 42, 0.1);
        padding: 1.4rem;
    }
    .consult-prejoin-grid {
        display: grid;
        gap: 1.25rem;
        grid-template-columns: minmax(0, 1.2fr) minmax(300px, 0.8fr);
    }
    .consult-prejoin-copy {
        display: grid;
        gap: 0.85rem;
        align-content: start;
    }
    .consult-prejoin-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
    }
    .consult-prejoin-text {
        color: #475569;
        line-height: 1.6;
    }
    .consult-device-grid {
        display: grid;
        gap: 0.9rem;
    }
    .consult-device-field label {
        display: block;
        margin-bottom: 0.38rem;
        font-size: 0.84rem;
        font-weight: 700;
        color: #334155;
    }
    .consult-device-field select {
        width: 100%;
        min-height: 2.9rem;
        border-radius: 0.95rem;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #0f172a;
        padding: 0.7rem 0.9rem;
    }
    .consult-prejoin-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.85rem;
        align-items: center;
    }
    .consult-join-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        min-height: 3rem;
        padding: 0.8rem 1.2rem;
        border-radius: 999px;
        border: 0;
        background: linear-gradient(135deg, #0f766e, #0ea5a6);
        color: #fff;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 14px 30px rgba(13, 148, 136, 0.24);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .consult-join-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 34px rgba(13, 148, 136, 0.28);
    }
    .consult-join-btn:disabled {
        cursor: not-allowed;
        opacity: 0.7;
        transform: none;
        box-shadow: none;
    }
    .consult-prejoin-status {
        font-size: 0.88rem;
        color: #64748b;
    }
    .consult-card {
        border: 1px solid #dbe7f3;
        border-radius: 1.5rem;
        overflow: hidden;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
    }
    .consult-card-head {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid #dbe7f3;
    }
    .consult-card-title {
        font-weight: 800;
        color: #0f172a;
    }
    .consult-card-meta {
        margin-top: 0.2rem;
        font-size: 0.88rem;
        color: #64748b;
    }
    .consult-card-body {
        padding: 1rem;
    }
    .consult-room-body {
        display: grid;
        gap: 1rem;
    }
    .consult-video-shell {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        border-radius: 1.25rem;
        overflow: hidden;
        background: #020617;
    }
    .consult-video-shell--local {
        aspect-ratio: 4 / 3;
    }
    .consult-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        background: #020617;
    }
    .consult-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        text-align: center;
        color: #ffffff;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.28), rgba(15, 23, 42, 0.75));
    }
    .consult-placeholder.hidden {
        display: none;
    }
    .consult-avatar-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.85rem;
        background: radial-gradient(circle at top, rgba(14, 165, 233, 0.16), transparent 38%), linear-gradient(180deg, rgba(15, 23, 42, 0.55), rgba(15, 23, 42, 0.82));
        color: #fff;
        text-align: center;
        padding: 1rem;
    }
    .consult-avatar-placeholder.hidden {
        display: none;
    }
    .consult-avatar-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 5rem;
        height: 5rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.18);
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: 0.04em;
    }
    .consult-avatar-name {
        font-size: 1rem;
        font-weight: 700;
    }
    .consult-avatar-note {
        font-size: 0.86rem;
        opacity: 0.78;
    }
    .consult-video-label {
        position: absolute;
        left: 1rem;
        bottom: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.5rem 0.85rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.7);
        color: #fff;
        font-size: 0.85rem;
        font-weight: 700;
        backdrop-filter: blur(10px);
    }
    .consult-preview-note {
        margin-top: 0.85rem;
        font-size: 0.88rem;
        color: #64748b;
    }
    .consult-sidebar {
        display: grid;
        gap: 1.5rem;
    }
    .consult-alert {
        display: none;
        border-radius: 1rem;
        border: 1px solid #fecaca;
        background: #fff1f2;
        color: #9f1239;
        padding: 0.95rem 1rem;
        font-size: 0.94rem;
        line-height: 1.5;
    }
    .consult-alert.visible {
        display: block;
    }
    .consult-controls {
        display: grid;
        gap: 0.75rem;
    }
    .consult-control-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        border-radius: 1.25rem;
        border: 1px solid #dbe7f3;
        background: #f8fafc;
        padding: 1rem;
    }
    .consult-control-group {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.75rem;
    }
    .consult-control-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 3.4rem;
        height: 3.4rem;
        border-radius: 999px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    }
    .consult-control-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
    }
    .consult-control-btn:disabled {
        cursor: not-allowed;
        opacity: 0.55;
        transform: none;
        box-shadow: none;
    }
    .consult-control-btn svg {
        width: 1.2rem;
        height: 1.2rem;
    }
    .consult-control-btn--off {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #b91c1c;
    }
    .consult-control-btn--danger {
        width: auto;
        min-width: 3.4rem;
        padding: 0 1.2rem;
        gap: 0.55rem;
        background: #dc2626;
        border-color: #dc2626;
        color: #fff;
    }
    .consult-control-text {
        font-size: 0.92rem;
        font-weight: 700;
        color: #334155;
    }
    .consult-control-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        min-height: 2.8rem;
        padding: 0.7rem 1rem;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid #dbe7f3;
        color: #334155;
        font-size: 0.88rem;
        font-weight: 700;
    }
    .consult-note {
        margin-top: 1.25rem;
        border-radius: 1.25rem;
        border: 1px solid #dbe7f3;
        background: #f8fafc;
        padding: 1rem;
    }
    .consult-note-title {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
    }
    .consult-note ul {
        margin: 0.65rem 0 0;
        padding-left: 1rem;
        color: #64748b;
    }
    @media (max-width: 991px) {
        .consult-prejoin-grid,
        .consult-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 767px) {
        .consult-control-bar {
            align-items: stretch;
        }
        .consult-control-group {
            justify-content: center;
        }
        .consult-control-btn--danger {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="consult-shell">
    <div class="consult-header">
        <div>
            <div class="consult-eyebrow">{{ $eyebrow }}</div>
            <h1 class="consult-title">{{ $title }}</h1>
            <p class="consult-subtitle">{{ $subtitle }}</p>
        </div>
        <div class="consult-header-actions">
            <span id="call-status-badge" class="consult-badge">Preparing devices...</span>
            <span id="call-timer-pill" class="consult-control-pill consult-hidden">
                <i data-lucide="timer" aria-hidden="true"></i>
                <span class="consult-control-text">00:00</span>
            </span>
            <a href="{{ $backUrl }}" class="consult-link">{{ $backLabel }}</a>
        </div>
    </div>

    <div id="prejoin-panel" class="consult-prejoin">
        <div class="consult-prejoin-grid">
            <div class="consult-card">
                <div class="consult-card-head">
                    <div>
                        <div class="consult-card-title">Preview before joining</div>
                        <div class="consult-card-meta">Check your camera, microphone, and selected devices before you enter the room.</div>
                    </div>
                </div>
                <div class="consult-card-body">
                    <div class="consult-video-shell">
                        <video id="prejoin-video" autoplay playsinline muted class="consult-video"></video>
                        <div id="prejoin-avatar-placeholder" class="consult-avatar-placeholder hidden">
                            <div class="consult-avatar-circle">{{ strtoupper(substr($role === 'doctor' ? 'D' : ($consultation->patient_name[0] ?? 'P'), 0, 1)) }}</div>
                            <div class="consult-avatar-name">{{ $role === 'doctor' ? 'Doctor preview' : $consultation->patient_name }}</div>
                            <div class="consult-avatar-note">Camera is off. You can still join with audio only.</div>
                        </div>
                        <div class="consult-video-label">
                            <i data-lucide="sparkles" aria-hidden="true"></i>
                            <span>Pre-join preview</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="consult-prejoin-copy">
                <div class="consult-prejoin-title">Join when everything looks right</div>
                <div class="consult-prejoin-text">Pick your preferred microphone and camera, then join the consultation. The in-call controls below will work like a lightweight Meet-style room.</div>

                <div class="consult-device-grid">
                    <div class="consult-device-field">
                        <label for="audio-input-select">Microphone</label>
                        <select id="audio-input-select">
                            <option value="">Default microphone</option>
                        </select>
                    </div>
                    <div class="consult-device-field">
                        <label for="video-input-select">Camera</label>
                        <select id="video-input-select">
                            <option value="">Default camera</option>
                        </select>
                    </div>
                </div>

                <div class="consult-control-group">
                    <button type="button" id="prejoin-toggle-mic-btn" class="consult-control-btn" aria-pressed="true" title="Mute microphone">
                        <i data-lucide="mic" aria-hidden="true"></i>
                    </button>
                    <button type="button" id="prejoin-toggle-camera-btn" class="consult-control-btn" aria-pressed="true" title="Turn camera off">
                        <i data-lucide="video" aria-hidden="true"></i>
                    </button>
                    <button type="button" id="prejoin-retry-btn" class="consult-control-btn" title="Retry preview devices">
                        <i data-lucide="rotate-ccw" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="consult-prejoin-actions">
                    <button type="button" id="join-call-btn" class="consult-join-btn">
                        <i data-lucide="phone-call" aria-hidden="true"></i>
                        <span>Join call</span>
                    </button>
                    <div id="prejoin-status" class="consult-prejoin-status">Checking your devices...</div>
                </div>
            </div>
        </div>
    </div>

    <div id="consult-main-grid" class="consult-grid consult-hidden">
        <div class="consult-card">
            <div class="consult-card-head">
                <div>
                    <div class="consult-card-title">Live consultation room</div>
                    <div class="consult-card-meta">Room ID: {{ $consultation->uuid }}</div>
                </div>
                <div class="consult-card-meta">Patient: {{ $consultation->patient_name }}</div>
            </div>
            <div class="consult-card-body">
                <div class="consult-room-body">
                    <div class="consult-video-shell">
                        <video id="remote-video" autoplay playsinline class="consult-video"></video>
                        <div id="remote-placeholder" class="consult-placeholder">
                            <div style="font-size:1.1rem;font-weight:800;">Waiting for the other participant</div>
                            <div style="margin-top:0.55rem;font-size:0.92rem;opacity:0.82;">The remote video will appear here once the peer connection is established.</div>
                        </div>
                        <div id="remote-avatar-placeholder" class="consult-avatar-placeholder">
                            <div class="consult-avatar-circle">{{ strtoupper(substr($role === 'doctor' ? ($consultation->patient_name[0] ?? 'P') : 'D', 0, 1)) }}</div>
                            <div class="consult-avatar-name">{{ $role === 'doctor' ? $consultation->patient_name : 'Doctor' }}</div>
                            <div class="consult-avatar-note">Video is off or not available yet.</div>
                        </div>
                        <div class="consult-video-label">
                            <i data-lucide="monitor-up" aria-hidden="true"></i>
                            <span>Remote participant</span>
                        </div>
                    </div>
                    <div class="consult-control-bar">
                        <div class="consult-control-group">
                            <button type="button" id="toggle-mic-btn" class="consult-control-btn" aria-pressed="true" title="Mute microphone">
                                <i data-lucide="mic" aria-hidden="true"></i>
                            </button>
                            <button type="button" id="toggle-camera-btn" class="consult-control-btn" aria-pressed="true" title="Turn camera off">
                                <i data-lucide="video" aria-hidden="true"></i>
                            </button>
                            <button type="button" id="retry-media-btn" class="consult-control-btn" title="Retry device access">
                                <i data-lucide="rotate-ccw" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="consult-control-group">
                            <span id="device-state-pill" class="consult-control-pill">
                                <i data-lucide="badge-check" aria-hidden="true"></i>
                                <span class="consult-control-text">Checking devices...</span>
                            </span>
                            <button type="button" id="end-call-btn" class="consult-control-btn consult-control-btn--danger">
                                <i data-lucide="phone-off" aria-hidden="true"></i>
                                <span>Leave</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="consult-sidebar">
            <div id="device-alert" class="consult-alert"></div>

            <div class="consult-card">
                <div class="consult-card-head">
                    <div class="consult-card-title">Your preview</div>
                </div>
                <div class="consult-card-body">
                    <div class="consult-video-shell consult-video-shell--local">
                        <video id="local-video" autoplay playsinline muted class="consult-video"></video>
                        <div id="local-avatar-placeholder" class="consult-avatar-placeholder hidden">
                            <div class="consult-avatar-circle">{{ strtoupper(substr($role === 'doctor' ? 'D' : ($consultation->patient_name[0] ?? 'P'), 0, 1)) }}</div>
                            <div class="consult-avatar-name">{{ $role === 'doctor' ? 'Doctor' : $consultation->patient_name }}</div>
                            <div class="consult-avatar-note">Your camera is turned off.</div>
                        </div>
                        <div class="consult-video-label">
                            <i data-lucide="user-round" aria-hidden="true"></i>
                            <span>Your preview</span>
                        </div>
                    </div>
                    <div class="consult-preview-note">Use the round controls below the main video to mute yourself or turn your camera on and off during the consultation.</div>
                </div>
            </div>

            <div class="consult-card">
                <div class="consult-card-head">
                    <div class="consult-card-title">Call controls</div>
                </div>
                <div class="consult-card-body">
                    <div class="consult-note">
                        <div class="consult-note-title">Connection notes</div>
                        <ul>
                            <li>This room uses browser-native WebRTC only.</li>
                            <li>Signaling is exchanged through app polling every 2 seconds.</li>
                            <li>Device access falls back gracefully if one input is unavailable.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const role = @json($role);
        const pollUrl = @json(route('consultations.poll', $consultation->uuid));
        const signalUrl = @json(route('consultations.signal', $consultation->uuid));
        const endUrl = @json(route('consultations.end', $consultation->uuid));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const peerConfig = {
            iceServers: @json(config('services.webrtc.ice_servers', [['urls' => ['stun:stun.l.google.com:19302']]])),
        };

        const prejoinPanel = document.getElementById('prejoin-panel');
        const consultMainGrid = document.getElementById('consult-main-grid');
        const prejoinVideo = document.getElementById('prejoin-video');
        const prejoinStatus = document.getElementById('prejoin-status');
        const prejoinRetryBtn = document.getElementById('prejoin-retry-btn');
        const prejoinToggleMicBtn = document.getElementById('prejoin-toggle-mic-btn');
        const prejoinToggleCameraBtn = document.getElementById('prejoin-toggle-camera-btn');
        const joinCallBtn = document.getElementById('join-call-btn');
        const audioInputSelect = document.getElementById('audio-input-select');
        const videoInputSelect = document.getElementById('video-input-select');
        const localVideo = document.getElementById('local-video');
        const remoteVideo = document.getElementById('remote-video');
        const remotePlaceholder = document.getElementById('remote-placeholder');
        const statusBadge = document.getElementById('call-status-badge');
        const deviceAlert = document.getElementById('device-alert');
        const retryMediaBtn = document.getElementById('retry-media-btn');
        const toggleMicBtn = document.getElementById('toggle-mic-btn');
        const toggleCameraBtn = document.getElementById('toggle-camera-btn');
        const endCallBtn = document.getElementById('end-call-btn');
        const deviceStatePill = document.getElementById('device-state-pill');
        const timerPill = document.getElementById('call-timer-pill');
        const localAvatarPlaceholder = document.getElementById('local-avatar-placeholder');
        const remoteAvatarPlaceholder = document.getElementById('remote-avatar-placeholder');
        const prejoinAvatarPlaceholder = document.getElementById('prejoin-avatar-placeholder');

        let peerConnection = null;
        let localStream = null;
        let remoteStream = null;
        let pollHandle = null;
        let pendingCandidates = [];
        let flushHandle = null;
        let remoteDescriptionApplied = false;
        let answerCreated = false;
        let callEnded = false;
        let pollingStarted = false;
        let bootstrapping = false;
        let offered = false;
        let joinedCall = false;
        let currentConsultationStatus = @json($consultation->status);
        let selectedAudioDeviceId = '';
        let selectedVideoDeviceId = '';
        let preferredMediaState = {
            audio: true,
            video: true,
        };
        let timerHandle = null;
        let callStartedAt = null;
        const appliedCandidates = new Set();
        const queuedRemoteCandidates = [];

        function setStatus(label, tone = 'secondary') {
            const tones = {
                secondary: ['#e2e8f0', '#0f172a'],
                info: ['#dbeafe', '#1d4ed8'],
                success: ['#dcfce7', '#166534'],
                warning: ['#fef3c7', '#92400e'],
                danger: ['#fee2e2', '#b91c1c'],
                dark: ['#0f172a', '#ffffff'],
            };
            const [background, color] = tones[tone] || tones.secondary;
            statusBadge.textContent = label;
            statusBadge.style.background = background;
            statusBadge.style.color = color;
        }

        function showDeviceAlert(message) {
            if (!message) {
                deviceAlert.textContent = '';
                deviceAlert.classList.remove('visible');
                return;
            }

            deviceAlert.textContent = message;
            deviceAlert.classList.add('visible');
        }

        function getFriendlyMediaError(error) {
            const name = error?.name || '';

            if (name === 'NotAllowedError' || name === 'PermissionDeniedError') {
                return 'Camera or microphone permission was blocked. Allow access in the browser address bar and retry.';
            }

            if (name === 'NotFoundError' || name === 'DevicesNotFoundError') {
                return 'No usable camera or microphone was found on this device.';
            }

            if (name === 'NotReadableError' || name === 'TrackStartError') {
                return 'Your camera or microphone is already in use by another app. Close the other app and retry.';
            }

            if (name === 'SecurityError') {
                return 'This page must be opened from localhost or HTTPS for browser media access.';
            }

            return 'The browser could not start camera or microphone access. Check permissions and retry.';
        }

        function setPrejoinStatus(message) {
            if (prejoinStatus) {
                prejoinStatus.textContent = message;
            }
        }

        function showJoinedLayout() {
            joinedCall = true;
            prejoinPanel?.classList.add('consult-hidden');
            consultMainGrid?.classList.remove('consult-hidden');
        }

        function startCallTimer() {
            if (timerHandle) {
                return;
            }

            callStartedAt = callStartedAt || Date.now();
            timerPill?.classList.remove('consult-hidden');

            const tick = () => {
                if (!timerPill) {
                    return;
                }

                const elapsedSeconds = Math.max(0, Math.floor((Date.now() - callStartedAt) / 1000));
                const minutes = String(Math.floor(elapsedSeconds / 60)).padStart(2, '0');
                const seconds = String(elapsedSeconds % 60).padStart(2, '0');
                const text = timerPill.querySelector('.consult-control-text');
                if (text) {
                    text.textContent = `${minutes}:${seconds}`;
                }
            };

            tick();
            timerHandle = window.setInterval(tick, 1000);
        }

        function stopCallTimer() {
            if (timerHandle) {
                window.clearInterval(timerHandle);
                timerHandle = null;
            }
        }

        function setPlaceholderVisibility(element, visible) {
            if (!element) {
                return;
            }

            element.classList.toggle('hidden', !visible);
        }

        function updateLocalPreviewPlaceholders() {
            const hasEnabledVideo = !!localStream?.getVideoTracks().some((track) => track.enabled && track.readyState === 'live');
            setPlaceholderVisibility(localAvatarPlaceholder, !hasEnabledVideo && joinedCall);
            setPlaceholderVisibility(prejoinAvatarPlaceholder, !hasEnabledVideo && !joinedCall);
        }

        function updateRemoteVideoState() {
            const remoteHasVideo = !!remoteStream?.getVideoTracks().some((track) => track.readyState === 'live' && !track.muted);
            setPlaceholderVisibility(remoteAvatarPlaceholder, !remoteHasVideo);
            remotePlaceholder?.classList.toggle('hidden', remoteHasVideo);
        }

        function setDeviceState(label, tone = 'ready') {
            if (!deviceStatePill) {
                return;
            }

            const text = deviceStatePill.querySelector('.consult-control-text');
            const tones = {
                ready: ['#ffffff', '#dbe7f3', '#334155'],
                success: ['#dcfce7', '#86efac', '#166534'],
                warning: ['#fef3c7', '#fcd34d', '#92400e'],
                danger: ['#fee2e2', '#fca5a5', '#b91c1c'],
            };
            const [background, border, color] = tones[tone] || tones.ready;

            deviceStatePill.style.background = background;
            deviceStatePill.style.borderColor = border;
            deviceStatePill.style.color = color;
            if (text) {
                text.textContent = label;
            }
        }

        function syncLucideIcons() {
            if (window.refreshLucideIcons) {
                window.refreshLucideIcons();
            } else if (window.lucide?.createIcons) {
                window.lucide.createIcons();
            }
        }

        async function populateDeviceOptions() {
            if (!navigator.mediaDevices?.enumerateDevices) {
                return;
            }

            const devices = await navigator.mediaDevices.enumerateDevices();
            const audioInputs = devices.filter((device) => device.kind === 'audioinput');
            const videoInputs = devices.filter((device) => device.kind === 'videoinput');

            const setOptions = (select, items, selectedValue, fallbackLabel) => {
                if (!select) {
                    return;
                }

                const previous = selectedValue || select.value;
                select.innerHTML = '';

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = fallbackLabel;
                select.appendChild(defaultOption);

                items.forEach((item, index) => {
                    const option = document.createElement('option');
                    option.value = item.deviceId;
                    option.textContent = item.label || `${fallbackLabel} ${index + 1}`;
                    select.appendChild(option);
                });

                select.value = items.some((item) => item.deviceId === previous) ? previous : '';
            };

            setOptions(audioInputSelect, audioInputs, selectedAudioDeviceId, 'Default microphone');
            setOptions(videoInputSelect, videoInputs, selectedVideoDeviceId, 'Default camera');
        }

        async function preparePreview(force = false) {
            if (callEnded) {
                return;
            }

            if (force) {
                stopLocalStream();
            }

            setPrejoinStatus('Checking your devices...');

            try {
                const media = await acquireLocalStream();
                stopLocalStream();
                localStream = media.stream;
                prejoinVideo.srcObject = localStream;
                localVideo.srcObject = localStream;

                if (media.mode === 'none') {
                    setPrejoinStatus('Joining with camera and microphone off.');
                } else if (media.mode === 'video-only') {
                    setPrejoinStatus('Preview ready with camera only.');
                } else if (media.mode === 'audio-only') {
                    setPrejoinStatus('Preview ready with microphone only.');
                } else {
                    setPrejoinStatus('Preview ready. You can join when you are ready.');
                }

                await populateDeviceOptions();
            } catch (error) {
                console.error(error);
                setPrejoinStatus(getFriendlyMediaError(error));
                showDeviceAlert(getFriendlyMediaError(error));
            } finally {
                updateControlAvailability();
                updateLocalPreviewPlaceholders();
            }
        }

        function updateToggleButton(button, options) {
            if (!button) {
                return;
            }

            const {
                active,
                activeLabel,
                inactiveLabel,
                activeIcon,
                inactiveIcon,
                disabled = false,
                unavailableLabel = inactiveLabel,
            } = options;

            button.disabled = disabled;
            button.classList.toggle('consult-control-btn--off', !active || disabled);
            button.setAttribute('aria-pressed', active ? 'true' : 'false');
            button.setAttribute('title', disabled ? unavailableLabel : (active ? activeLabel : inactiveLabel));

            const icon = button.querySelector('i');
            if (icon) {
                icon.setAttribute('data-lucide', active && !disabled ? activeIcon : inactiveIcon);
            }

            syncLucideIcons();
        }

        async function acquireLocalStream() {
            const buildConstraintValue = (kind) => {
                const enabled = preferredMediaState[kind];
                const deviceId = kind === 'audio' ? selectedAudioDeviceId : selectedVideoDeviceId;

                if (!enabled) {
                    return false;
                }

                if (!deviceId) {
                    return true;
                }

                return {
                    deviceId: { exact: deviceId },
                };
            };

            const audioConstraint = buildConstraintValue('audio');
            const videoConstraint = buildConstraintValue('video');

            const attempts = [];

            if (audioConstraint && videoConstraint) {
                attempts.push({
                    constraints: {
                        video: videoConstraint,
                        audio: audioConstraint,
                    },
                    mode: 'video+audio',
                });
            }

            if (videoConstraint) {
                attempts.push({
                    constraints: {
                        video: videoConstraint,
                        audio: false,
                    },
                    mode: 'video-only',
                });
            }

            if (audioConstraint) {
                attempts.push({
                    constraints: {
                        video: false,
                        audio: audioConstraint,
                    },
                    mode: 'audio-only',
                });
            }

            if (attempts.length === 0) {
                return { stream: new MediaStream(), mode: 'none' };
            }

            let lastError = null;

            for (const attempt of attempts) {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia(attempt.constraints);
                    return { stream, mode: attempt.mode };
                } catch (error) {
                    lastError = error;
                }
            }

            throw lastError;
        }

        function stopLocalStream() {
            if (!localStream) {
                return;
            }

            localStream.getTracks().forEach((track) => track.stop());
            localStream = null;
            localVideo.srcObject = null;
            prejoinVideo.srcObject = null;
            updateLocalPreviewPlaceholders();
        }

        function updateControlAvailability() {
            const audioTracks = localStream?.getAudioTracks() || [];
            const videoTracks = localStream?.getVideoTracks() || [];
            const hasAudio = preferredMediaState.audio && !!audioTracks.length;
            const hasVideo = preferredMediaState.video && !!videoTracks.length;
            const isAudioEnabled = preferredMediaState.audio && (!audioTracks.length || audioTracks.some((track) => track.enabled));
            const isVideoEnabled = preferredMediaState.video && (!videoTracks.length || videoTracks.some((track) => track.enabled));

            retryMediaBtn.disabled = callEnded || bootstrapping;
            prejoinRetryBtn.disabled = callEnded || bootstrapping;
            updateToggleButton(toggleMicBtn, {
                active: isAudioEnabled,
                activeLabel: 'Mute microphone',
                inactiveLabel: 'Unmute microphone',
                unavailableLabel: 'Microphone unavailable',
                activeIcon: 'mic',
                inactiveIcon: hasAudio ? 'mic-off' : 'mic-off',
                disabled: !preferredMediaState.audio || (!hasAudio && joinedCall) || callEnded,
            });
            updateToggleButton(toggleCameraBtn, {
                active: isVideoEnabled,
                activeLabel: 'Turn camera off',
                inactiveLabel: 'Turn camera on',
                unavailableLabel: 'Camera unavailable',
                activeIcon: 'video',
                inactiveIcon: hasVideo ? 'video-off' : 'video-off',
                disabled: !preferredMediaState.video || (!hasVideo && joinedCall) || callEnded,
            });
            updateToggleButton(prejoinToggleMicBtn, {
                active: isAudioEnabled,
                activeLabel: 'Mute microphone',
                inactiveLabel: 'Unmute microphone',
                unavailableLabel: 'Microphone unavailable',
                activeIcon: 'mic',
                inactiveIcon: 'mic-off',
                disabled: callEnded,
            });
            updateToggleButton(prejoinToggleCameraBtn, {
                active: isVideoEnabled,
                activeLabel: 'Turn camera off',
                inactiveLabel: 'Turn camera on',
                unavailableLabel: 'Camera unavailable',
                activeIcon: 'video',
                inactiveIcon: 'video-off',
                disabled: callEnded,
            });

            if (bootstrapping) {
                setDeviceState('Checking devices...', 'ready');
            } else if (callEnded) {
                setDeviceState('Call closed', 'danger');
            } else if (hasAudio && hasVideo) {
                setDeviceState('Mic and camera on', 'success');
            } else if (hasAudio || hasVideo) {
                setDeviceState(hasAudio ? 'Mic only' : 'Camera only', 'warning');
            } else {
                setDeviceState('No local media', 'danger');
            }

            updateLocalPreviewPlaceholders();
        }

        function scheduleCandidateFlush() {
            if (flushHandle) {
                return;
            }

            flushHandle = window.setTimeout(async () => {
                flushHandle = null;

                if (!pendingCandidates.length || callEnded) {
                    return;
                }

                const candidates = pendingCandidates.splice(0, pendingCandidates.length);
                await postSignal({ role, ice_candidates: candidates });
            }, 500);
        }

        async function postSignal(payload) {
            const response = await fetch(signalUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(payload),
            });

            if (!response.ok) {
                throw new Error('Unable to update consultation signaling.');
            }

            return response.json();
        }

        async function sendPatientOffer(statusOverride = null) {
            if (!peerConnection || role !== 'patient' || callEnded) {
                return;
            }

            const offer = await peerConnection.createOffer({
                offerToReceiveAudio: true,
                offerToReceiveVideo: true,
            });
            await peerConnection.setLocalDescription(offer);
            offered = true;

            await postSignal({
                role,
                status: statusOverride || (currentConsultationStatus === 'active' ? 'active' : 'pending'),
                sdp_offer: offer.toJSON(),
            });
        }

        async function pollState() {
            if (callEnded) {
                return null;
            }

            const response = await fetch(pollUrl, {
                headers: {
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Unable to poll consultation state.');
            }

            return response.json();
        }

        async function applyRemoteCandidates(candidates) {
            if (!peerConnection) {
                return;
            }

            for (const candidate of candidates) {
                const key = JSON.stringify(candidate);
                if (appliedCandidates.has(key)) {
                    continue;
                }
                if (!remoteDescriptionApplied) {
                    queuedRemoteCandidates.push(candidate);
                    continue;
                }

                try {
                    await peerConnection.addIceCandidate(candidate);
                    appliedCandidates.add(key);
                } catch (error) {
                    console.error('ICE candidate apply failed', error);
                }
            }
        }

        async function flushQueuedRemoteCandidates() {
            if (!peerConnection || !remoteDescriptionApplied || !queuedRemoteCandidates.length) {
                return;
            }

            while (queuedRemoteCandidates.length > 0) {
                const candidate = queuedRemoteCandidates.shift();
                const key = JSON.stringify(candidate);

                if (appliedCandidates.has(key)) {
                    continue;
                }

                try {
                    await peerConnection.addIceCandidate(candidate);
                    appliedCandidates.add(key);
                } catch (error) {
                    console.error('Queued ICE candidate apply failed', error);
                }
            }
        }

        async function handlePolledState(state) {
            if (!state) {
                return;
            }

            currentConsultationStatus = state.status;

            if (state.status === 'completed') {
                finishCall('Call completed', 'dark');
                return;
            }

            if (state.status === 'active') {
                startCallTimer();
            }

            if (state.status === 'rejected') {
                finishCall('Call rejected', 'danger');
                showDeviceAlert(role === 'patient'
                    ? 'The consultation request was rejected by the admin.'
                    : 'This consultation has been rejected.');
                return;
            }

            if (role === 'patient' && state.status === 'accepted' && !state.sdp_answer) {
                setStatus('Doctor accepted. Joining shortly...', 'info');
            }

            if (role === 'patient') {
                if (state.sdp_answer && !remoteDescriptionApplied) {
                    await peerConnection.setRemoteDescription(new RTCSessionDescription(state.sdp_answer));
                    remoteDescriptionApplied = true;
                    setStatus('Doctor connected', 'success');
                    await flushQueuedRemoteCandidates();
                }

                await applyRemoteCandidates(state.ice_candidates_doctor || []);
                return;
            }

            if (state.sdp_offer && !remoteDescriptionApplied) {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(state.sdp_offer));
                remoteDescriptionApplied = true;
                setStatus('Offer received', 'info');
                await flushQueuedRemoteCandidates();
            }

            if (remoteDescriptionApplied && !answerCreated) {
                const answer = await peerConnection.createAnswer();
                await peerConnection.setLocalDescription(answer);
                answerCreated = true;
                await postSignal({
                    role,
                    status: 'active',
                    sdp_answer: answer.toJSON(),
                });
                setStatus('Answer sent', 'success');
            }

            await applyRemoteCandidates(state.ice_candidates_patient || []);
        }

        function finishCall(label, tone) {
            if (callEnded) {
                return;
            }

            callEnded = true;
            setStatus(label, tone);
            stopCallTimer();

            if (pollHandle) {
                window.clearInterval(pollHandle);
                pollHandle = null;
            }

            if (peerConnection) {
                peerConnection.close();
                peerConnection = null;
            }

            stopLocalStream();
            remoteStream = null;
            remoteVideo.srcObject = null;
            updateRemoteVideoState();

            retryMediaBtn.disabled = true;
            prejoinRetryBtn.disabled = true;
            toggleMicBtn.disabled = true;
            toggleCameraBtn.disabled = true;
            prejoinToggleMicBtn.disabled = true;
            prejoinToggleCameraBtn.disabled = true;
            joinCallBtn.disabled = true;
            endCallBtn.disabled = true;
        }

        async function endCall() {
            try {
                await fetch(endUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
            } catch (error) {
                console.error('End call request failed', error);
            } finally {
                finishCall('Call ended', 'dark');
            }
        }

        function ensurePeerConnection() {
            if (peerConnection) {
                return peerConnection;
            }

            peerConnection = new RTCPeerConnection(peerConfig);

            peerConnection.addTransceiver('audio', { direction: 'recvonly' });
            peerConnection.addTransceiver('video', { direction: 'recvonly' });

            peerConnection.addEventListener('icecandidate', (event) => {
                if (!event.candidate) {
                    return;
                }

                pendingCandidates.push(event.candidate.toJSON());
                scheduleCandidateFlush();
            });

            peerConnection.addEventListener('track', (event) => {
                remoteStream = event.streams[0];
                remoteVideo.srcObject = remoteStream;
                event.track.onmute = () => updateRemoteVideoState();
                event.track.onunmute = () => updateRemoteVideoState();
                remoteVideo.onloadedmetadata = () => updateRemoteVideoState();
                remotePlaceholder.classList.add('hidden');
                updateRemoteVideoState();
                setStatus('Peer connection established', 'success');
                startCallTimer();
            });

            peerConnection.addEventListener('connectionstatechange', () => {
                const state = peerConnection.connectionState;

                if (state === 'connected') {
                    setStatus('Live call connected', 'success');
                    startCallTimer();
                } else if (state === 'connecting') {
                    setStatus('Connecting call...', 'info');
                } else if (state === 'disconnected') {
                    setStatus('Connection interrupted. Reconnecting...', 'warning');
                } else if (state === 'failed') {
                    setStatus('Connection failed. Check network and retry.', 'danger');
                    showDeviceAlert('The peers could not establish a direct media path. If this keeps happening across different networks, configure TURN credentials in the environment.');
                } else if (state === 'closed') {
                    setStatus('Call closed', 'dark');
                    stopCallTimer();
                }
            });

            return peerConnection;
        }

        function startPolling() {
            if (pollingStarted || callEnded) {
                return;
            }

            pollingStarted = true;

            pollHandle = window.setInterval(async () => {
                try {
                    const state = await pollState();
                    await handlePolledState(state);
                } catch (error) {
                    console.error(error);
                    setStatus('Polling retrying...', 'warning');
                }
            }, 2000);
        }

        async function bootstrapPeer() {
            if (bootstrapping || callEnded) {
                return;
            }

            bootstrapping = true;
            showJoinedLayout();
            updateControlAvailability();
            showDeviceAlert('');
            setStatus('Preparing devices...', 'secondary');

            try {
                let media = null;
                if (!localStream) {
                    media = await acquireLocalStream();
                    localStream = media.stream;
                    prejoinVideo.srcObject = localStream;
                    localVideo.srcObject = localStream;
                } else {
                    media = {
                        stream: localStream,
                        mode: localStream.getAudioTracks().length && localStream.getVideoTracks().length
                            ? 'video+audio'
                            : (localStream.getVideoTracks().length ? 'video-only' : (localStream.getAudioTracks().length ? 'audio-only' : 'none')),
                    };
                    localVideo.srcObject = localStream;
                }

                ensurePeerConnection();

                const transceivers = peerConnection.getTransceivers();
                localStream.getTracks().forEach((track) => {
                    const transceiver = transceivers.find((t) => t.receiver.track.kind === track.kind);
                    if (transceiver) {
                        transceiver.sender.replaceTrack(track);
                        transceiver.direction = 'sendrecv';
                    } else {
                        peerConnection.addTrack(track, localStream);
                    }
                });

                if (media.mode === 'video-only') {
                    showDeviceAlert('Microphone access is unavailable, so this call will start with video only.');
                    setDeviceState('Camera only', 'warning');
                } else if (media.mode === 'audio-only') {
                    showDeviceAlert('Camera access is unavailable, so this call will start with audio only.');
                    setDeviceState('Mic only', 'warning');
                } else if (media.mode === 'none') {
                    showDeviceAlert('You joined without local camera or microphone. You can retry device access anytime.');
                    setDeviceState('Joined without local media', 'danger');
                } else {
                    setDeviceState('Mic and camera on', 'success');
                }

                updateControlAvailability();

                if (role === 'patient' && !offered) {
                    await sendPatientOffer('pending');
                    setStatus('Offer sent. Waiting for doctor...', 'info');
                } else if (role === 'doctor' && !remoteDescriptionApplied) {
                    setStatus('Waiting for patient offer...', 'info');
                }

                startPolling();

                const initialState = await pollState();
                await handlePolledState(initialState);
            } catch (error) {
                console.error(error);
                showDeviceAlert(getFriendlyMediaError(error));
                setStatus('Joining without local media', 'warning');
                setDeviceState('Joining without local media', 'danger');

                ensurePeerConnection();
                startPolling();

                if (role === 'patient' && !offered) {
                    await sendPatientOffer('pending');
                    setStatus('Offer sent. Waiting for doctor...', 'info');
                } else if (role === 'doctor') {
                    setStatus('Waiting for patient offer...', 'info');
                }

                try {
                    const initialState = await pollState();
                    await handlePolledState(initialState);
                } catch (pollError) {
                    console.error(pollError);
                }

                updateControlAvailability();
            } finally {
                bootstrapping = false;
                updateControlAvailability();
            }
        }

        toggleMicBtn.addEventListener('click', () => {
            preferredMediaState.audio = !preferredMediaState.audio;
            if (localStream?.getAudioTracks().length) {
                localStream.getAudioTracks().forEach((track) => {
                    track.enabled = preferredMediaState.audio;
                });
            }
            updateControlAvailability();
        });

        prejoinToggleMicBtn.addEventListener('click', async () => {
            preferredMediaState.audio = !preferredMediaState.audio;
            if (localStream?.getAudioTracks().length) {
                localStream.getAudioTracks().forEach((track) => {
                    track.enabled = preferredMediaState.audio;
                });
            } else {
                await preparePreview(true);
            }
            updateControlAvailability();
        });

        toggleCameraBtn.addEventListener('click', () => {
            preferredMediaState.video = !preferredMediaState.video;
            if (localStream?.getVideoTracks().length) {
                localStream.getVideoTracks().forEach((track) => {
                    track.enabled = preferredMediaState.video;
                });
            }
            updateControlAvailability();
        });

        prejoinToggleCameraBtn.addEventListener('click', async () => {
            preferredMediaState.video = !preferredMediaState.video;
            if (localStream?.getVideoTracks().length) {
                localStream.getVideoTracks().forEach((track) => {
                    track.enabled = preferredMediaState.video;
                });
            } else {
                await preparePreview(true);
            }
            updateControlAvailability();
        });

        retryMediaBtn.addEventListener('click', () => {
            bootstrapPeer();
        });

        prejoinRetryBtn.addEventListener('click', async () => {
            await preparePreview(true);
        });

        joinCallBtn.addEventListener('click', async () => {
            joinCallBtn.disabled = true;
            setPrejoinStatus('Joining the consultation room...');
            try {
                await bootstrapPeer();
            } finally {
                if (!callEnded) {
                    joinCallBtn.disabled = false;
                }
            }
        });

        audioInputSelect?.addEventListener('change', async (event) => {
            selectedAudioDeviceId = event.target.value || '';
            await preparePreview(true);
        });

        videoInputSelect?.addEventListener('change', async (event) => {
            selectedVideoDeviceId = event.target.value || '';
            await preparePreview(true);
        });

        endCallBtn.addEventListener('click', endCall);

        if (!navigator.mediaDevices?.getUserMedia || !window.RTCPeerConnection) {
            setStatus('Browser not supported', 'danger');
            showDeviceAlert('This browser does not support the required camera, microphone, or WebRTC APIs.');
            retryMediaBtn.disabled = true;
            prejoinRetryBtn.disabled = true;
            toggleMicBtn.disabled = true;
            toggleCameraBtn.disabled = true;
            prejoinToggleMicBtn.disabled = true;
            prejoinToggleCameraBtn.disabled = true;
            joinCallBtn.disabled = true;
            endCallBtn.disabled = true;
            setDeviceState('Browser unsupported', 'danger');
            return;
        }

        setDeviceState('Checking devices...', 'ready');
        updateControlAvailability();
        syncLucideIcons();
        navigator.mediaDevices?.addEventListener?.('devicechange', async () => {
            await populateDeviceOptions();
        });
        preparePreview(true);
    });
</script>
@endpush
