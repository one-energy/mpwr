<div>
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center mt-7 mb-5">
            <button class="
                py-2 focus:outline-none rounded-l shadow-md w-96
                @if ($this->filesTabSelected)
                    bg-green-450 text-white
                @else
                    bg-gray-base  text-gray-800
                @endif
            " wire:click="changeTab('files')">
                Files
            </button>
            <button class="
                py-2 focus:outline-none rounded-r shadow-md w-96
                @if ($this->trainingTabSelected)
                    bg-green-450 text-white
                @else
                    bg-gray-base  text-gray-800
                @endif
            " wire:click="changeTab('training')">
                Training
            </button>
        </div>

        <div class="px-4 py-5 sm:px-6">
            <div class="flex flex-col lg:flex-row lg:justify-end">
                <div class="order-last lg:order-none md:flex-1 xl:ml-1/6">
                    @foreach($path as $pathSection)
                        <a href="/castle/manage-trainings/list/{{$departmentId}}/{{$pathSection->id}}" class="text-gray-500 align-baseline">{{$pathSection->title}}</a>
                        <span class="text-gray-500">/</span>
                    @endforeach
                    @if(user()->role == "Admin" || user()->role == "Owner" || user()->role == "Department Manager")
                        <div class="inline-flex" x-data="{ 'editSectionModal': false }" @keydown.escape="editSectionModal = false" x-cloak>
                            <button class="p-3 rounded-full  hover:bg-gray-100" @click="editSectionModal = true">
                                <x-svg.pencil class="w-4 h-4 fill-current text-gray-800" />
                            </button>
                            <div x-show="editSectionModal" wire:loading.remove class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
                                <div x-show="editSectionModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>
                                <div x-show="editSectionModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative bottom-16 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                                    <div class="absolute top-0 right-0 pt-4 pr-4">
                                        <button type="button" x-on:click="editSectionModal = false; setTimeout(() => open = true, 1000)" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150" aria-label="Close">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="px-4 py-5 sm:p-6">
                                        <div class="flex justify-between">
                                            <div class="flex justify-start">
                                                <div class="flex-grid items-center">
                                                    <h3>Edit the section {{$actualSection->title}}</h3>
                                                    <x-form class="mt-8 inline-flex" :route="route('castle.manage-trainings.updateSection', $actualSection->id)" put>
                                                        <div class="flex space-x-2">
                                                            <x-input label="Title" name="title" type="text" value="{{$actualSection->title}}"/>
                                                            <div class="mt-6">
                                                                <span class="block w-full rounded-md shadow-sm">
                                                                    <x-button class="w-full flex" type="submit" color="green">
                                                                        {{ __('Save') }}
                                                                    </x-button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </x-form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="flex justify-center lg:justify-end mb-3.5 lg:mr-6">
                    @if(user()->role == "Admin" || user()->role == "Owner" || user()->role == "Department Manager")
                        <div class="mr-4" x-data="{ 'showSectionModal': false }" @keydown.escape="showSectionModal = false" x-cloak>
                            <button class="bg-green-450 text-white focus:outline-none font-medium text-sm rounded shadow-md px-4 md:px-5 py-2.5" @click="showSectionModal = true">
                                Add Section
                            </button>
                            <div x-show="showSectionModal" wire:loading.remove class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
                                <div x-show="showSectionModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>
                                <div x-show="showSectionModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative bottom-16 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                                    <div class="absolute top-0 right-0 pt-4 pr-4">
                                        <button type="button" x-on:click="showSectionModal = false; setTimeout(() => open = true, 1000)" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150" aria-label="Close">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="px-4 py-5 sm:p-6">
                                        <div class="flex justify-between">
                                            <div class="flex justify-start">
                                                <div class="flex-grid items-center">
                                                    <h3>Add a new section to {{$actualSection->title}}</h3>
                                                    <x-form class="mt-8 inline-flex" :route="route('castle.manage-trainings.storeSection',[
                                                            'section' => $actualSection->id
                                                            ])">
                                                        <div class="flex space-x-2">
                                                            <x-input label="Title" name="title" type="text"/>
                                                            <div class="mt-6">
                                                                <span class="block w-full rounded-md shadow-sm">
                                                                    <x-button class="w-full flex" type="submit" color="green">
                                                                        {{ __('Save') }}
                                                                    </x-button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </x-form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!$contents)
                            <div class="col-span-1" x-data="{ 'showContentModal': false }" @keydown.escape="showContentModal = false" x-cloak>
                                <button class="bg-green-450 text-white focus:outline-none font-medium text-sm rounded shadow-md px-4 md:px-5 py-2.5" @click="showContentModal = true">
                                    Add Section
                                </button>
                                <div x-show="showContentModal" wire:loading.remove class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
                                    <div x-show="showContentModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                    </div>
                                    <div x-show="showContentModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative bottom-16 p-4 overflow-y-auto max-h-96 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                                        <div class="absolute top-0 right-0 pt-4 pr-4">
                                            <button type="button" x-on:click="showContentModal = false; setTimeout(() => open = true, 1000)" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150" aria-label="Close">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="sm:p-6">
                                            <div class="flex justify-between">
                                                <div class="flex justify-start">
                                                    <div class="w-full inline-grid items-center">
                                                        <h3>Add a new content to {{$actualSection->title}}</h3>
                                                        <x-form id="formContent" :route="route('castle.manage-trainings.storeContent', $actualSection->id)">
                                                            <div class="grid grid-cols-2 mt-8 gap-2 mb-4">
                                                                <x-input class="col-span-1" label="Title" name="content_title"/>
                                                                <x-input class="col-span-1" label="Video Url" name="video_url"/>
                                                                <x-text-area class="col-span-2" label="Description" name="description" hidden></x-text-area>
                                                            </div>
                                                            <div class="grid" id="editor"></div>
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
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($contents)
                            <button
                                class="bg-green-450  text-white font-medium text-sm rounded shadow-md px-4 md:px-5 py-2.5 focus:outline-none"
                                wire:click="$set('showAddContentModal', true)"
                            >
                                Add Content
                            </button>
                            <div class="col-span-1 sm:col-span-3" x-data="addContentHandler()" x-cloak>
                                <div x-show="show" @keydown.escape.window="close" wire:loading.remove class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
                                    <div x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                    </div>
                                    <div
                                        @click.away="close" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        class="relative bottom-16 p-4 overflow-y-auto bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full"
                                    >
                                        <div class="absolute top-0 right-0 pt-4 pr-4">
                                            <button type="button" @click="close" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150" aria-label="Close">
                                                <x-svg.x class="w-5 h-5" />
                                            </button>
                                        </div>
                                        <div class="sm:p-6">
                                            <div class="flex mt-4 mb-7">
                                                <button
                                                    class="py-2 focus:outline-none rounded-l shadow-md w-96"
                                                    :class="tabVideoSelected ? activeTabColors : inactiveTabColors"
                                                    @click="changeTab('video')"
                                                >
                                                    Video
                                                </button>
                                                <button
                                                    class="py-2 focus:outline-none rounded-r shadow-md w-96"
                                                    :class="tabFileSelected ? activeTabColors : inactiveTabColors"
                                                    @click="changeTab('file')"
                                                >
                                                    File
                                                </button>
                                            </div>

                                            <div x-show="tabVideoSelected">
                                                <x-form wire:submit.prevent="storeVideo">
                                                    <div class="flex flex-col space-y-5 my-4">
                                                        <x-input label="Title" wire:model.defer="video.title" name="video.title" />
                                                        <x-input label="Video Url" wire:model.defer="video.video_url" name="video.video_url"/>
                                                        <x-text-area label="Description" wire:model.defer="video.description" name="video.description"></x-text-area>
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

                                            <div x-show="tabFileSelected">
                                                <x-drop-file :namedRoute="route('uploadSectionFile', [
                                                    'section' => $actualSection->id
                                                ])" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="flex justify-center md:flex-none">
                    <x-search class="block w-full border-1  border-cool-gray-400 rounded-sm px-10 form-input md:w-96 sm:text-sm sm:leading-5" :search="$search" :perPage="false"/>
                </div>
            </div>
            {{-- @if(user()->role == "Admin" || user()->role == "Owner")
                <form action="{{ route('castle.manage-trainings.changeDepartment')}}" method="POST">
                    @csrf
                    <div class="flex justify-end" x-data="{ sortOptions: false }">
                        <label for="department" class="block mt-1 text-xs font-medium leading-5 text-gray-700">
                            Department
                        </label>
                        <div class="relative inline-block ml-2 text-left">
                            <select name="department" onchange="this.form.submit()" class="block w-full py-1 text-lg text-gray-500 transition duration-150 ease-in-out rounded-lg form-select">
                                @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            @endif --}}

            <div class="mt-15">
                @if(!$path || ($contents == null && $sections->isEmpty()) )
                    <div class="h-96">
                        <div class="flex justify-center align-middle">
                            <div class="text-sm text-center text-gray-700">
                                <x-svg.draw.empty></x-svg.draw.empty>
                                No data yet.
                            </div>
                        </div>
                    </div>
                @endif

                <div>
                    <livewire:castle.manage-trainings.folders
                        key="folders-list-{{ $sections->count() }}"
                        :currentSection="$actualSection"
                        :sections="$sections"
                    />
                </div>

                <div class="mt-10 @if ($this->filesTabSelected) hidden @endif">
                    <livewire:castle.manage-trainings.videos
                        key="videos-list-{{ $contents->count() }}"
                        :currentSection="$actualSection"
                        :contents="$contents"
                    />
                </div>

                <div class="mt-10 @if ($actualSection->files->isEmpty()) hidden @endif">
                    <h3 class="text-xl text-gray-700 font-medium mb-3.5">Files</h3>
                    <livewire:list-files
                        key="files-list-{{ $actualSection->files->count() }}"
                        :files="$actualSection->files"
                        :showDeleteButton="true"
                    />
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const addContentHandler = () => ({
        show: @entangle('showAddContentModal'),
        selectedTab: 'video',
        changeTab(tab) {
            this.selectedTab = tab;
        },
        open() {
            this.show = true;
        },
        close() {
            this.show = false;
        },
        get tabVideoSelected() {
            return this.selectedTab === 'video';
        },
        get tabFileSelected() {
            return this.selectedTab === 'file';
        },
        get activeTabColors() {
            return 'bg-green-450 text-white';
        },
        get inactiveTabColors() {
            return 'bg-gray-300 text-gray-800';
        },
    });
</script>
