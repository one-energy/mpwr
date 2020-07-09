<x-app.auth :title="__('Dashboard')" :header="__('Dashboard')">
    <x-card>


        <button class="px-2 py-1 text-green-700 bg-green-100" type="button" x-data
            x-on:click="$dispatch('confirm', {from: $el})"
            x-on:confirmed="alert('oi')"
        >
            Confirm
        </button>

        <hr>

        {{-- Modal --}}
        <div class="text-lg text-red-400" x-data="{open : false, target: null}" x-show="open"
            x-on:confirm.window="open = true; target = $event.detail.from"
            >
            Modal


            <button class="px-2 py-1 text-blue-700 bg-blue-100" type="button"
                x-on:click="target.dispatchEvent(new CustomEvent('confirmed'))"
            >
                OK
            </button>

            <button class="px-2 py-1 text-red-700 bg-red-100" type="button" x-on:click="open = false">
                Cancel
            </button>
        </div>




    </x-card>
</x-app.auth>
