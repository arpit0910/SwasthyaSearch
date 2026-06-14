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
        .consult-grid {
            grid-template-columns: 1fr;
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
            <a href="{{ $backUrl }}" class="consult-link">{{ $backLabel }}</a>
        </div>
    </div>

    <div class="consult-grid">
        <div class="consult-card">
            <div class="consult-card-head">
                <div>
                    <div class="consult-card-title">Live consultation room</div>
                    <div class="consult-card-meta">Room ID: {{ $consultation->uuid }}</div>
                </div>
                <div class="consult-card-meta">Patient: {{ $consultation->patient_name }}</div>
            </div>
            <div class="consult-card-body">
                <div class="consult-video-shell">
                    <video id="remote-video" autoplay playsinline class="consult-video"></video>
                    <div id="remote-placeholder" class="consult-placeholder">
                        <div style="font-size:1.1rem;font-weight:800;">Waiting for the other participant</div>
                        <div style="margin-top:0.55rem;font-size:0.92rem;opacity:0.82;">The remote video will appear here once the peer connection is established.</div>
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
                    </div>
                </div>
            </div>

            <div class="consult-card">
                <div class="consult-card-head">
                    <div class="consult-card-title">Call controls</div>
                </div>
                <div class="consult-card-body">
                    <div class="consult-controls">
                        <button type="button" id="retry-media-btn" class="consult-btn consult-btn--soft">Retry device access</button>
                        <button type="button" id="toggle-mic-btn" class="consult-btn consult-btn--soft">Mute microphone</button>
                        <button type="button" id="toggle-camera-btn" class="consult-btn consult-btn--soft">Turn camera off</button>
                        <button type="button" id="end-call-btn" class="consult-btn consult-btn--danger">End call</button>
                    </div>

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

        const localVideo = document.getElementById('local-video');
        const remoteVideo = document.getElementById('remote-video');
        const remotePlaceholder = document.getElementById('remote-placeholder');
        const statusBadge = document.getElementById('call-status-badge');
        const deviceAlert = document.getElementById('device-alert');
        const retryMediaBtn = document.getElementById('retry-media-btn');
        const toggleMicBtn = document.getElementById('toggle-mic-btn');
        const toggleCameraBtn = document.getElementById('toggle-camera-btn');
        const endCallBtn = document.getElementById('end-call-btn');

        let peerConnection = null;
        let localStream = null;
        let pollHandle = null;
        let pendingCandidates = [];
        let flushHandle = null;
        let remoteDescriptionApplied = false;
        let answerCreated = false;
        let callEnded = false;
        let pollingStarted = false;
        let bootstrapping = false;
        let offered = false;
        const appliedCandidates = new Set();

        const peerConfig = { iceServers: [] };

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

        async function acquireLocalStream() {
            const attempts = [
                {
                    constraints: {
                        video: {
                            width: { ideal: 1280 },
                            height: { ideal: 720 },
                            frameRate: { ideal: 24, max: 30 },
                            facingMode: 'user',
                        },
                        audio: {
                            echoCancellation: true,
                            noiseSuppression: true,
                            autoGainControl: true,
                        },
                    },
                    mode: 'video+audio',
                },
                {
                    constraints: {
                        video: {
                            width: { ideal: 960 },
                            height: { ideal: 540 },
                            frameRate: { ideal: 20, max: 24 },
                            facingMode: 'user',
                        },
                        audio: false,
                    },
                    mode: 'video-only',
                },
                {
                    constraints: {
                        video: false,
                        audio: {
                            echoCancellation: true,
                            noiseSuppression: true,
                            autoGainControl: true,
                        },
                    },
                    mode: 'audio-only',
                },
            ];

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
        }

        function updateControlAvailability() {
            const hasAudio = !!localStream?.getAudioTracks().length;
            const hasVideo = !!localStream?.getVideoTracks().length;

            retryMediaBtn.disabled = callEnded || bootstrapping;
            toggleMicBtn.disabled = !hasAudio || callEnded;
            toggleCameraBtn.disabled = !hasVideo || callEnded;

            toggleMicBtn.textContent = hasAudio ? 'Mute microphone' : 'Microphone unavailable';
            toggleCameraBtn.textContent = hasVideo ? 'Turn camera off' : 'Camera unavailable';
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

                appliedCandidates.add(key);

                try {
                    await peerConnection.addIceCandidate(candidate);
                } catch (error) {
                    console.error('ICE candidate apply failed', error);
                }
            }
        }

        async function handlePolledState(state) {
            if (!state) {
                return;
            }

            if (state.status === 'completed') {
                finishCall('Call completed', 'dark');
                return;
            }

            if (role === 'patient') {
                if (state.sdp_answer && !remoteDescriptionApplied) {
                    await peerConnection.setRemoteDescription(new RTCSessionDescription(state.sdp_answer));
                    remoteDescriptionApplied = true;
                    setStatus('Doctor connected', 'success');
                }

                await applyRemoteCandidates(state.ice_candidates_doctor || []);
                return;
            }

            if (state.sdp_offer && !remoteDescriptionApplied) {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(state.sdp_offer));
                remoteDescriptionApplied = true;
                setStatus('Offer received', 'info');
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

            if (pollHandle) {
                window.clearInterval(pollHandle);
                pollHandle = null;
            }

            if (peerConnection) {
                peerConnection.close();
                peerConnection = null;
            }

            stopLocalStream();

            retryMediaBtn.disabled = true;
            toggleMicBtn.disabled = true;
            toggleCameraBtn.disabled = true;
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

            peerConnection.addEventListener('icecandidate', (event) => {
                if (!event.candidate) {
                    return;
                }

                pendingCandidates.push(event.candidate.toJSON());
                scheduleCandidateFlush();
            });

            peerConnection.addEventListener('track', (event) => {
                remoteVideo.srcObject = event.streams[0];
                remotePlaceholder.classList.add('hidden');
                setStatus('Peer connection established', 'success');
            });

            peerConnection.addEventListener('connectionstatechange', () => {
                const state = peerConnection.connectionState;

                if (state === 'connected') {
                    setStatus('Live call connected', 'success');
                } else if (state === 'connecting') {
                    setStatus('Connecting call...', 'info');
                } else if (state === 'disconnected') {
                    setStatus('Connection interrupted. Reconnecting...', 'warning');
                } else if (state === 'failed') {
                    setStatus('Connection interrupted. Retry device access if needed.', 'warning');
                } else if (state === 'closed') {
                    setStatus('Call closed', 'dark');
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
            updateControlAvailability();
            showDeviceAlert('');
            setStatus('Preparing devices...', 'secondary');

            try {
                const media = await acquireLocalStream();
                stopLocalStream();
                localStream = media.stream;
                localVideo.srcObject = localStream;

                ensurePeerConnection();

                const senders = peerConnection.getSenders();
                localStream.getTracks().forEach((track) => {
                    const sender = senders.find((item) => item.track && item.track.kind === track.kind);
                    if (sender) {
                        sender.replaceTrack(track);
                    } else {
                        peerConnection.addTrack(track, localStream);
                    }
                });

                if (media.mode === 'video-only') {
                    showDeviceAlert('Microphone access is unavailable, so this call will start with video only.');
                } else if (media.mode === 'audio-only') {
                    showDeviceAlert('Camera access is unavailable, so this call will start with audio only.');
                }

                updateControlAvailability();

                if (role === 'patient' && !offered) {
                    const offer = await peerConnection.createOffer({
                        offerToReceiveAudio: true,
                        offerToReceiveVideo: true,
                    });
                    await peerConnection.setLocalDescription(offer);
                    await postSignal({
                        role,
                        status: 'pending',
                        sdp_offer: offer.toJSON(),
                    });
                    offered = true;
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
                setStatus('Device access needed', 'danger');
                updateControlAvailability();
            } finally {
                bootstrapping = false;
                updateControlAvailability();
            }
        }

        toggleMicBtn.addEventListener('click', () => {
            if (!localStream?.getAudioTracks().length) {
                return;
            }

            const enabled = localStream.getAudioTracks().some((track) => track.enabled);
            localStream.getAudioTracks().forEach((track) => {
                track.enabled = !enabled;
            });
            toggleMicBtn.textContent = enabled ? 'Unmute microphone' : 'Mute microphone';
        });

        toggleCameraBtn.addEventListener('click', () => {
            if (!localStream?.getVideoTracks().length) {
                return;
            }

            const enabled = localStream.getVideoTracks().some((track) => track.enabled);
            localStream.getVideoTracks().forEach((track) => {
                track.enabled = !enabled;
            });
            toggleCameraBtn.textContent = enabled ? 'Turn camera on' : 'Turn camera off';
        });

        retryMediaBtn.addEventListener('click', () => {
            bootstrapPeer();
        });

        endCallBtn.addEventListener('click', endCall);

        if (!navigator.mediaDevices?.getUserMedia || !window.RTCPeerConnection) {
            setStatus('Browser not supported', 'danger');
            showDeviceAlert('This browser does not support the required camera, microphone, or WebRTC APIs.');
            retryMediaBtn.disabled = true;
            toggleMicBtn.disabled = true;
            toggleCameraBtn.disabled = true;
            endCallBtn.disabled = true;
            return;
        }

        updateControlAvailability();
        bootstrapPeer();
    });
</script>
@endpush
