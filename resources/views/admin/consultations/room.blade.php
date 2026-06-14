@extends('admin.layouts.app')

@section('content')
@include('consultations._room_interface', [
    'consultation' => $consultation,
    'role' => 'doctor',
    'eyebrow' => 'Admin room',
    'title' => 'Join patient consultation',
    'subtitle' => 'Answer the patient offer and stay on this screen until the consultation is completed.',
    'backUrl' => route('admin.consultations'),
    'backLabel' => 'Back to list',
])
@endsection
