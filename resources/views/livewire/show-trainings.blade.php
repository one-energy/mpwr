<div>
  <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
    <div class="px-4 py-5 sm:px-6">
      <div class="md:flex justify-between">
        <div class="md:flex justify-start">
          <h3 class="text-lg text-gray-900">Training</h3>
        </div>

        <x-search class="w-full" :search="$search" :perPage="false"/>
      </div>


      <div class="text-gray-600 mt-6 md:mt-3 inline-flex items-center">
        @foreach($path as $pathSection)
        <a href="/trainings/{{user()->department_id}}/{{$pathSection->id}}" class="underline align-baseline">{{$pathSection->title}}</a> /
        @endforeach
      </div>
      <div class="mt-15">
        @if(!$path || ($content == null && $sections->isEmpty()) )
        <div class="h-96 ">
          <div class="flex justify-center align-middle">
            <div class="text-sm text-center text-gray-700">
              <x-svg.draw.empty></x-svg.draw.empty>
              No data yet.
            </div>
          </div>
        </div>
        @endif
        <p class="mt-2 p-4 text-lg">
          @if($videoId)
          <div class="w-full text-center embed-container">
            <iframe class="lg:float-right self-center w-full max-w-xl xl:h-80 lg:h-72 lg:w-3/5" src="https://www.youtube.com/embed/{{$videoId}}" frameborder='0' width="500" allowfullscreen></iframe>
          </div>
          @endif

          @if($content && $content->title)
          <div class="mt-3 text-xl font-semibold">
            {{$content->title}}
          </div>
          <div class="grid" id="reader"></div>
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
                  <x-svg.chevron-right class="w-7 text-gray-500" />
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

<script>
  var readerOption = {
    readOnly: true
  }

  var content = <?= $content->description ?? 'null' ?>

  var reader = new Quill('#reader', readerOption);

  if (content) {
    reader.setContents(content);
  }
</script>
