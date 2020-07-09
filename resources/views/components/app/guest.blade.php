@extends('layouts.guest')

@section('title', $title)

@section('content')
    {{ $slot }}
@endsection
