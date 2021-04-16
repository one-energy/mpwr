<section class="p-4 border-2 border-gray-400 rounded">
    <h3 class="text-center text-lg text-gray-900 font-medium mb-5">Leaderboard</h3>

    <section
        x-data="{
            pills: ['Doors', 'Hours', 'Sets', 'Sits', 'Set Closes'],
            selectedPill: @entangle('selectedPill'),
            isTheSelectedPill(pill) {
                return this.selectedPill === pill;
            },
            selectPill(pill) {
                if (pill === this.selectedPill) return;
                this.selectedPill = pill;
            },
            get greenPill() {
                return 'bg-green-450 text-white';
            },
            get grayPill() {
                return 'bg-white border-2 border-gray-400 text-cool-gray-800';
            }
        }"
        id="items"
        class="flex flex-no-wrap items-center space-x-2 overflow-x-hidden overflow-y-hidden whitespace-no-wrap px-5 mb-7"
    >
        <template x-for="pill in pills" :key="pill">
            <div
                class="font-medium text-sm px-5 py-1 rounded shadow-md cursor-pointer"
                :class="isTheSelectedPill(pill) ? greenPill : grayPill"
                style="min-width: fit-content; flex: 0 0 auto"
                x-text="pill"
                @click="selectPill(pill)"
            >
            </div>
        </template>
    </section>

    <section class="flex flex-col space-y-5 px-5">
        @foreach (collect()->times(10) as $item)
            <div class="col-span-full flex justify-between items-center text-gray-900 font-medium">
                <p class="col-span-1 w-5">{{ $item }}</p>
                <p class="flex-1 ml-4">Blade Cannon</p>
                <p>230</p>
            </div>
        @endforeach
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