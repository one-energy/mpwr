<div>
    <h3 class="text-xl text-gray-700 font-medium mb-3.5">Videos</h3>

    <div class="grid grid-cols-1 gap-y-3 sm:gap-x-3 lg:gap-x-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($contents as $content)
            <div wire:key="video-{{ $content->id }}" class="grid grid-rows-2 lg:grid-rows-1 lg:grid-cols-5 lg:gap-x-3 border-2 border-gray-300 rounded p-4">
                <div class="col-span-full lg:col-span-2 relative  bg-gray-800">
                    <iframe class="absolute inset-0 w-full h-full" src="https://www.youtube.com/embed/#" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="lg:col-span-3">
                    <div class="flex flex-col h-full">
                        <div class="flex-1">
                            <h5 class="text-gray-800 font-medium mb-1 mt-3 lg:mt-0">{{ $content->title }}</h5>
                            <p class="text-sm">
                                {{ Str::limit($content->description, 100) }}
                            </p>
                        </div>
                        <div class="flex justify-end space-x-1 mt-1">
                            <button class="hover:bg-gray-100 focus:outline-none p-2 rounded-full" wire:click="onEdit({{ $content->id }})">
                                <x-svg.pencil class="w-4 h-4 fill-current text-gray-800" />
                            </button>
                            <button class="hover:bg-red-200 focus:outline-none p-2 rounded-full" wire:click="onDestroy({{ $content->id }})">
                                <x-svg.trash class="w-5 h-5  text-red-600 fill-current" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div x-data="editVideoHandler()" @on-edit-content.window="open" x-cloak>
        <div x-show="isOpen()" wire:loading.remove class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
            <div x-show="isOpen()" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div x-show="isOpen()" @click.away="close" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" @click="close" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150" aria-label="Close">
                        <x-svg.x class="w-5 h-5" />
                    </button>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="mb-3.5 font-medium">Video Editing</h3>
                    <x-form id="videoForm" :route="$updateRoute">
                        <div class="grid grid-cols-2 mt-8 gap-2 mb-4">
                            <x-input class="col-span-1" label="Title" name="content_title" x-model="content.title" />
                            <x-input class="col-span-1" label="Video Url" name="video_url" x-model="content.video_url"/>
                            <x-text-area class="col-span-2" label="Description" name="description" x-model="content.description"></x-text-area>
                        </div>
                        <div class="mt-6">
                            <span class="block w-full rounded-md shadow-sm">
                                <x-button class="w-full flex" type="submit" color="green">
                                    {{ __('Save') }}
                                </x-button>
                            </span>
                        </div>
                    </x-form>
                </div>
            </div>
        </div>
    </div>

    <x-modal x-cloak :title="__('Delete Video')" description="Are you sure you want to delete this video? You will not be able to recover!">
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
                    wire:click="destroyVideo({{ $selectedContent }})"
                    class="rounded-md inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 border-2 text-red-500 border-red-500 hover:text-red-600 hover:border-red-600 focus:border-red-500 focus:shadow-outline-red active:bg-red-50">
                    Confirm
                </button>
            </div>
        </div>
    </x-modal>
</div>


<script>
    const editVideoHandler = () => ({
        content: {},
        show: false,
        open(content) {
            this.content = {...event.detail.content};

            this.show = true;
        },
        close() { this.show = false },
        isOpen() { return this.show === true },
    });
  </script>
