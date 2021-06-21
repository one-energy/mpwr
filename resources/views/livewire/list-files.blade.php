<div>
    @if ($files->isNotEmpty())
        <h3 class="text-xl text-gray-700 font-medium mb-3.5">Files</h3>

        <div class="overflow-x-auto">
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
                        @if ($showDeleteButton)
                            <x-table.th>
                                &nbsp;
                            </x-table.th>
                        @endif
                    </x-table.th-tr>
                </x-slot>
                <x-slot name="body">
                    @foreach ($files as $file)
                        <x-table.tr :loop="$loop">
                            <x-table.td class="cursor-pointer" wire:click="downloadSectionFile({{$file}})">
                                <div class="flex align-middle">
                                    <x-icon class="h-5 w-auto mr-5" icon="download-file"/>
                                    {{ $file->original_name }}
                                </div>
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
        </div>
    @endif

        <div x-data="{ open : @entangle('showDeleteModal').defer }"
             x-cloak
             x-show="open"
             class="fixed inset-x-0 bottom-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
        >
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="px-4 pt-5 pb-4 overflow-hidden transition-all transform bg-white rounded-lg shadow-xl sm:max-w-lg sm:w-full sm:p-6"
                role="dialog"
                aria-modal="true"
                aria-labelledby="modal-headline"
                @keydown.escape.window="open = false"
                @click.away="open = false"
            >
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-headline">
                            Delete File
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm leading-5 text-gray-500">
                                Are you sure you want to delete this file? You will not be able to recover!
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
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
                </div>
            </div>
        </div>
</div>
