<div>
    <div class="grid grid-cols-1 gap-y-3 sm:gap-x-3 lg:gap-x-5 md:grid-cols-3">
        @foreach ($sections as $key => $section)
            <a href="{{ route($showActions ? 'castle.manage-trainings.index' : 'trainings.index',[
                'department' => $section->department_id,
                'section'    => $section->id,
            ])}}">
                <div x-data="{editing: false}" wire:key="section-field-{{ $section->id }}" class="border-cool-gray-300 @if (!$section->department_folder) bg-gray-50 hover:bg-gray-100 @else hover:bg-gray-50 @endif  border-2 p-3 cursor-pointer flex items-center">
                        <div class="text-center flex flex-1 items-center space-x-3.5 text-base" x-on:cancel-edit-input.window="editing = false">
                            @if ($showActions && $section->department_folder && user()->hasAnyRole(['Admin', 'Owner', 'Department Manager']))
                                <button class="hover:bg-red-200 focus:outline-none p-2 rounded-full" wire:click.prevent="onDestroy({{ $section->id }})">
                                    <x-svg.trash class="w-5 h-5  text-red-600 fill-current" />
                                </button>
                                <button class="hover:bg-gray-100 p-3 rounded-full focus:outline-none" wire:click.prevent="" x-on:click="editing = !editing">
                                        <x-svg.pencil class="w-4 h-4 fill-current text-gray-800" />
                                </button>
                            @endif                        
                            <p x-show="!editing">{{ $section->title }}</p>
                            <x-input-confirmation x-show="editing" label="name" name="sections.{{$key}}.title" wire x-cloak wire:click.prevent=""/>
                        </div>
                    <div>
                        <x-svg.chevron-right class="text-gray-500 font-bold h-6 w-6" />
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div x-data="modal()" @on-destroy-section.window="open" x-cloak>
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
                    <h3 class="text-left" x-text="`Do you want to delete ${section.title} section?`"></h3>
                    <p
                        class="text-left mt-4 text-base text-gray-700"
                         x-text="`If you delete this section all content into ${section.title} will be pass to {{$currentSection->title}}`">
                    </p>
                    <div class="mr-4 mb-4 inline-flex space-x-4 float-right mt-8">
                        <x-button class="w-full flex ml-4" @click="close">
                            {{ __('No') }}
                        </x-button>
                        <x-form :route="$sectionDestroyRoute" delete x-data="{deleting: false}">
                            <x-button class="w-full flex ml-4" type="submit" color="green">
                                {{ __('Yes') }}
                            </x-button>
                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = () => ({
        section: {},
        show: false,
        open(section) {
            this.section = {...event.detail.section};
            this.show = true;
        },
        close() { this.show = false },
        isOpen() { return this.show === true },
    })
</script>
