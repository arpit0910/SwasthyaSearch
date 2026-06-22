@php
    $isHi = \App\Helpers\LocaleHelper::current() === 'hi';
    if ($isHi) {
        if ($role === 'doctor') {
            $eyebrow = 'व्यवस्थापक कक्ष';
            $title = 'रोगी परामर्श में शामिल हों';
            $subtitle = 'रोगी के प्रस्ताव का उत्तर दें और कॉल पूरा होने तक इस स्क्रीन पर बने रहें।';
            $backLabel = 'सूची में वापस जाएं';
        } else {
            $eyebrow = 'रोगी कक्ष';
            $title = 'आपका वीडियो परामर्श तैयार है';
            $subtitle = 'इस पृष्ठ पर बने रहें जब तक कि डॉक्टर व्यवस्थापक डैशबोर्ड से शामिल न हो जाएं।';
            $backLabel = 'नया परामर्श';
        }
    }
@endphp
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
    #prejoin-video,
    #local-video {
        transform: scaleX(-1);
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
        /* Floating Local Preview on Mobile */
        .consult-sidebar .consult-card:first-of-type {
            position: fixed;
            bottom: 5.5rem;
            right: 1rem;
            width: 130px;
            height: 97px;
            z-index: 100;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            border-radius: 1rem;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.8);
            background: #020617;
            transition: opacity 0.3s ease;
        }
        .consult-sidebar .consult-card:first-of-type:active,
        .consult-sidebar .consult-card:first-of-type:hover {
            opacity: 0.3;
        }
        .consult-sidebar .consult-card:first-of-type .consult-card-head,
        .consult-sidebar .consult-card:first-of-type .consult-preview-note {
            display: none !important;
        }
        .consult-sidebar .consult-card:first-of-type .consult-card-body {
            padding: 0 !important;
            width: 100%;
            height: 100%;
        }
        .consult-sidebar .consult-card:first-of-type .consult-video-shell--local {
            width: 100%;
            height: 100%;
            border-radius: 0;
            aspect-ratio: auto;
        }
        .consult-sidebar .consult-card:first-of-type .consult-video-label {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
            left: 0.4rem;
            bottom: 0.4rem;
        }
        .consult-sidebar .consult-card:first-of-type .consult-avatar-circle {
            width: 2.5rem;
            height: 2.5rem;
            font-size: 0.9rem;
        }
        .consult-sidebar .consult-card:first-of-type .consult-avatar-name,
        .consult-sidebar .consult-card:first-of-type .consult-avatar-note {
            display: none !important;
        }
    }
    @media (max-width: 767px) {
        .consult-chat-input {
            font-size: 16px !important;
        }
    }
    @media (max-width: 576px) {
        .consult-shell {
            gap: 1rem;
        }
        .consult-title {
            font-size: 1.5rem;
        }
        .consult-subtitle {
            font-size: 0.85rem;
        }
        .consult-prejoin {
            padding: 1rem;
            border-radius: 1.25rem;
        }
        .consult-prejoin-title {
            font-size: 1.15rem;
        }
        .consult-prejoin-text {
            font-size: 0.88rem;
        }
        .consult-badge, .consult-link {
            font-size: 0.8rem;
            min-height: 2.2rem;
            padding: 0.4rem 0.8rem;
        }
        .consult-control-bar {
            padding: 0.6rem;
            gap: 0.4rem;
            border-radius: 1rem;
        }
        .consult-control-group {
            gap: 0.4rem;
        }
        .consult-control-btn {
            width: 2.7rem;
            height: 2.7rem;
        }
        .consult-control-btn svg {
            width: 0.95rem;
            height: 0.95rem;
        }
        .consult-control-btn--danger {
            width: 2.7rem !important;
            min-width: 2.7rem !important;
            padding: 0 !important;
            justify-content: center;
        }
        .consult-control-btn--danger span {
            display: none !important;
        }
        .consult-control-pill {
            padding: 0.4rem 0.6rem;
            min-height: 2.2rem;
            font-size: 0.75rem;
            border-radius: 999px;
        }
        .consult-control-text {
            font-size: 0.75rem;
        }
    }

    /* Chat styling */
    .consult-chat-messages {
        height: 250px;
        overflow-y: auto;
        padding: 0.75rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        background: #f8fafc;
        margin-bottom: 0.75rem;
    }
    .consult-chat-message {
        display: flex;
        flex-direction: column;
        max-width: 85%;
        padding: 0.55rem 0.75rem;
        border-radius: 0.75rem;
        font-size: 0.9rem;
        line-height: 1.4;
    }
    .consult-chat-message--local {
        align-self: flex-end;
        background: #0f766e;
        color: #ffffff;
        border-bottom-right-radius: 0.15rem;
    }
    .consult-chat-message--remote {
        align-self: flex-start;
        background: #e2e8f0;
        color: #0f172a;
        border-bottom-left-radius: 0.15rem;
    }
    .consult-chat-meta {
        font-size: 0.72rem;
        margin-bottom: 0.15rem;
        font-weight: 700;
        opacity: 0.85;
    }
    .consult-chat-text {
        word-break: break-word;
    }
    .consult-chat-input-wrapper {
        display: flex;
        gap: 0.5rem;
    }
    .consult-chat-input {
        flex: 1;
        min-height: 2.5rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        color: #0f172a;
        background: #ffffff;
    }
    .consult-chat-send-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        border: 0;
        background: #0f766e;
        color: #ffffff;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .consult-chat-send-btn:hover {
        background: #0d9488;
    }
    .consult-chat-send-btn svg {
        width: 1.1rem;
        height: 1.1rem;
    }
    .consult-chat-disclaimer {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.5rem;
        text-align: center;
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
            <span id="call-status-badge" class="consult-badge">{{ $isHi ? 'उपकरण तैयार किए जा रहे हैं...' : 'Preparing devices...' }}</span>
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
                        <div class="consult-card-title">{{ $isHi ? 'शामिल होने से पहले पूर्वावलोकन' : 'Preview before joining' }}</div>
                        <div class="consult-card-meta">{{ $isHi ? 'कमरे में प्रवेश करने से पहले अपने कैमरे, माइक्रोफ़ोन और चयनित उपकरणों की जाँच करें।' : 'Check your camera, microphone, and selected devices before you enter the room.' }}</div>
                    </div>
                </div>
                <div class="consult-card-body">
                    <div class="consult-video-shell">
                        <video id="prejoin-video" autoplay playsinline muted class="consult-video"></video>
                        <div id="prejoin-avatar-placeholder" class="consult-avatar-placeholder hidden">
                            <div class="consult-avatar-circle">{{ strtoupper(substr($role === 'doctor' ? 'D' : ($consultation->patient_name[0] ?? 'P'), 0, 1)) }}</div>
                            <div class="consult-avatar-name">{{ $role === 'doctor' ? ($isHi ? 'डॉक्टर पूर्वावलोकन' : 'Doctor preview') : $consultation->patient_name }}</div>
                            <div class="consult-avatar-note">{{ $isHi ? 'कैमरा बंद है। आप अभी भी केवल ऑडियो के साथ शामिल हो सकते हैं।' : 'Camera is off. You can still join with audio only.' }}</div>
                        </div>
                        <div class="consult-video-label">
                            <i data-lucide="sparkles" aria-hidden="true"></i>
                            <span>{{ $isHi ? 'पूर्व-जॉइन पूर्वावलोकन' : 'Pre-join preview' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="consult-prejoin-copy">
                <div class="consult-prejoin-title">{{ $isHi ? 'जब सब कुछ ठीक लगे तो शामिल हों' : 'Join when everything looks right' }}</div>
                <div class="consult-prejoin-text">{{ $isHi ? 'अपना पसंदीदा माइक्रोफ़ोन और कैमरा चुनें, फिर परामर्श में शामिल हों। नीचे दिए गए इन-कॉल नियंत्रण एक हल्के Meet-शैली के कमरे की तरह काम करेंगे।' : 'Pick your preferred microphone and camera, then join the consultation. The in-call controls below will work like a lightweight Meet-style room.' }}</div>

                <div class="consult-device-grid">
                    <div class="consult-device-field">
                        <label for="audio-input-select">{{ $isHi ? 'माइक्रोफ़ोन' : 'Microphone' }}</label>
                        <select id="audio-input-select">
                            <option value="">{{ $isHi ? 'डिफ़ॉल्ट माइक्रोफ़ोन' : 'Default microphone' }}</option>
                        </select>
                    </div>
                    <div class="consult-device-field">
                        <label for="video-input-select">{{ $isHi ? 'कैमरा' : 'Camera' }}</label>
                        <select id="video-input-select">
                            <option value="">{{ $isHi ? 'डिफ़ॉल्ट कैमरा' : 'Default camera' }}</option>
                        </select>
                    </div>
                </div>

                <div class="consult-control-group">
                    <button type="button" id="prejoin-toggle-mic-btn" class="consult-control-btn" aria-pressed="true" title="{{ $isHi ? 'माइक्रोफ़ोन बंद करें' : 'Mute microphone' }}">
                        <i data-lucide="mic" aria-hidden="true"></i>
                    </button>
                    <button type="button" id="prejoin-toggle-camera-btn" class="consult-control-btn" aria-pressed="true" title="{{ $isHi ? 'कैमरा बंद करें' : 'Turn camera off' }}">
                        <i data-lucide="video" aria-hidden="true"></i>
                    </button>
                    <button type="button" id="prejoin-retry-btn" class="consult-control-btn" title="{{ $isHi ? 'पूर्वावलोकन उपकरणों का पुनः प्रयास करें' : 'Retry preview devices' }}">
                        <i data-lucide="rotate-ccw" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="consult-prejoin-actions">
                    <button type="button" id="join-call-btn" class="consult-join-btn">
                        <i data-lucide="phone-call" aria-hidden="true"></i>
                        <span>{{ $isHi ? 'कॉल में शामिल हों' : 'Join call' }}</span>
                    </button>
                    <div id="prejoin-status" class="consult-prejoin-status">{{ $isHi ? 'जब तक आप पूर्वावलोकन शुरू नहीं करते या कॉल में शामिल नहीं होते, तब तक कैमरा और माइक्रोफ़ोन बंद रहेंगे।' : 'Camera and microphone stay off until you start preview or join the call.' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div id="consult-main-grid" class="consult-grid consult-hidden">
        <div class="consult-card">
            <div class="consult-card-head">
                <div>
                    <div class="consult-card-title">{{ $isHi ? 'लाइव परामर्श कक्ष' : 'Live consultation room' }}</div>
                    <div class="consult-card-meta">{{ $isHi ? 'रूम आईडी' : 'Room ID' }}: {{ $consultation->uuid }}</div>
                </div>
                <div class="consult-card-meta">{{ $isHi ? 'मरीज' : 'Patient' }}: {{ $consultation->patient_name }}</div>
            </div>
            <div class="consult-card-body">
                <div class="consult-room-body">
                    <div class="consult-video-shell">
                        <video id="remote-video" autoplay playsinline class="consult-video"></video>
                        <div id="remote-placeholder" class="consult-placeholder">
                            <div style="font-size:1.1rem;font-weight:800;">{{ $isHi ? 'दूसरे प्रतिभागी की प्रतीक्षा की जा रही है' : 'Waiting for the other participant' }}</div>
                            <div style="margin-top:0.55rem;font-size:0.92rem;opacity:0.82;">{{ $isHi ? 'पीयर कनेक्शन स्थापित होने के बाद दूसरा वीडियो यहाँ दिखाई देगा।' : 'The remote video will appear here once the peer connection is established.' }}</div>
                        </div>
                        <div id="remote-avatar-placeholder" class="consult-avatar-placeholder">
                            <div class="consult-avatar-circle">{{ strtoupper(substr($role === 'doctor' ? ($consultation->patient_name[0] ?? 'P') : 'D', 0, 1)) }}</div>
                            <div class="consult-avatar-name">{{ $role === 'doctor' ? $consultation->patient_name : ($isHi ? 'डॉक्टर' : 'Doctor') }}</div>
                            <div class="consult-avatar-note">{{ $isHi ? 'वीडियो बंद है या अभी उपलब्ध नहीं है।' : 'Video is off or not available yet.' }}</div>
                        </div>
                        <div class="consult-video-label">
                            <i data-lucide="monitor-up" aria-hidden="true"></i>
                            <span>{{ $isHi ? 'रिमोट प्रतिभागी' : 'Remote participant' }}</span>
                        </div>
                    </div>
                    <div class="consult-control-bar">
                        <div class="consult-control-group">
                            <button type="button" id="toggle-mic-btn" class="consult-control-btn" aria-pressed="true" title="{{ $isHi ? 'माइक्रोफ़ोन बंद करें' : 'Mute microphone' }}">
                                <i data-lucide="mic" aria-hidden="true"></i>
                            </button>
                            <button type="button" id="toggle-camera-btn" class="consult-control-btn" aria-pressed="true" title="{{ $isHi ? 'कैमरा बंद करें' : 'Turn camera off' }}">
                                <i data-lucide="video" aria-hidden="true"></i>
                            </button>
                            <button type="button" id="retry-media-btn" class="consult-control-btn" title="{{ $isHi ? 'डिवाइस एक्सेस का पुनः प्रयास करें' : 'Retry device access' }}">
                                <i data-lucide="rotate-ccw" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="consult-control-group">
                            <span id="device-state-pill" class="consult-control-pill">
                                <i data-lucide="badge-check" aria-hidden="true"></i>
                                <span class="consult-control-text">{{ $isHi ? 'उपकरणों की जाँच की जा रही है...' : 'Checking devices...' }}</span>
                            </span>
                            <button type="button" id="end-call-btn" class="consult-control-btn consult-control-btn--danger">
                                <i data-lucide="phone-off" aria-hidden="true"></i>
                                <span>{{ $isHi ? 'छोड़ें' : 'Leave' }}</span>
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
                    <div class="consult-card-title">{{ $isHi ? 'आपका पूर्वावलोकन' : 'Your preview' }}</div>
                </div>
                <div class="consult-card-body">
                    <div class="consult-video-shell consult-video-shell--local">
                        <video id="local-video" autoplay playsinline muted class="consult-video"></video>
                        <div id="local-avatar-placeholder" class="consult-avatar-placeholder hidden">
                            <div class="consult-avatar-circle">{{ strtoupper(substr($role === 'doctor' ? 'D' : ($consultation->patient_name[0] ?? 'P'), 0, 1)) }}</div>
                            <div class="consult-avatar-name">{{ $role === 'doctor' ? ($isHi ? 'डॉक्टर' : 'Doctor') : $consultation->patient_name }}</div>
                            <div class="consult-avatar-note">{{ $isHi ? 'आपका कैमरा बंद है।' : 'Your camera is turned off.' }}</div>
                        </div>
                        <div class="consult-video-label">
                            <i data-lucide="user-round" aria-hidden="true"></i>
                            <span>{{ $isHi ? 'आपका पूर्वावलोकन' : 'Your preview' }}</span>
                        </div>
                    </div>
                    <div class="consult-preview-note">{{ $isHi ? 'खुद को म्यूट करने या कॉल के दौरान कैमरे को चालू/बंद करने के लिए मुख्य वीडियो के नीचे दिए गए गोल नियंत्रणों का उपयोग करें।' : 'Use the round controls below the main video to mute yourself or turn your camera on and off during the consultation.' }}</div>
                </div>
            </div>

            <div class="consult-card">
                <div class="consult-card-head">
                    <div class="consult-card-title">{{ $isHi ? 'इन-कॉल संदेश' : 'In-call messages' }}</div>
                </div>
                <div class="consult-card-body">
                    <div id="chat-messages-container" class="consult-chat-messages">
                        <!-- Messages will be dynamically rendered here -->
                    </div>
                    <div class="consult-chat-input-wrapper">
                        <input type="text" id="chat-message-input" class="consult-chat-input" placeholder="{{ $isHi ? 'सभी को एक संदेश भेजें' : 'Send a message to everyone' }}" maxlength="1000">
                        <button type="button" id="chat-send-btn" class="consult-chat-send-btn" title="{{ $isHi ? 'संदेश भेजें' : 'Send message' }}">
                            <i data-lucide="send" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="consult-chat-disclaimer">
                        {{ $isHi ? 'संदेश केवल कॉल में शामिल लोगों द्वारा ही देखे जा सकते हैं और कॉल समाप्त होने पर हटा दिए जाते हैं।' : 'Messages can only be seen by people in the call and are deleted when the call ends.' }}
                    </div>
                </div>
            </div>

            <div class="consult-card">
                <div class="consult-card-head">
                    <div class="consult-card-title">{{ $isHi ? 'कॉल नियंत्रण' : 'Call controls' }}</div>
                </div>
                <div class="consult-card-body">
                    <div class="consult-note">
                        <div class="consult-note-title">{{ $isHi ? 'कनेक्शन नोट्स' : 'Connection notes' }}</div>
                        <ul>
                            @if($isHi)
                                <li>यह कमरा केवल ब्राउज़र-नेटिव WebRTC का उपयोग करता है।</li>
                                <li>सिगनलिंग का आदान-प्रदान हर 2 सेकंड में ऐप पोलिंग के माध्यम से किया जाता है।</li>
                                <li>यदि कोई एक इनपुट अनुपलब्ध है, तो डिवाइस एक्सेस शालीनता से वापस आ जाता है।</li>
                                <li>एक वास्तविक एंड-टू-एंड परीक्षण के लिए, दो भौतिक उपकरणों का उपयोग करें। एक कंप्यूटर पर दो टैब अक्सर प्रतिस्पर्धा करते हैं।</li>
                                <li>यदि रोगी और व्यवस्थापक अलग नेटवर्क पर हैं, तो Meet की तरह विश्वसनीय बनाने के लिए .env में TURN जोड़ें।</li>
                            @else
                                <li>This room uses browser-native WebRTC only.</li>
                                <li>Signaling is exchanged through app polling every 2 seconds.</li>
                                <li>Device access falls back gracefully if one input is unavailable.</li>
                                <li>For a real end-to-end test, use two physical devices. Two tabs or profiles on one computer often compete for the same camera and microphone.</li>
                                <li>If patient and admin are on different networks, add TURN credentials in `.env` to make the call reliable like Meet.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $hasTurnServer = false;

    foreach (config('services.webrtc.ice_servers', []) as $server) {
        foreach ((array) ($server['urls'] ?? []) as $url) {
            if (str_starts_with((string) $url, 'turn:') || str_starts_with((string) $url, 'turns:')) {
                $hasTurnServer = true;
                break 2;
            }
        }
    }
@endphp

@push('scripts')
@php
    $translations = [
        'preparingDevices' => $isHi ? 'उपकरण तैयार किए जा रहे हैं...' : 'Preparing devices...',
        'checkingDevices' => $isHi ? 'उपकरणों की जाँच की जा रही है...' : 'Checking devices...',
        'checkingYourDevices' => $isHi ? 'आपके उपकरणों की जाँच की जा रही है...' : 'Checking your devices...',
        'joiningCameraMicOff' => $isHi ? 'कैमरा और माइक्रोफ़ोन बंद के साथ शामिल हो रहे हैं।' : 'Joining with camera and microphone off.',
        'previewCameraOnly' => $isHi ? 'केवल कैमरे के साथ पूर्वावलोकन तैयार है।' : 'Preview ready with camera only.',
        'previewMicOnly' => $isHi ? 'केवल माइक्रोफ़ोन के साथ पूर्वावलोकन तैयार है।' : 'Preview ready with microphone only.',
        'previewReady' => $isHi ? 'पूर्वावलोकन तैयार है। जब आप तैयार हों तो शामिल हो सकते हैं।' : 'Preview ready. You can join when you are ready.',
        'previewStopped' => $isHi ? 'आपके शामिल होने तक कैमरा और माइक्रोफ़ोन खाली रखने के लिए पूर्वावलोकन रोक दिया गया है।' : 'Preview stopped to keep your camera and microphone free until you join.',
        'muteMic' => $isHi ? 'माइक्रोफ़ोन बंद करें' : 'Mute microphone',
        'unmuteMic' => $isHi ? 'माइक्रोफ़ोन चालू करें' : 'Unmute microphone',
        'micUnavailable' => $isHi ? 'माइक्रोफ़ोन अनुपलब्ध' : 'Microphone unavailable',
        'turnCameraOff' => $isHi ? 'कैमरा बंद करें' : 'Turn camera off',
        'turnCameraOn' => $isHi ? 'कैमरा चालू करें' : 'Turn camera on',
        'cameraUnavailable' => $isHi ? 'कैमरा अनुपलब्ध' : 'Camera unavailable',
        'callClosed' => $isHi ? 'कॉल बंद' : 'Call closed',
        'micCameraOn' => $isHi ? 'माइक्रोफ़ोन और कैमरा चालू' : 'Mic and camera on',
        'micOnly' => $isHi ? 'केवल माइक्रोफ़ोन' : 'Mic only',
        'cameraOnly' => $isHi ? 'केवल कैमरा' : 'Camera only',
        'noLocalMedia' => $isHi ? 'कोई स्थानीय मीडिया नहीं' : 'No local media',
        'joinedWithoutLocalMedia' => $isHi ? 'बिना स्थानीय मीडिया के शामिल हुए' : 'Joined without local media',
        'joiningWithoutLocalMedia' => $isHi ? 'बिना स्थानीय मीडिया के शामिल हो रहे हैं' : 'Joining without local media',
        'errUpdateSignaling' => $isHi ? 'परामर्श सिगनलिंग को अपडेट करने में असमर्थ।' : 'Unable to update consultation signaling.',
        'errPollState' => $isHi ? 'परामर्श स्थिति पोल करने में असमर्थ।' : 'Unable to poll consultation state.',
        'callCompleted' => $isHi ? 'कॉल पूरी हुई' : 'Call completed',
        'callRejected' => $isHi ? 'कॉल अस्वीकार की गई' : 'Call rejected',
        'callEnded' => $isHi ? 'कॉल समाप्त' : 'Call ended',
        'patientRejectedByAdmin' => $isHi ? 'व्यवस्थापक द्वारा परामर्श अनुरोध अस्वीकार कर दिया गया था।' : 'The consultation request was rejected by the admin.',
        'consultationRejected' => $isHi ? 'यह परामर्श अस्वीकार कर दिया गया है।' : 'This consultation has been rejected.',
        'doctorAccepted' => $isHi ? 'डॉक्टर ने स्वीकार कर लिया। जल्द ही शामिल हो रहे हैं...' : 'Doctor accepted. Joining shortly...',
        'doctorConnected' => $isHi ? 'डॉक्टर जुड़े' : 'Doctor connected',
        'answerSent' => $isHi ? 'उत्तर भेजा गया' : 'Answer sent',
        'waitingForDoctor' => $isHi ? 'प्रस्ताव भेजा गया। डॉक्टर की प्रतीक्षा की जा रही है...' : 'Offer sent. Waiting for doctor...',
        'waitingForPatient' => $isHi ? 'रोगी के प्रस्ताव की प्रतीक्षा की जा रही है...' : 'Waiting for patient offer...',
        'offerReceived' => $isHi ? 'प्रस्ताव प्राप्त हुआ' : 'Offer received',
        'peerConnectionEstablished' => $isHi ? 'पीयर कनेक्शन स्थापित' : 'Peer connection established',
        'liveCallConnected' => $isHi ? 'लाइव कॉल कनेक्टेड' : 'Live call connected',
        'connectingCall' => $isHi ? 'कॉल कनेक्ट की जा रही है...' : 'Connecting call...',
        'connectionInterrupted' => $isHi ? 'कनेक्शन बाधित। पुनः कनेक्ट किया जा रहा है...' : 'Connection interrupted. Reconnecting...',
        'connectionFailed' => $isHi ? 'कनेक्शन विफल। नेटवर्क की जाँच करें और पुनः प्रयास करें।' : 'Connection failed. Check network and retry.',
        'peerMediaFailedWithTurn' => $isHi ? 'पीयर एक स्थिर मीडिया पथ स्थापित नहीं कर सके। एक बार पुनः प्रयास करें और फिर ब्राउज़र अनुमतियों और नेटवर्क या फ़ायरवॉल नियमों दोनों को सत्यापित करें।' : 'The peers could not establish a stable media path. Retry once and then verify both browser permissions and network or firewall rules.',
        'peerMediaFailedWithoutTurn' => $isHi ? 'पीयर सीधा मीडिया पथ स्थापित नहीं कर सके। वातावरण में TURN क्रेडेंशियल जोड़ें ताकि कॉल विभिन्न नेटवर्क पर विश्वसनीय रूप से काम करें।' : 'The peers could not establish a direct media path. Add TURN credentials in the environment so calls work reliably across different networks.',
        'pollingRetrying' => $isHi ? 'पोलिंग पुनः प्रयास की जा रही है...' : 'Polling retrying...',
        'micUnavailableStartVideoOnly' => $isHi ? 'माइक्रोफ़ोन एक्सेस अनुपलब्ध है, इसलिए यह कॉल केवल वीडियो के साथ शुरू होगी।' : 'Microphone access is unavailable, so this call will start with video only.',
        'cameraUnavailableStartAudioOnly' => $isHi ? 'कैमरा एक्सेस अनुपलब्ध है, इसलिए यह कॉल केवल ऑडियो के साथ शुरू होगी।' : 'Camera access is unavailable, so this call will start with audio only.',
        'joinedNoMediaRetry' => $isHi ? 'आप स्थानीय कैमरे या माइक्रोफ़ोन के बिना शामिल हुए। आप किसी भी समय डिवाइस एक्सेस का पुनः प्रयास कर सकते हैं।' : 'You joined without local camera or microphone. You can retry device access anytime.',
        'joiningConsultationRoom' => $isHi ? 'परामर्श कक्ष में शामिल हो रहे हैं...' : 'Joining the consultation room...',
        'you' => $isHi ? 'आप' : 'You',
        'browserNotSupported' => $isHi ? 'ब्राउज़र समर्थित नहीं है' : 'Browser not supported',
        'webrtcNotSupported' => $isHi ? 'यह ब्राउज़र आवश्यक कैमरा, माइक्रोफ़ोन या WebRTC API का समर्थन नहीं करता है।' : 'This browser does not support the required camera, microphone, or WebRTC APIs.',
        'defaultMicrophone' => $isHi ? 'डिफ़ॉल्ट माइक्रोफ़ोन' : 'Default microphone',
        'defaultCamera' => $isHi ? 'डिफ़ॉल्ट कैमरा' : 'Default camera',
        'errBlocked' => $isHi ? 'कैमरा या माइक्रोफ़ोन अनुमति अवरुद्ध कर दी गई थी। ब्राउज़र एड्रेस बार में पहुंच की अनुमति दें और पुनः प्रयास करें।' : 'Camera or microphone permission was blocked. Allow access in the browser address bar and retry.',
        'errNotFound' => $isHi ? 'इस उपकरण पर कोई उपयोगी कैमरा या माइक्रोफ़ोन नहीं मिला।' : 'No usable camera or microphone was found on this device.',
        'errInUse' => $isHi ? 'आपका कैमरा या माइक्रोफ़ोन पहले से ही किसी अन्य ऐप, ब्राउज़र टैब या प्रोफ़ाइल द्वारा उपयोग किया जा रहा है। यदि आप एक ही कंप्यूटर पर व्यवस्थापक और रोगी का परीक्षण कर रहे हैं, तो एक पक्ष को दूसरे फोन या लैपटॉप पर ले जाएं और पुनः प्रयास करें।' : 'Your camera or microphone is already in use by another app, browser tab, or profile. If you are testing admin and patient on the same computer, move one side to another phone or laptop and retry.',
        'errOverconstrained' => $isHi ? 'चयनित कैमरा या माइक्रोफ़ोन अनुपलब्ध है। डिफ़ॉल्ट डिवाइस को फिर से चुनें और पुनः प्रयास करें।' : 'The selected camera or microphone is unavailable. Re-select the default device and retry.',
        'errSecurity' => $isHi ? 'ब्राउज़र मीडिया एक्सेस के लिए इस पेज को लोकलहोस्ट या HTTPS से खोला जाना चाहिए।' : 'This page must be opened from localhost or HTTPS for browser media access.',
        'errAttemptedPrefix' => $isHi ? 'ब्राउज़र' : 'The browser could not start camera or microphone access after trying',
        'errAttemptedSuffix' => $isHi ? 'प्रयास करने के बाद कैमरा या माइक्रोफ़ोन एक्सेस शुरू नहीं कर सका। जांचें कि क्या कोई अन्य टैब, प्रोफ़ाइल, ज़ूम, मीट, व्हाट्सएप या कैमरा ऐप पहले से ही डिवाइस का उपयोग कर रहा है।' : '. Check whether another tab, profile, Zoom, Meet, WhatsApp, or the camera app is already using the device.',
        'errDefault' => $isHi ? 'ब्राउज़र कैमरा या माइक्रोफ़ोन एक्सेस शुरू नहीं कर सका। अनुमतियों की जांच करें और पुनः प्रयास करें।' : 'The browser could not start camera or microphone access. Check permissions and retry.',
    ];
@endphp
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const lang = @json($translations);
        const role = @json($role);
        const pollUrl = @json(route('consultations.poll', $consultation->uuid));
        const signalUrl = @json(route('consultations.signal', $consultation->uuid));
        const endUrl = @json(route('consultations.end', $consultation->uuid));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const hasTurnServer = @json($hasTurnServer);
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
        const chatMessagesContainer = document.getElementById('chat-messages-container');
        const chatMessageInput = document.getElementById('chat-message-input');
        const chatSendBtn = document.getElementById('chat-send-btn');
        let localMessageCount = 0;

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
        let localTrackSenders = {
            audio: null,
            video: null,
        };
        const appliedCandidates = new Set();
        const queuedRemoteCandidates = [];

        function cleanSdp(sdp) {
            if (!sdp) return '';
            return sdp
                .replace(/\r\n/g, '\n')
                .split('\n')
                .map(line => line.trim())
                .filter(line => line && !line.startsWith('a=candidate:'))
                .join('\n');
        }

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
            const attemptedModes = Array.isArray(error?.attemptedModes) ? error.attemptedModes.join(', ') : '';
            const details = error?.details ? ` ${error.details}` : '';

            if (name === 'NotAllowedError' || name === 'PermissionDeniedError') {
                return `${lang.errBlocked}${details}`.trim();
            }

            if (name === 'NotFoundError' || name === 'DevicesNotFoundError') {
                return `${lang.errNotFound}${details}`.trim();
            }

            if (name === 'NotReadableError' || name === 'TrackStartError' || name === 'AbortError') {
                return `${lang.errInUse}${details}`.trim();
            }

            if (name === 'OverconstrainedError') {
                return lang.errOverconstrained;
            }

            if (name === 'SecurityError') {
                return lang.errSecurity;
            }

            if (attemptedModes) {
                return `${lang.errAttemptedPrefix} ${attemptedModes} ${lang.errAttemptedSuffix}${details}`.trim();
            }

            return `${lang.errDefault}${details}`.trim();
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

        async function syncRemotePlayback() {
            if (!remoteVideo?.srcObject) {
                return;
            }

            try {
                await remoteVideo.play();
            } catch (error) {
                console.warn('Remote autoplay is waiting for user interaction.', error);
            }
        }

        function clearRemoteStream() {
            remoteStream?.getTracks().forEach((track) => {
                try {
                    track.stop();
                } catch (error) {
                    console.warn('Unable to stop remote track cleanly.', error);
                }
            });

            remoteStream = new MediaStream();
            if (remoteVideo) {
                remoteVideo.srcObject = remoteStream;
            }

            updateRemoteVideoState();
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

            setOptions(audioInputSelect, audioInputs, selectedAudioDeviceId, lang.defaultMicrophone);
            setOptions(videoInputSelect, videoInputs, selectedVideoDeviceId, lang.defaultCamera);
        }

        async function preparePreview(force = false) {
            if (callEnded) {
                return;
            }

            if (force) {
                stopLocalStream();
            }

            setPrejoinStatus(lang.checkingYourDevices);

            try {
                const media = await acquireLocalStream();
                stopLocalStream();
                localStream = media.stream;
                prejoinVideo.srcObject = localStream;
                localVideo.srcObject = localStream;

                if (media.mode === 'none') {
                    setPrejoinStatus(lang.joiningCameraMicOff);
                } else if (media.mode === 'video-only') {
                    setPrejoinStatus(lang.previewCameraOnly);
                } else if (media.mode === 'audio-only') {
                    setPrejoinStatus(lang.previewMicOnly);
                } else {
                    setPrejoinStatus(lang.previewReady);
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

        function releasePrejoinPreview() {
            if (joinedCall || callEnded) {
                return;
            }

            stopLocalStream();
            setPrejoinStatus(lang.previewStopped);
            showDeviceAlert('');
            updateControlAvailability();
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
            const failures = [];

            for (const attempt of attempts) {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia(attempt.constraints);
                    return { stream, mode: attempt.mode };
                } catch (error) {
                    lastError = error;
                    failures.push(`${attempt.mode}: ${error?.name || 'UnknownError'}`);
                }
            }

            if (lastError && failures.length) {
                lastError.attemptedModes = attempts.map((attempt) => attempt.mode);
                lastError.details = `Attempts failed: ${failures.join('; ')}.`;
            }

            throw lastError;
        }

        function stopLocalStream() {
            if (!localStream) {
                return;
            }

            localStream.getTracks().forEach((track) => track.stop());
            localStream = null;
            if (peerConnection) {
                attachLocalTracksToPeer();
            }
            localVideo.srcObject = null;
            prejoinVideo.srcObject = null;
            updateLocalPreviewPlaceholders();
        }

        function attachLocalTracksToPeer() {
            if (!peerConnection) {
                return;
            }

            const tracksByKind = {
                audio: localStream?.getAudioTracks?.()[0] ?? null,
                video: localStream?.getVideoTracks?.()[0] ?? null,
            };

            ['audio', 'video'].forEach((kind) => {
                const track = tracksByKind[kind];
                let sender = localTrackSenders[kind];

                if (!sender) {
                    sender = peerConnection.getSenders().find((candidate) => candidate.track?.kind === kind) || null;
                }

                if (sender) {
                    sender.replaceTrack(track).catch((error) => {
                        console.error(`Unable to sync ${kind} track`, error);
                    });
                    localTrackSenders[kind] = sender;
                    return;
                }

                if (track) {
                    localTrackSenders[kind] = peerConnection.addTrack(track, localStream);
                }
            });
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
                activeLabel: lang.muteMic,
                inactiveLabel: lang.unmuteMic,
                unavailableLabel: lang.micUnavailable,
                activeIcon: 'mic',
                inactiveIcon: hasAudio ? 'mic-off' : 'mic-off',
                disabled: !preferredMediaState.audio || (!hasAudio && joinedCall) || callEnded,
            });
            updateToggleButton(toggleCameraBtn, {
                active: isVideoEnabled,
                activeLabel: lang.turnCameraOff,
                inactiveLabel: lang.turnCameraOn,
                unavailableLabel: lang.cameraUnavailable,
                activeIcon: 'video',
                inactiveIcon: hasVideo ? 'video-off' : 'video-off',
                disabled: !preferredMediaState.video || (!hasVideo && joinedCall) || callEnded,
            });
            updateToggleButton(prejoinToggleMicBtn, {
                active: isAudioEnabled,
                activeLabel: lang.muteMic,
                inactiveLabel: lang.unmuteMic,
                unavailableLabel: lang.micUnavailable,
                activeIcon: 'mic',
                inactiveIcon: 'mic-off',
                disabled: callEnded,
            });
            updateToggleButton(prejoinToggleCameraBtn, {
                active: isVideoEnabled,
                activeLabel: lang.turnCameraOff,
                inactiveLabel: lang.turnCameraOn,
                unavailableLabel: lang.cameraUnavailable,
                activeIcon: 'video',
                inactiveIcon: 'video-off',
                disabled: callEnded,
            });

            if (bootstrapping) {
                setDeviceState(lang.checkingDevices, 'ready');
            } else if (callEnded) {
                setDeviceState(lang.callClosed, 'danger');
            } else if (hasAudio && hasVideo) {
                setDeviceState(lang.micCameraOn, 'success');
            } else if (hasAudio || hasVideo) {
                setDeviceState(hasAudio ? lang.micOnly : lang.cameraOnly, 'warning');
            } else {
                setDeviceState(lang.noLocalMedia, 'danger');
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

            const nextStatus = statusOverride || (
                currentConsultationStatus === 'active'
                    ? 'active'
                    : (currentConsultationStatus === 'accepted' ? 'accepted' : 'pending')
            );

            await postSignal({
                role,
                status: nextStatus,
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
                    await peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
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
                    await peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
                    appliedCandidates.add(key);
                } catch (error) {
                    console.error('Queued ICE candidate apply failed', error);
                }
            }
        }

        function resetPeerConnection() {
            if (peerConnection) {
                try {
                    peerConnection.close();
                } catch (e) {
                    console.error('Error closing peer connection', e);
                }
                peerConnection = null;
            }
            remoteDescriptionApplied = false;
            answerCreated = false;
            pendingCandidates = [];
            localTrackSenders = {
                audio: null,
                video: null,
            };
            appliedCandidates.clear();
            queuedRemoteCandidates.length = 0;
            ensurePeerConnection();
            clearRemoteStream();
            attachLocalTracksToPeer();
        }

        async function handlePolledState(state) {
            if (!state) {
                return;
            }

            if (state.chat_messages) {
                renderChatMessages(state.chat_messages);
            }

            currentConsultationStatus = state.status;

            if (state.status === 'completed') {
                finishCall(lang.callCompleted, 'dark');
                return;
            }

            if (state.status === 'active') {
                startCallTimer();
            }

            if (state.status === 'rejected') {
                finishCall(lang.callRejected, 'danger');
                showDeviceAlert(role === 'patient'
                    ? lang.patientRejectedByAdmin
                    : lang.consultationRejected);
                return;
            }

            if (role === 'patient' && state.status === 'accepted' && !state.sdp_answer) {
                setStatus(lang.doctorAccepted, 'info');
            }

            if (role === 'patient') {
                const currentRemoteDesc = peerConnection?.remoteDescription;
                if (state.sdp_answer) {
                    if (!currentRemoteDesc) {
                        await peerConnection.setRemoteDescription(new RTCSessionDescription(state.sdp_answer));
                        remoteDescriptionApplied = true;
                        setStatus(lang.doctorConnected, 'success');
                        await flushQueuedRemoteCandidates();
                    } else if (cleanSdp(currentRemoteDesc.sdp) !== cleanSdp(state.sdp_answer.sdp)) {
                        console.log('Doctor answer changed, renegotiating...');
                        resetPeerConnection();
                        await sendPatientOffer('active');
                    }
                }

                await applyRemoteCandidates(state.ice_candidates_doctor || []);
                return;
            }

            const currentRemoteDesc = peerConnection?.remoteDescription;
            if (state.sdp_offer) {
                if (!currentRemoteDesc) {
                    await peerConnection.setRemoteDescription(new RTCSessionDescription(state.sdp_offer));
                    remoteDescriptionApplied = true;
                    setStatus(lang.offerReceived, 'info');
                    await flushQueuedRemoteCandidates();
                } else if (cleanSdp(currentRemoteDesc.sdp) !== cleanSdp(state.sdp_offer.sdp)) {
                    console.log('Patient offer changed, recreating answer...');
                    resetPeerConnection();
                    await peerConnection.setRemoteDescription(new RTCSessionDescription(state.sdp_offer));
                    remoteDescriptionApplied = true;
                    setStatus(lang.offerReceived, 'info');
                    await flushQueuedRemoteCandidates();
                }
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
                setStatus(lang.answerSent, 'success');
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

            pendingCandidates = [];
            localTrackSenders = {
                audio: null,
                video: null,
            };
            stopLocalStream();
            clearRemoteStream();

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
                finishCall(lang.callEnded, 'dark');
            }
        }

        function ensurePeerConnection() {
            if (peerConnection) {
                return peerConnection;
            }

            peerConnection = new RTCPeerConnection(peerConfig);
            clearRemoteStream();

            peerConnection.addEventListener('icecandidate', (event) => {
                if (!event.candidate) {
                    return;
                }

                pendingCandidates.push(event.candidate.toJSON());
                scheduleCandidateFlush();
            });

            peerConnection.addEventListener('track', (event) => {
                if (!remoteStream.getTracks().some((track) => track.id === event.track.id)) {
                    remoteStream.addTrack(event.track);
                }
                remoteVideo.srcObject = remoteStream;

                event.track.onmute = () => updateRemoteVideoState();
                event.track.onunmute = () => updateRemoteVideoState();
                remoteVideo.onloadedmetadata = () => {
                    updateRemoteVideoState();
                    syncRemotePlayback();
                };
                remotePlaceholder.classList.add('hidden');
                updateRemoteVideoState();
                syncRemotePlayback();
                setStatus(lang.peerConnectionEstablished, 'success');
                startCallTimer();
            });

            peerConnection.addEventListener('connectionstatechange', () => {
                const state = peerConnection.connectionState;

                if (state === 'connected') {
                    setStatus(lang.liveCallConnected, 'success');
                    startCallTimer();
                } else if (state === 'connecting') {
                    setStatus(lang.connectingCall, 'info');
                } else if (state === 'disconnected') {
                    setStatus(lang.connectionInterrupted, 'warning');
                } else if (state === 'failed') {
                    setStatus(lang.connectionFailed, 'danger');
                    showDeviceAlert(hasTurnServer
                        ? lang.peerMediaFailedWithTurn
                        : lang.peerMediaFailedWithoutTurn);
                } else if (state === 'closed') {
                    setStatus(lang.callClosed, 'dark');
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
                    setStatus(lang.pollingRetrying, 'warning');
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
            setStatus(lang.preparingDevices, 'secondary');

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
                attachLocalTracksToPeer();

                if (media.mode === 'video-only') {
                    showDeviceAlert(lang.micUnavailableStartVideoOnly);
                    setDeviceState(lang.cameraOnly, 'warning');
                } else if (media.mode === 'audio-only') {
                    showDeviceAlert(lang.cameraUnavailableStartAudioOnly);
                    setDeviceState(lang.micOnly, 'warning');
                } else if (media.mode === 'none') {
                    showDeviceAlert(lang.joinedNoMediaRetry);
                    setDeviceState(lang.joinedWithoutLocalMedia, 'danger');
                } else {
                    setDeviceState(lang.micCameraOn, 'success');
                }

                updateControlAvailability();

                if (role === 'patient' && !offered) {
                    await sendPatientOffer();
                    setStatus(lang.waitingForDoctor, 'info');
                } else if (role === 'doctor' && !remoteDescriptionApplied) {
                    setStatus(lang.waitingForPatient, 'info');
                }

                startPolling();

                const initialState = await pollState();
                await handlePolledState(initialState);
            } catch (error) {
                console.error(error);
                showDeviceAlert(getFriendlyMediaError(error));
                setStatus(lang.joiningWithoutLocalMedia, 'warning');
                setDeviceState(lang.joiningWithoutLocalMedia, 'danger');

                ensurePeerConnection();
                attachLocalTracksToPeer();
                startPolling();

                if (role === 'patient' && !offered) {
                    await sendPatientOffer();
                    setStatus(lang.waitingForDoctor, 'info');
                } else if (role === 'doctor') {
                    setStatus(lang.waitingForPatient, 'info');
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

        retryMediaBtn.addEventListener('click', async () => {
            offered = false;
            stopLocalStream();
            resetPeerConnection();
            await bootstrapPeer();
        });

        prejoinRetryBtn.addEventListener('click', async () => {
            await preparePreview(true);
        });

        joinCallBtn.addEventListener('click', async () => {
            joinCallBtn.disabled = true;
            setPrejoinStatus(lang.joiningConsultationRoom);
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

        function renderChatMessages(messages) {
            if (!chatMessagesContainer) {
                return;
            }

            chatMessagesContainer.innerHTML = '';

            messages.forEach((msg) => {
                const isLocal = msg.sender === role;
                const msgEl = document.createElement('div');
                msgEl.className = `consult-chat-message ${isLocal ? 'consult-chat-message--local' : 'consult-chat-message--remote'}`;

                const metaEl = document.createElement('div');
                metaEl.className = 'consult-chat-meta';
                metaEl.textContent = isLocal ? lang.you : msg.sender_name;

                const textEl = document.createElement('div');
                textEl.className = 'consult-chat-text';
                textEl.textContent = msg.text;

                msgEl.appendChild(metaEl);
                msgEl.appendChild(textEl);
                chatMessagesContainer.appendChild(msgEl);
            });

            if (messages.length > localMessageCount) {
                localMessageCount = messages.length;
                chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
            }
        }

        async function sendChatMessage() {
            if (!chatMessageInput) {
                return;
            }

            const text = chatMessageInput.value.trim();
            if (!text) {
                return;
            }

            chatMessageInput.value = '';

            try {
                const response = await postSignal({
                    role,
                    message: text,
                });
                if (response && response.consultation) {
                    renderChatMessages(response.consultation.chat_messages || []);
                }
            } catch (error) {
                console.error('Failed to send chat message', error);
            }
        }

        chatSendBtn?.addEventListener('click', sendChatMessage);
        chatMessageInput?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                sendChatMessage();
            }
        });

        endCallBtn.addEventListener('click', endCall);

        if (!navigator.mediaDevices?.getUserMedia || !window.RTCPeerConnection) {
            setStatus(lang.browserNotSupported, 'danger');
            showDeviceAlert(lang.webrtcNotSupported);
            retryMediaBtn.disabled = true;
            prejoinRetryBtn.disabled = true;
            toggleMicBtn.disabled = true;
            toggleCameraBtn.disabled = true;
            prejoinToggleMicBtn.disabled = true;
            prejoinToggleCameraBtn.disabled = true;
            joinCallBtn.disabled = true;
            endCallBtn.disabled = true;
            setDeviceState(lang.browserNotSupported, 'danger');
            return;
        }

        setDeviceState(lang.checkingDevices, 'ready');
        updateControlAvailability();
        syncLucideIcons();
        clearRemoteStream();
        navigator.mediaDevices?.addEventListener?.('devicechange', async () => {
            await populateDeviceOptions();
        });
        preparePreview(true);

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                releasePrejoinPreview();
            } else if (!joinedCall && !callEnded) {
                preparePreview();
            }
        });
    });
</script>
@endpush
