<x-app.auth :title="__('Training')">
  <div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="px-4 py-5 sm:px-6">
        <div class="md:flex justify-between">
          <div class="md:flex justify-start">
            <h3 class="text-lg text-gray-900">Manage Training</h3>
          </div>
          <div class="flex md:justify-end sm:justify-start">
            <div class="pt-2 relative md:mx-auto sm:mx-0 text-gray-600">
              @if(user()->isMaster())
                <div class="inline-flex" x-data="{ 'showSectionModal': false }" @keydown.escape="showSectionModal = false" x-cloak>
                  <x-button @click="showSectionModal = true">
                    Add Section
                  </x-button>
                  <div x-show="showSectionModal" wire:loading.remove class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
                    <div x-show="showSectionModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    <div x-show="showSectionModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
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
                              <x-form class="mt-8 inline-flex" :route="route('castle.manage-trainings.storeSection', $actualSection->id)">
                                <x-input label="Title" name="title" type="text"></x-input>
                                <div class="mt-6">
                                  <span class="block w-full rounded-md shadow-sm">
                                    <x-button class="w-full flex ml-4" type="submit" color="green">
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
                @if(!$content)
                  <div class="inline-flex" x-data="{ 'showContentModal': false }" @keydown.escape="showContentModal = false" x-cloak>
                    <x-button @click="showContentModal = true">
                      Add Content
                    </x-button>
                    <div x-show="showContentModal" wire:loading.remove class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
                      <div x-show="showContentModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                      </div>
                      <div x-show="showContentModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
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
                              <div class="inline-grid items-center">
                                <h3>Add a new content to {{$actualSection->title}}</h3>
                                <x-form :route="route('manage-trainings.storeContent', $actualSection->id)">
                                  <div class="grid grid-cols-2 mt-8 gap-2">
                                    <x-input class="col-span-1" label="Title" name="title"></x-input>
                                    <x-input class="col-span-1" label="Video Url" name="video_url"></x-input>
                                    <x-input class="col-span-2" label="Description" name="description"></x-input>
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
                      </div>
                    </div>
                  </div>
                @endif
                @if($content)
                  <div class="inline-flex" x-data="{ 'showContentModal': false }" @keydown.escape="showContentModal = false" x-cloak>
                    <x-button @click="showContentModal = true">
                      Edit Content
                    </x-button>
                    
                    <div x-show="showContentModal" wire:loading.remove class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
                      <div x-show="showContentModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                      </div>
                      <div x-show="showContentModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
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
                              <div class="inline-grid items-center">
                                <h3>Edit the content to {{$actualSection->title}}</h3>
                                <x-form :route="route('castle.manage-trainings.updateContent', $content->id)">
                                  <div class="grid grid-cols-2 mt-8 gap-2">
                                    <x-input class="col-span-1" label="Title" name="title" value="{{$content->title}}"></x-input>
                                    <x-input class="col-span-1" label="Video Url" name="video_url" value="{{$content->video_url}}"></x-input>
                                    <x-input class="col-span-2" label="Description" name="description" value="{{$content->description}}"></x-input>
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
                      </div>
                    </div>
                  </div>
                @endif
              @endif
              <input class="border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                type="search" name="search" placeholder="Search for Training">
              <button type="submit" class="absolute right-0 top-0 mt-5 mr-4">
                <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                  viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
                  width="512px" height="512px">
                  <path
                    d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" />
                </svg>
              </button>
            </div>
          </div>
        </div>
        <div class="text-gray-600 mt-6 md:mt-3 inline-flex items-center">
          @foreach($path as $pathSection)
            <a href="/trainings/{{$pathSection->id}}" class="underline align-baseline">{{$pathSection->title}}</a> / 
          @endforeach
          @if(user()->isMaster())
            <div class="inline-flex" x-data="{ 'editSectionModal': false }" @keydown.escape="editSectionModal = false" x-cloak>
              <button class="ml-4 p-3 rounded-full hover:bg-gray-200" @click="editSectionModal = true">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M18.363 8.464l1.433 1.431-12.67 12.669-7.125 1.436 1.439-7.127 12.665-12.668 1.431 1.431-12.255 12.224-.726 3.584 3.584-.723 12.224-12.257zm-.056-8.464l-2.815 2.817 5.691 5.692 2.817-2.821-5.693-5.688zm-12.318 18.718l11.313-11.316-.705-.707-11.313 11.314.705.709z"/></svg>
              </button>
              <div x-show="editSectionModal" wire:loading.remove class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
                <div x-show="editSectionModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                  <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div x-show="editSectionModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
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
                          <x-form class="mt-8 inline-flex" :route="route('castle.manage-trainings.updateSection', $actualSection->id)">
                            <x-input label="Title" name="title" type="text" value="{{$actualSection->title}}"></x-input>
                            <div class="mt-6">
                              <span class="block w-full rounded-md shadow-sm">
                                <x-button class="w-full flex ml-4" type="submit" color="green">
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
        </div>
        <div class="mt-15">
          <p class="mt-2 p-4 text-lg">
            @if($videoId)
              <div class="w-full text-center embed-container">
                <iframe class="lg:float-right self-center" src="https://www.youtube.com/embed/{{$videoId}}" frameborder='0' height="290" width="500" allowfullscreen></iframe>
              </div>
            @endif
            
            @if($content && $content->title)
              <div class="mt-3 text-xl font-semibold">
                {{$content->title}}
              </div>
              {{$content->description}}
            @endif
          </p>
          <div class="md:grid-cols-2 sm:grid-cols-1 md:row-gap-4 sm:row-gap-0 col-gap-4 inline-grid w-full mt-4">
            @foreach($sections as $section)
              <div class="col-span-1 hover:bg-gray-50">
                <a href="{{route('trainings.index', $section->id)}}">
                  <div class="grid grid-cols-10 row-gap-4 col-gap-4 border-gray-200 md:border-2 border-t-2 p-4 md:rounded-lg">
                    <div class="col-span-9">
                      {{$section->title}}
                    </div>
                    <div class="col-span-1">
                      <x-svg.chevron-right class="w-7 text-gray-500"/>
                    </div>
                  </div>
                </a>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app.auth>