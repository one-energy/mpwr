<a href="{{ $this->trainingIndexRoute }}">
    <div class="border-cool-gray-300 border-2 p-3 cursor-pointer flex items-center">
        <div class="text-center flex flex-1 items-center space-x-3.5 text-base">
            <button class="hover:bg-red-200 focus:outline-none p-2 rounded-full" wire:click.prevent="onDestroy">
                <x-svg.trash class="w-5 h-5  text-red-600 fill-current" />
            </button>
            <p class="">{{ $section->title }}</p>
        </div>
        <div>
            <x-svg.chevron-right class="text-gray-500 font-bold h-6 w-6" />
        </div>
    </div>
</a>
