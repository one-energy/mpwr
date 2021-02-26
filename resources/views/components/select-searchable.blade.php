@props(['label'])
@php $model = $attributes->wire('model'); @endphp
<div class="relative"
x-data="{
    search: null,
    clientName: null,
    clients: [],
    token: document.head.querySelector('meta[name=csrf-token]').content,
    model: @entangle($model),
    modelName: '{{ $model->value() }}',
    showClients: false,
    loading: false,
    getClientFullName(client) {
        return `${client.last_name}, ${client.first_name}`
    },
    select(client) {
        this.model      = client.id
        this.clientName = this.getClientFullName(client)
        this.closeShowClients()
    },
    clearInput() {
        this.model = null
    },
    fillClients() {
        this.loading = true
        fetch('{{ route('getUsers') }}',{
            method: 'post',  headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        }}).then(res=> res.json()).then( (usersData) => {
            this.clients = usersData

        })
    },
    openShowClients() {
        this.showClients = !this.showClients
        if (this.showClients) {
            this.fillClients()
            this.$nextTick(() => { this.$refs.search?.focus() })
        }
    },
    closeShowClients() {
        this.showClients = false
    }
}"
x-init="() => {
    const clientId = $wire.get(modelName)
    if (clientId) { fillClient(clientId) }
    $watch('model', value => {
        if (value) { fillClient(value) }
        clientName = null
    })
}">
    <div class="relative">
        <x-input
            :label="$label ??  'Select a client'"
            :name="$model->value()"
            x-on:click="openShowClients"
            x-model="clientName"
            readonly
            placeholder="Search here"
        />
        <div class="absolute top-9 right-0 mr-3 flex items-center bg-white">
            <x-icon name="close" x-show="model" x-on:click="clearInput()" class="w-5 h-5 cursor-pointer text-red-500" />
            <x-icon name="selector" x-show="!loading" class="text-gray-400" />
            <x-svg.spinner x-show="loading" color="#666" class="w-5 h-5" />
        </div>
    </div>
    <div class="absolute z-50 border-t mt-1 w-full rounded-lg bg-white shadow-lg"
        x-show="showClients"
        x-on:click.away="closeShowClients">
        <div class="p-2">
            <x-search
                x-ref="search"
                filled
                x-on:input.debounce.750ms="fillClients"
                x-model="search"
                placeholder="Search here"
            />
        </div>
        <ul tabindex="-1" class="max-h-60 overflow-auto soft-scrollbar text-base leading-6 focus:outline-none sm:text-sm sm:leading-5">
            <template x-for="client in clients" :key="'client' + client.id">
                <li class="text-gray-700 cursor-pointer hover:text-indigo-600 select-none relative py-2 pl-3 pr-9"
                    x-on:click="select(client)">
                    <span class="block truncate"
                        :class="{
                            'font-semibold text-indigo-600': client.id === model,
                            'font-normal': client.id !== model
                        }">
                        <span x-text="client.last_name"></span>,
                        <span x-text="client.first_name"></span>
                        <span x-text="client.email" class="block"></span>
                    </span>
                    <span x-show="client.id === model" class="text-indigo-600 absolute inset-y-0 right-0 flex items-center pr-4">
                        <x-icon name="check" />
                    </span>
                </li>
            </template>
            <li x-show="!clients.length" class="text-gray-900 cursor-default select-none relative py-2 pl-3 pr-9">
                <span x-text="loading ? 'loading..':'no records'" class="font-normal block truncate"></span>
            </li>
        </ul>
    </div>
</div>
