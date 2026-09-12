@extends('layouts.public')





@section('title', ($locale === 'hi' ? 'लक्षण परीक्षण' : 'Symptom Test') . ' - Arogio')


@section('meta_title', $locale === 'hi' ? 'लक्षण परीक्षण | अरोगियो' : 'Symptom Test | Arogio')


@section('meta_description', $locale === 'hi'


    ? 'आयु, लिंग और लक्षण चरण दर चरण चुनें। संभावित स्थितियाँ, संबंधित विभाग और पूछने के लिए अगले लक्षण देखें।'


    : 'Choose age, gender, and symptoms step by step. See likely conditions, the relevant department, and the next symptoms to ask about.')





@section('content')
@include('partials.symptom-design')
@endsection
