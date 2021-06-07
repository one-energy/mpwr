<div>
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center mt-7 mb-5">
            <button class="
                py-2 focus:outline-none rounded-l shadow-md w-96
                @if ($this->filesTabSelected)
                    bg-green-base text-white
                @else
                    bg-gray-base  text-gray-800
                @endif
            " wire:click="changeTab('files')">
                Files
            </button>
            <button class="
                py-2 focus:outline-none rounded-r shadow-md w-96
                @if ($this->trainingTabSelected)
                    bg-green-base text-white
                @else
                    bg-gray-base  text-gray-800
                @endif
            " wire:click="changeTab('training')">
                Training
            </button>
        </div>

        <div class="px-4 py-5 sm:px-6">
            <div class="flex flex-col justify-end @if(user()->hasAnyRole(['Admin', 'Owner'])) lg:justify-between @endif xl:flex-row">

                @if (user()->hasAnyRole(['Admin', 'Owner']))
                    <div>
                        <form action="{{ route('castle.manage-trainings.index')}}" method="get">
                            <div class="flex items-center justify-end">
                                <label for="department" class="block mt-1 text-xs font-medium leading-5 text-gray-700">
                                    Department
                                </label>
                                <div class="relative inline-block ml-2 text-left">
                                    <select name="department" x-data="{}" @change="$wire.changeDepartment($el.value)" class="block w-full py-1 text-lg text-gray-500 transition duration-150 ease-in-out rounded-lg form-select">
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
                
                <div class="mt-3 space-x-2 sm:flex justify-between xl:mt-0">
                    <div class="flex flex-none justify-end mb-3.5 sm:justify-start lg:mr-6">
                        @if (user()->hasRole('Region Manager') && !$actualSection->isDepartmentSection())
                            <x-castle.manage-trainings.add-section
                                :actualSection="$actualSection"
                                :contents="$contents"
                            />
                        @endif
                        @if(user()->hasAnyRole(['Admin', 'Owner', 'Department Manager']))
                            <x-castle.manage-trainings.add-section
                                :actualSection="$actualSection"
                                :contents="$contents"
                            />
                        @endif
                    </div>
                    <div class="flex grow justify-end">
                        <x-search class="block w-full border-1  border-cool-gray-400 rounded-sm px-10 form-input md:w-96 sm:text-sm sm:leading-5" :search="$search" :perPage="false"/>
                    </div>
                </div>
            </div>

            <div class="flex mt-5">
                @foreach($path as $pathSection)
                    <a href="/castle/manage-trainings/list/{{$departmentId}}/{{$pathSection->id}}" class="text-gray-500 align-baseline">{{$pathSection->title}}</a>
                    <span class="text-gray-500">/</span>
                @endforeach
            </div>

            <div class="mt-5">
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
                        :showActions="$this->canSeeActions"
                    />
                </div>

                <div class="mt-10 @if ($actualSection->files->isEmpty()) hidden @endif">
                    <livewire:list-files
                        key="files-list-{{ $actualSection->files->count() }}"
                        :files="$actualSection->files"
                        :showDeleteButton="$this->canSeeActions"
                    />
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const addContentHandler = () => ({
        show: @entangle('showAddContentModal'),
        topTab: @entangle('selectedTab'),
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
            return 'bg-green-base text-white';
        },
        get inactiveTabColors() {
            return 'bg-gray-300 text-gray-800';
        },
    });
</script>
