@props(['trackers', 'pills'])

<section class="p-4 border-2 border-gray-200 rounded-md">
    <div class="flex text-center mb-5">
        <h3 class="text-lg text-gray-900 font-medium mr-3">Leaderboard</h3>
        <x-svg.spinner wire:loading
            color="#9fa6b2"
            class="relative w-6">
        </x-svg.spinner>
    </div>

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
                return 'bg-green-base border-2 border-green-200 text-white sticky left-0 right-0';
            },
            get grayPill() {
                return 'bg-white border-2 border-gray-200 text-cool-gray-800';
            }
        }"
        id="items"
        class="flex flex-no-wrap items-center space-x-2 overflow-x-hidden overflow-y-hidden whitespace-no-wrap mb-7"
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

    <section class="flex flex-col space-y-5">
        @forelse ($trackers as $tracker)
            <div class="col-span-full flex justify-between items-center text-gray-900 font-medium">
                <div class="flex">
                    <p class="col-span-1 w-5">{{ $loop->index + 1 }}</p>
                    <p class="col-span-1 w-5">{{html_entity_decode('&#8212;')}}</p>
                    @if ($tracker->user->deleted_at != null)
                        <x-icon class="w-6 h-6" icon="user-blocked"/>
                    @endif
                    <p class="flex-1 ml-1">{{ $tracker->user->full_name }}</p>
                </div>
                <p>{{ $tracker->total ?? html_entity_decode('&#8212;') }}</p>
            </div>
        @empty
            <p class="italic text-gray-800">No users found...</p>
        @endforelse
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
