@props(['title', 'header', 'headerSlot'])
@php
    $header = $header ?? null;
    $headerSlot = $headerSlot ?? null;
@endphp
@extends('layouts.auth')

@section('title', $title)

@section('header')
    @if($headerSlot)
        {{ $headerSlot }}
    @else
        <x-header :text="$header"/>
    @endif
@endsection

@section('content')
    {{ $slot  }}
@endsection
