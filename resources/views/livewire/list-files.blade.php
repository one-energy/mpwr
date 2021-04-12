<div>
    <x-table>
        <x-slot name="header">
            <x-table.th-tr>
                <x-table.th-searchable by="original_name" :sortedBy="$sortBy" :direction="$sortDirection">
                    @lang('Name')
                </x-table.th-searchable>
                <x-table.th-searchable by="size" :sortedBy="$sortBy" :direction="$sortDirection">
                    @lang('size')
                </x-table.th-searchable>
                <x-table.th-searchable by="created_at" :sortedBy="$sortBy" :direction="$sortDirection">
                    @lang('uploaded_at')
                </x-table.th-searchable>
            </x-table.th-tr>
        </x-slot>
        <x-slot name="body">
            @foreach ($files as $file)
                <x-table.tr :loop="$loop">
                    <x-table.td class="flex align-middle" wire:click="downloadSectionFile({{$file}})">
                        <x-icon class="h-5 w-auto mr-5" icon="download-file"/>
                        {{ $file->original_name }}
                    </x-table.td>
                    <x-table.td>{{ $file->abbreviatedSize }}</x-table.td>
                    <x-table.td>{{ $file->created_at->format('d/m/Y') }} at {{$file->created_at->format('h:ia')}}</x-table.td>
                    <x-table.td wire:click="removeFile({{$file}})">
                        <button class="hover:bg-red-200 focus:outline-none p-2 rounded-full">
                            {{-- <x-icon icon="trash"/> --}}
                            <x-svg.trash class="w-5 h-5  text-red-600 fill-current" />
                        </button>
                    </x-table.td>
                </x-table.tr>
            @endforeach
        </x-slot>
    </x-table>
</div>
