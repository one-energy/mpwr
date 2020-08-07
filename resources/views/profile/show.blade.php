<x-app.auth :title="__('Profile')" :header="__('Profile')">
    @if (session('message'))
        <x-alert class="mb-4">
            {{ session('message') }}
        </x-alert>
    @endif

    <x-profile.update-form/>

    <div class="mt-8 border-t border-gray-200 pt-5">
        <div class="h-8"></div>
    </div>

    <x-profile.update-password/>
</x-app.auth>
