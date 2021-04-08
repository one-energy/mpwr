<h3 class="text-xl text-cool-gray-700 font-medium mb-3.5">Videos</h3>

<div class="grid grid-cols-1 gap-y-3 sm:gap-x-3 lg:gap-x-5 md:grid-cols-2 xl:grid-cols-3">
    @foreach ($contents as $content)
    <div class="grid grid-rows-2 lg:grid-rows-1 lg:grid-cols-5 lg:gap-x-3 border-2 border-cool-gray-300 rounded p-4">
        <div class="col-span-full lg:col-span-2 relative  bg-cool-gray-800">
            <iframe class="absolute inset-0 w-full h-full" src="https://www.youtube.com/embed/#" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="lg:col-span-3">
            <div class="flex flex-col h-full">
                <div class="flex-1">
                    <h5 class="text-cool-gray-800 font-medium mb-1 mt-3 lg:mt-0">{{ $content->title }}</h5>
                    <p class="text-sm">
                        {{ Str::limit($content->decoded_description, 100) }}
                    </p>
                </div>
                <div class="flex justify-end space-x-1 mt-1">
                    <button class="hover:bg-cool-gray-100 focus:outline-none p-2 rounded-full" @click="console.log('editando haha')">
                        <x-svg.pencil class="w-4 h-4 fill-current text-cool-gray-800" />
                    </button>
                    <button class="hover:bg-red-200 focus:outline-none p-2 rounded-full">
                        <x-svg.trash class="w-5 h-5  text-red-600 fill-current" />
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
    var readerOption = {
      readOnly: true
    }

    var options = {
      height: "200px",
      theme: 'snow'
    };

    var content = 123

    var quill = new Quill('#editor', options);
    var reader = new Quill('#reader', readerOption);

    if(content){
      quill.setContents(content);
      reader.setContents(content);
    }

    var form = document.getElementById("formContent");
    form.onsubmit = function() { // onsubmit do this first
      var description = document.querySelector('textarea[name="description"]'); // set name input var
      description.value = JSON.stringify(quill.getContents()); // populate name input with quill data
      return true; // submit form
    }
  </script>
