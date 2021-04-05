@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
                Reset Password
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <x-card>
                @if (session('status'))
                    <x-alert color="green" title="An email with reset link was sent to you" class="mb-6">
                        {{ session('status') }}
                    </x-alert>
                @endif

                <x-form :route="route('password.email')">
                    <x-input label="E-Mail Address" name="email"/>

                    <div class="mt-6 flex items-center justify-end">
                        <div class="text-sm leading-5">
                            <x-link :href="route('login')">
                                Back to login
                            </x-link>
                        </div>
                    </div>

                    <div class="mt-6">
                        <span class="block w-full rounded-md shadow-sm">
                            <x-button class="w-full flex" type="submit" color="green">
                                Send Password Reset Link
                            </x-button>
                        </span>
                    </div>
                </x-form>
            </x-card>
        </div>
    </div>
@endsection
