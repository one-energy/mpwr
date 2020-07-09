@extends('layouts.auth')

@section('title', __('Verify Your Email Address'))

@section('header')
    <x-header :text="__('Verify Your Email Address')"></x-header>
@endsection

@section('content')
    @if (session('resent'))
        <x-alert class="mb-4">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </x-alert>
    @endif

    <x-card class="h-96 ">
        <div class="flex align-middle justify-center">
            <x-svg.draw.tw-factor class="w-80"></x-svg.draw.tw-factor>

            <div class="pl-14 flex w-1/3">
                <div class="self-center text-2xl">
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    <p class="leading-normal mt-8">
                        {{ __('If you did not receive the email,') }}
                        <x-link type="submit"
                                form="resend-verification-form">{{ __('click here to request another') }}</x-link>
                        .
                    </p>
                    <x-form id="resend-verification-form" :route="route('verification.resend')"
                            class="hidden"></x-form>
                </div>
            </div>
        </div>
    </x-card>
@endsection
