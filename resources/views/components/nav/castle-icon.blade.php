<a href="{{ route('castle.dashboard') }}"
   class="p-1 border-2 border-transparent {{ is_active('castle.*') ? 'text-green-400' : 'text-gray-400' }} rounded-full hover:text-white focus:outline-none focus:text-white focus:bg-gray-700"
   aria-label="Notifications">
    <x-svg.shield class="h-6 w-6"></x-svg.shield>
</a>
