<div>
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-center mt-7 mb-5">
                <button class="
                    py-2 focus:outline-none rounded-l shadow-md w-96
                    @if ($this->filesTabSelected)
                        bg-green-base  text-white
                    @else
                        bg-gray-base  text-gray-800
                    @endif
                " wire:click="changeTab('files')">
                    Files
                </button>
                <button class="
                    py-2 focus:outline-none rounded-r shadow-md w-96
                    @if ($this->trainingTabSelected)
                        bg-green-base  text-white
                    @else
                        bg-gray-base  text-gray-800
                    @endif
                " wire:click="changeTab('training')">
                    Training
                </button>
            </div>


            <div class="text-gray-600 mt-6 md:mt-3 inline-flex items-center">
                @foreach($path as $pathSection)
                    <a href="/trainings/{{user()->department_id}}/{{$pathSection->id}}"
                       class="underline align-baseline">{{$pathSection->title}}</a> /
                @endforeach
            </div>
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
                        :showActions="false"
                    />
                </div>

                <div class="@if ($this->filesTabSelected) hidden @endif">
                    <div class="mt-10">
                        <livewire:castle.manage-trainings.videos
                            key="videos-list-{{ $contents->count() }}"
                            :currentSection="$actualSection"
                            :contents="$contents"
                            :showActions="false"
                        />
                    </div>

                    <div class="mt-10">
                        <livewire:list-files
                            key="training-list-{{ $groupedFiles['training']->count() }}"
                            :files="$groupedFiles['training']"
                            :showDeleteButton="false"
                        />
                    </div>
                </div>


                <div class="@if ($this->trainingTabSelected) hidden @endif">
                    <div class="mt-10">
                        <livewire:list-files
                            key="files-list-{{ $groupedFiles['files']->count() }}"
                            :files="$groupedFiles['files']"
                            :showDeleteButton="false"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
