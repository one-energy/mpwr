<x-app.auth :title="__('Profile')" :header="__('Profile')">
    @if (session('message'))
        <x-alert class="mb-4">
            {{ session('message') }}
        </x-alert>
    @endif

    <x-profile.update-form/>

    <div class="h-8"></div>

    <x-profile.update-password/>
</x-app.auth>
