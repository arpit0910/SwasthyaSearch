@extends('layouts.public')

@php
    $isHi = \App\Helpers\LocaleHelper::current() === 'hi';
@endphp

@section('title', $isHi ? 'विवरण साझा करें | Arogio' : 'Share Details | Arogio')
@section('meta_description', $isHi ? 'Arogio के साथ सत्यापित डॉक्टर या अस्पताल की जानकारी साझा करें।' : 'Share verified doctor or hospital details with Arogio.')

@section('content')
<section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(20,184,166,0.15),_transparent_38%),radial-gradient(circle_at_bottom_right,_rgba(49,46,129,0.12),_transparent_35%)]"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="text-center space-y-4 mb-10">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-teal-200/80 bg-white/90 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-teal-700 shadow-sm dark:border-teal-900/50 dark:bg-slate-950/80 dark:text-teal-300">
                <i data-lucide="users" class="w-3.5 h-3.5 text-teal-600 dark:text-teal-400"></i>
                {{ $isHi ? 'सामुदायिक योगदान' : 'Community Contribution' }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                {{ $isHi ? 'डॉक्टर या अस्पताल की जानकारी साझा करें' : 'Share Doctor or Hospital Details' }}
            </h1>
            <p class="max-w-2xl mx-auto text-base text-slate-600 dark:text-slate-300">
                {{ $isHi 
                    ? 'यदि आपके पास किसी डॉक्टर या अस्पताल की सही जानकारी है, तो कृपया नीचे साझा करें। हम विवरणों को सत्यापित करेंगे और निर्देशिका में जोड़ेंगे।' 
                    : 'If you have correct information about any doctor or hospital, please share it below. We will validate and import verified listings to our directory.' 
                }}
            </p>
        </div>

        @if($errors->any())
            <div class="mb-8 rounded-2xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 p-5 text-rose-800 dark:text-rose-300 shadow-sm flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-6 h-6 text-rose-600 dark:text-rose-400 shrink-0 mt-0.5"></i>
                <div>
                    <h4 class="font-bold text-base">{{ $isHi ? 'कृपया त्रुटियों को सुधारें' : 'Please resolve the errors' }}</h4>
                    <ul class="mt-2 list-disc pl-5 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="rounded-3xl border border-white/70 bg-white/90 p-6 sm:p-10 shadow-xl dark:border-slate-800 dark:bg-slate-900/85">
            <form action="{{ route('suggestions.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Selection Toggle -->
                <div>
                    <label class="mb-3 block text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                        {{ $isHi ? 'आप किसकी जानकारी साझा करना चाहते हैं?' : 'What would you like to suggest?' }}
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label id="label-type-doctor" class="relative flex items-center justify-center p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 cursor-pointer select-none transition-all duration-200 hover:border-teal-300">
                            <input type="radio" name="type" value="doctor" class="sr-only" {{ old('type', $defaultType ?? 'doctor') === 'doctor' ? 'checked' : '' }} onchange="toggleFormFields('doctor')">
                            <div class="text-center space-y-1">
                                <i data-lucide="stethoscope" class="w-6 h-6 mx-auto text-teal-600"></i>
                                <span class="block font-bold text-sm text-slate-800 dark:text-slate-100">{{ $isHi ? 'डॉक्टर' : 'Doctor' }}</span>
                            </div>
                        </label>
                        <label id="label-type-hospital" class="relative flex items-center justify-center p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 cursor-pointer select-none transition-all duration-200 hover:border-teal-300">
                            <input type="radio" name="type" value="hospital" class="sr-only" {{ old('type', $defaultType ?? 'doctor') === 'hospital' ? 'checked' : '' }} onchange="toggleFormFields('hospital')">
                            <div class="text-center space-y-1">
                                <i data-lucide="building-2" class="w-6 h-6 mx-auto text-teal-600"></i>
                                <span class="block font-bold text-sm text-slate-800 dark:text-slate-100">{{ $isHi ? 'अस्पताल / क्लीनिक' : 'Hospital / Clinic' }}</span>
                            </div>
                        </label>
                    </div>
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
                        {{ $isHi ? 'à¤•à¥‡à¤µà¤² à¤®à¥à¤–à¥à¤¯ à¤œà¤¾à¤¨à¤•à¤¾à¤°à¥€ à¤¶à¥‡à¤¯à¤° à¤•à¤°à¥‡à¤‚. à¤¹à¤® à¤¬à¤¾à¤•à¥€ à¤µà¥‡à¤°à¤¿à¤«à¤¿à¤•à¥‡à¤¶à¤¨ à¤•à¤° à¤²à¥‡à¤‚à¤—à¥‡.' : 'Only share the main details. We will verify the rest before listing it.' }}
                    </p>
                </div>

                <!-- Basic Fields -->
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            <span id="name-label-doc">{{ $isHi ? 'डॉक्टर का नाम' : 'Doctor’s Full Name' }}</span>
                            <span id="name-label-hosp" class="hidden">{{ $isHi ? 'अस्पताल / क्लीनिक का नाम' : 'Hospital / Clinic Name' }}</span>
                        </label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            required
                            maxlength="255"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                            placeholder="{{ $isHi ? 'नाम दर्ज करें' : 'Enter name' }}"
                        >
                    </div>
                    <div>
                        <label for="phone" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $isHi ? 'संपर्क नंबर (वैकल्पिक)' : 'Contact Phone (Optional)' }}
                        </label>
                        <input
                            id="phone"
                            name="phone"
                            type="text"
                            value="{{ old('phone') }}"
                            maxlength="50"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                            placeholder="{{ $isHi ? 'उदा. 9876543210' : 'e.g. 9876543210' }}"
                        >
                    </div>
                </div>

                <!-- Structured Address Fields -->
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label for="address_line_1" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $isHi ? 'पता पंक्ति 1' : 'Address Line 1' }}
                        </label>
                        <input
                            id="address_line_1"
                            name="address_line_1"
                            type="text"
                            value="{{ old('address_line_1') }}"
                            required
                            maxlength="255"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                            placeholder="{{ $isHi ? 'मकान नंबर, गली, मोहल्ला' : 'House number, Street, Area' }}"
                        >
                    </div>
                    <div>
                        <label for="address_line_2" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $isHi ? 'पता पंक्ति 2 (वैकल्पिक)' : 'Address Line 2 (Optional)' }}
                        </label>
                        <input
                            id="address_line_2"
                            name="address_line_2"
                            type="text"
                            value="{{ old('address_line_2') }}"
                            maxlength="255"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                            placeholder="{{ $isHi ? 'लैंडमार्क, सेक्टर' : 'Landmark, Sector' }}"
                        >
                    </div>
                </div>

                <div class="grid sm:grid-cols-3 gap-5">
                    <div>
                        <label for="city" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $isHi ? 'शहर' : 'City' }}
                        </label>
                        <input
                            id="city"
                            name="city"
                            type="text"
                            value="{{ old('city', 'Jaipur') }}"
                            required
                            maxlength="100"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                            placeholder="{{ $isHi ? 'उदा. जयपुर' : 'e.g. Jaipur' }}"
                        >
                    </div>
                    <div>
                        <label for="state" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $isHi ? 'राज्य' : 'State' }}
                        </label>
                        <input
                            id="state"
                            name="state"
                            type="text"
                            value="{{ old('state', 'Rajasthan') }}"
                            required
                            maxlength="100"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                            placeholder="{{ $isHi ? 'उदा. राजस्थान' : 'e.g. Rajasthan' }}"
                        >
                    </div>
                    <div>
                        <label for="pincode" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $isHi ? 'पिनकोड' : 'Pincode' }}
                        </label>
                        <input
                            id="pincode"
                            name="pincode"
                            type="text"
                            value="{{ old('pincode') }}"
                            required
                            maxlength="20"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                            placeholder="{{ $isHi ? 'उदा. 302017' : 'e.g. 302017' }}"
                        >
                    </div>
                </div>

                <!-- Geolocation Fields -->
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label for="latitude" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $isHi ? 'अक्षांश (Latitude - वैकल्पिक)' : 'Latitude (Optional)' }}
                        </label>
                        <input
                            id="latitude"
                            name="latitude"
                            type="text"
                            value="{{ old('latitude') }}"
                            maxlength="20"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                            placeholder="e.g. 26.9124"
                        >
                    </div>
                    <div>
                        <label for="longitude" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $isHi ? 'रेखांश (Longitude - वैकल्पिक)' : 'Longitude (Optional)' }}
                        </label>
                        <input
                            id="longitude"
                            name="longitude"
                            type="text"
                            value="{{ old('longitude') }}"
                            maxlength="20"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                            placeholder="e.g. 75.7873"
                        >
                    </div>
                </div>

                <!-- Doctor Fields (Visible by default) -->
                <div id="doctor-fields-container" class="space-y-6">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label for="registration_number" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                                {{ $isHi ? 'मेडिकल काउंसिल रजिस्ट्रेशन नंबर' : 'Medical Council Reg. Number' }}
                            </label>
                            <input
                                id="registration_number"
                                name="registration_number"
                                type="text"
                                value="{{ old('registration_number') }}"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                                placeholder="{{ $isHi ? 'उदा. M-12345' : 'e.g. M-12345' }}"
                            >
                        </div>
                        <div>
                            <label for="specialization" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                                {{ $isHi ? 'विशेषज्ञता / विभाग' : 'Specialization / Department' }}
                            </label>
                            <input
                                id="specialization"
                                name="specialization"
                                type="text"
                                value="{{ old('specialization') }}"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                                placeholder="{{ $isHi ? 'उदा. कार्डियोलॉजिस्ट, पीडियाट्रिशियन' : 'e.g. Cardiologist, Pediatrician' }}"
                            >
                        </div>
                    </div>
                </div>

                <!-- Hospital Fields (Hidden by default) -->
                <div id="hospital-fields-container" class="hidden space-y-6">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label for="hospital_type" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                                {{ $isHi ? 'अस्पताल का प्रकार' : 'Hospital Type' }}
                            </label>
                            <select
                                id="hospital_type"
                                name="hospital_type"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                            >
                                <option value="General">{{ $isHi ? 'सामान्य अस्पताल (General)' : 'General' }}</option>
                                <option value="Multispecialty">{{ $isHi ? 'मल्टीस्पेशलिटी (Multispecialty)' : 'Multispecialty' }}</option>
                                <option value="Super Specialty">{{ $isHi ? 'सुपर स्पेशलिटी (Super Specialty)' : 'Super Specialty' }}</option>
                                <option value="Eye Care">{{ $isHi ? 'आई केयर (Eye Care)' : 'Eye Care' }}</option>
                                <option value="Dental Clinic">{{ $isHi ? 'डेंटल क्लिनिक (Dental Clinic)' : 'Dental Clinic' }}</option>
                            </select>
                        </div>
                        <div class="flex flex-col justify-center gap-3 pt-2">
                            <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" name="accepts_ayushman" value="1" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $isHi ? 'आयुष्मान कार्ड स्वीकार्य है' : 'Accepts Ayushman Card' }}</span>
                            </label>
                            <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" name="accepts_janaadhaar" value="1" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $isHi ? 'जन आधार कार्ड स्वीकार्य है' : 'Accepts Jan Aadhaar Card' }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-teal-500 to-cyan-600 px-5 py-3.5 font-bold text-white shadow-lg shadow-cyan-950/15 transition hover:-translate-y-0.5 hover:shadow-xl">
                        <i data-lucide="send" class="w-5 h-5"></i>
                        <span>{{ $isHi ? 'जानकारी साझा करें' : 'Submit Details' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function toggleFormFields(type) {
        const docFields = document.getElementById('doctor-fields-container');
        const hospFields = document.getElementById('hospital-fields-container');
        const docNameLabel = document.getElementById('name-label-doc');
        const hospNameLabel = document.getElementById('name-label-hosp');

        const docRegInput = document.getElementById('registration_number');
        const docSpecInput = document.getElementById('specialization');
        const hospTypeSelect = document.getElementById('hospital_type');

        const labelDoc = document.getElementById('label-type-doctor');
        const labelHosp = document.getElementById('label-type-hospital');

        if (type === 'doctor') {
            docFields.classList.remove('hidden');
            hospFields.classList.add('hidden');
            docNameLabel.classList.remove('hidden');
            hospNameLabel.classList.add('hidden');

            docRegInput.setAttribute('required', 'required');
            docSpecInput.setAttribute('required', 'required');
            hospTypeSelect.removeAttribute('required');

            // Highlight Doctor
            labelDoc.classList.remove('border-slate-200', 'dark:border-slate-800', 'bg-white', 'dark:bg-slate-950');
            labelDoc.classList.add('border-teal-500', 'bg-teal-50/30', 'dark:bg-teal-950/15');

            // Reset Hospital
            labelHosp.classList.remove('border-teal-500', 'bg-teal-50/30', 'dark:bg-teal-950/15');
            labelHosp.classList.add('border-slate-200', 'dark:border-slate-800', 'bg-white', 'dark:bg-slate-950');
        } else {
            docFields.classList.add('hidden');
            hospFields.classList.remove('hidden');
            docNameLabel.classList.add('hidden');
            hospNameLabel.classList.remove('hidden');

            docRegInput.removeAttribute('required');
            docSpecInput.removeAttribute('required');
            hospTypeSelect.setAttribute('required', 'required');

            // Highlight Hospital
            labelHosp.classList.remove('border-slate-200', 'dark:border-slate-800', 'bg-white', 'dark:bg-slate-950');
            labelHosp.classList.add('border-teal-500', 'bg-teal-50/30', 'dark:bg-teal-950/15');

            // Reset Doctor
            labelDoc.classList.remove('border-teal-500', 'bg-teal-50/30', 'dark:bg-teal-950/15');
            labelDoc.classList.add('border-slate-200', 'dark:border-slate-800', 'bg-white', 'dark:bg-slate-950');
        }
    }

    // Set initial requirements on page load
    document.addEventListener('DOMContentLoaded', () => {
        const selectedType = document.querySelector('input[name="type"]:checked')?.value || 'doctor';
        toggleFormFields(selectedType);
    });
</script>
@endpush
@endsection
