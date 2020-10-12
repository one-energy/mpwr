<x-app.auth :title="__('Training')">
  <div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="px-4 py-5 sm:px-6">
        <div class="md:flex justify-between">
          <div class="md:flex justify-start">
            <h3 class="text-lg text-gray-900">Training</h3>
          </div>
          <div class="flex md:justify-end sm:justify-start">
            <div class="pt-2 relative md:mx-auto sm:mx-0 text-gray-600">
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
            <a href="/trainings/{{user()->department_id}}/{{$pathSection->id}}" class="underline align-baseline">{{$pathSection->title}}</a> / 
          @endforeach
        </div>
        <div class="mt-15">
          <p class="mt-2 p-4 text-lg">
            @if($videoId)
              <div class="w-full text-center embed-container">
                <iframe class="lg:float-right self-center ml-2" src="https://www.youtube.com/embed/{{$videoId}}" frameborder='0' height="290" width="500" allowfullscreen></iframe>
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
                <a href="{{route('trainings.index', [
                    'department' => user()->department_id,
                    'section'    => $section->id
                  ])}}">
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