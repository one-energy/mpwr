<div
    x-data="{
        show: @entangle($attributes->wire('model')),
        open() {
            this.show = true;
        },
        close() {
            this.show = false;
        },
        get isOpen() {
            return this.show === true;
        },
    }"
    @keydown.escape.window="close"
    x-cloak
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div
        x-show="isOpen"
        class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20"
    >
        <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div
            x-show="isOpen"
            @click.away="close"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white rounded-md overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full h-60"
        >
            <div class="p-5 h-full flex flex-col">
                <button
                    type="button"
                    @click="close"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150"
                    aria-label="Close"
                >
                    <x-svg.x class="w-5 h-5" />
                </button>
                <div>
                    {{ $header }}
                </div>

                <div class="my-5 flex-1">
                    {{ $body }}
                </div>

                <div>
                    {{ $footer }}
                </div>
            </div>
        </div>
    </div>
</div>