@extends('layouts.app')

@section('app.content')
    <div class="pb-32">
        <x-nav.main/>
        
        <livewire:notifications/>

        <x-alerts/>

    </div>
    <main class="-mt-32">
        <div class="max-w-7xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">

            @yield('content')

        </div>
    </main>
@endsection
