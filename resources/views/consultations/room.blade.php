@extends('layouts.public')

@section('title', 'Consultation Room | Arogio')
@section('meta_description', 'Private patient-side video consultation room.')

@section('content')
<section class="py-8 lg:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('consultations._room_interface', [
            'consultation' => $consultation,
            'role' => 'patient',
            'eyebrow' => 'Patient room',
            'title' => 'Your video consultation is ready',
            'subtitle' => 'Stay on this page while the doctor joins from the admin dashboard.',
            'backUrl' => route('consultations.index'),
            'backLabel' => 'New consultation',
        ])
    </div>
</section>
@endsection
