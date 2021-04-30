@props(['trackers', 'pills'])

<section class="p-4 border-2 border-gray-200 rounded-md">
    <h3 class="text-center text-lg text-gray-900 font-medium mb-5">Leaderboard</h3>

    <section
        x-data="{
            pills: {{ json_encode($pills) }},
            selectedPill: @entangle('selectedPill'),
            isTheSelectedPill(pill) {
                return this.selectedPill === pill;
            },
            selectPill(pill) {
                if (pill === this.selectedPill) return;
                this.selectedPill = pill;
            },
            get greenPill() {
                return 'bg-green-base text-white';
            },
            get grayPill() {
                return 'bg-white border-2 border-gray-200 text-cool-gray-800';
            }
        }"
        id="items"
        class="flex flex-no-wrap items-center space-x-2 overflow-x-hidden overflow-y-hidden whitespace-no-wrap px-5 mb-7"
    >
        <template x-for="pill in pills" :key="pill">
            <div
                class="font-medium text-sm px-5 py-1 rounded-md shadow-md cursor-pointer uppercase"
                :class="isTheSelectedPill(pill) ? greenPill : grayPill"
                style="min-width: fit-content; flex: 0 0 auto"
                x-text="pill"
                @click="selectPill(pill)"
            >
            </div>
        </template>
    </section>

    <section class="flex flex-col space-y-5 px-5" >
        @forelse ($trackers as $tracker)
            <div class="col-span-full flex justify-between items-center text-gray-900 font-medium">
                <p class="col-span-1 w-5">{{ $loop->index + 1 }}</p>
                {{-- <p class="flex-1 ml-4">{{ $tracker->user->full_name }}</p> --}}
                <p>{{ $tracker->total }}</p>
            </div>
        @empty
            <p class="italic text-gray-800">No users found...</p>
        @endforelse
    </section>
    <section class="flex" wire:loading>
        <x-svg.spinner
            color="#9fa6b2"
            class="relative hidden w-6 top-2">
        </x-svg.spinner>
    </section>
</section>

@push('scripts')
    <script>
        // https://codepen.io/thenutz/pen/VwYeYEE

        const slider = document.querySelector('#items');
        let startX;
        let scrollLeft;
        let isDown = false;

        slider.addEventListener('mouseleave', () => isDown = false);
        slider.addEventListener('mouseup', () => isDown = false);

        slider.addEventListener('mousedown', (event) => {
            startX = event.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
            isDown = true;
        });

        slider.addEventListener('mousemove', (event) => {
            if (!isDown) return;

            event.preventDefault();
            const x = event.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;

            slider.scrollLeft = scrollLeft - walk;
        });
    </script>
@endpush
