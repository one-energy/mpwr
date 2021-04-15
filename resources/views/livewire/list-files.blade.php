<div>
    @if ($files->isNotEmpty())
        <h3 class="text-xl text-gray-700 font-medium mb-3.5">Files</h3>

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
                    <x-table.th>
                        &nbsp;
                    </x-table.th>
                </x-table.th-tr>
            </x-slot>
            <x-slot name="body">
                @foreach ($files as $file)
                    <x-table.tr :loop="$loop">
                        <x-table.td class="flex align-middle cursor-pointer" wire:click="downloadSectionFile({{$file}})">
                            <x-icon class="h-5 w-auto mr-5" icon="download-file"/>
                            {{ $file->original_name }}
                        </x-table.td>
                        <x-table.td>{{ $file->abbreviatedSize }}</x-table.td>
                        <x-table.td>{{ $file->created_at->format('m/d/Y') }} at {{$file->created_at->format('h:ia')}}</x-table.td>
                    @if ($showDeleteButton)
                            <x-table.td wire:click="onDestroy({{$file}})">
                                <button class="hover:bg-red-200 focus:outline-none p-2 rounded-full cursor-pointer">
                                    <x-svg.trash class="w-5 h-5  text-red-600 fill-current" />
                                </button>
                            </x-table.td>
                        @endif
                    </x-table.tr>
                @endforeach
            </x-slot>
        </x-table>
    @endif

    <x-modal wire:key="deleteFileModal" x-cloak :title="__('Delete File')" description="Are you sure you want to delete this file? You will not be able to recover!">
        <div class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto justify-end space-x-2">
            <div class="flex w-full mt-3 rounded-md shadow-sm sm:mt-0 sm:w-auto">
                <button
                    @click="open = false"
                    class=" rounded-md inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 border-2 text-gray-500 border-gray-500 hover:text-gray-600 hover:border-gray-600 focus:border-gray-500 focus:shadow-outline-gray active:bg-gray-50">
                    Cancel
                </button>
            </div>
            <div class="flex w-full mt-3 rounded-md shadow-sm sm:mt-0 sm:w-auto">
                <button
                    wire:click="removeFile"
                    class="rounded-md inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 border-2 text-red-500 border-red-500 hover:text-red-600 hover:border-red-600 focus:border-red-500 focus:shadow-outline-red active:bg-red-50">
                    Confirm
                </button>
            </div>
        </div>
    </x-modal>
</div>
