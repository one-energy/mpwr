<div x-cloak
     @sidebar-toggled.window="opened = $event.detail"
     @sidebar-mobile.window="isMobile = $event.detail"
     x-data="{
    opened: @entangle('opened').defer,
    isMobile: false,
    close() {
        this.opened = false;
    },
    get widthSidebar() {
        const size = this.isMobile ? '100%' : '500px';

        return this.opened ? size : 0;
    },
    get styles() {
        const sidebarWidth = this.widthSidebar;
        const translateX = this.opened ? '0%' : '100%';
        return `
            box-shadow: 0 8px 10px -5px rgb(0 0 0 / 20%), 0 16px 24px 2px rgb(0 0 0 / 14%), 0 6px 30px 5px rgb(0 0 0 / 12%);
            width: ${sidebarWidth};
            transform: translateX(${translateX});
            transition-duration: .2s;
            transition-timing-function: cubic-bezier(.4,0,.2,1);
            transition-property: transform,visibility,width;
        `;
    }
}">

    <aside
        class="bg-white fixed h-screen top-0 right-0 z-50"
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
                <p class="text-center">You read all notifications &#128512;</p>
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
