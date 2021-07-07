<div x-cloak x-data="{
    opened: @entangle('opened').defer,
    close() {
        this.opened = false;
    },
    get width() {
        return this.opened ? '0' : '500px';
    },
    get styles() {
        const sidebarWidth = this.opened ? '500px' : '0';
        const translateX = this.opened ? '0' : '500px';
        return `
            width: ${sidebarWidth};
            transform: translateX(${translateX});
            transition: width 350ms cubic-bezier(.71,.13,.8,.67),
                        transform 350ms cubic-bezier(.71,.13,.8,.67);
        `;
    }
}">
    <div
        class="bg-black bg-opacity-25 fixed h-screen w-screen inset-0 z-20 cursor-pointer"
        :class="{'hidden': !opened}"
        @click="close"
    >
    </div>

    <aside
        class="bg-white fixed shadow-2xl h-screen top-0 right-0 z-30"
        id="sidebar"
        :style="styles">
        <header class="flex items-center justify-between px-5 py-6">
            <h1 class="text-xl">Notification(s)</h1>
            <button
                class="hover:bg-gray-100 rounded-full outline-none focus:outline-none p-2"
                id="sidebarCloseBtn"
                @click="close"
            >
                <x-icon icon="x" class="w-5 h-5 cursor-pointer" />
            </button>
        </header>

        <section class="px-3">
            @if ($notifications->isNotEmpty())
            <ul class="space-y-5">
                @foreach($notifications as $notification)
                    <li class="text-sm text-gray-800 border-l-2 hover:bg-gray-50 flex items-center">
                        <div class="mr-5">
                            <p class="pl-2">{!! $this->getMessage($notification) !!}</p>
                            @if ($this->hasMeta($notification))
                                <a
                                    class="text-green-base pl-2"
                                    href="{{ $notification['data']['data']['meta']['link'] }}">
                                    {{ $notification['data']['data']['meta']['text'] }}
                                </a>
                            @endif
                        </div>
                        <div class="mr-2">
                            <button
                                title="mark as read"
                                class="outline-none focus:outline-none md:opacity-0"
                                wire:click="read({{ $notification }})"
                            >
                                <x-icon icon="check" class="w-6 h-6" name="check" />
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
            @else
                <p>I will display &#128512;</p>
            @endif
        </section>
    </aside>
</div>

@push('styles')
    <style>
        svg[name=check]:hover {
            --text-opacity: 1;
            color: #46A049;
            color: rgba(70, 160, 73, var(--text-opacity));
        }

        li:hover > div:nth-child(2) > button {
            opacity: 1;
        }
    </style>
@endpush
