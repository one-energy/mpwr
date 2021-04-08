<div>
    <div x-data="initDropFileComponent()"  class="flex flex-col flex-grow mb-3 border-gray-300  border-2 border-dashed rounded-md">
        <div id="FileUpload" class="block w-full py-2 px-3 relative bg-white appearance-none ">
            <input type="file" multiple accept=".pdf"
                   class="absolute inset-0 z-50 m-0 p-0 w-full h-full outline-none opacity-0"
                   x-on:change="addFile($event.target.files)"
                   x-on:dragover="$el.classList.add('active')" x-on:dragleave="$el.classList.remove('active')" x-on:drop="$el.classList.remove('active')"
            >
            <div class="flex flex-col space-y-2 items-center justify-center">
                <x-icon class="w-1/3 h-auto" icon="upload-file"></x-icon>
                <p class="text-gray-700 text-center">Drag your files here or click in this area.</p>
                <a href="javascript:void(0)" class="flex items-center mx-auto py-2 px-4 text-white text-center font-medium border border-transparent rounded-md outline-none bg-green-base">Select a file</a>
            </div>
        </div>
        <template x-if="files !== null">
            <div class="grid grid-cols-6 h-auto">
                <template x-for="(_,index) in Array.from({ length: files.length })">
                    <div class="flex flex-row justify-center items-center space-x-2 bg-gray-200 h-1/8 m-2 p-1 shadow-md rounded-md col-span-1">
                        <span class="truncate font-medium text-gray-900" x-text="files[index].name">Uploading</span>
                        <span class="text-xs text-gray-500" x-text="filesize(files[index].size)">...</span>
                        <div ><x-icon class="w-4" icon="x" x-on:click="removeFile(index)"></x-icon></div>
                    </div>
                </template>
            </div>
        </template>
    </div>
    @push('scripts')
        <script>
            const G_SIZE = 1000000000;
            const M_SIZE = 1000000;
            const K_SIZE = 1000;
            function initDropFileComponent (){
                return {
                    files: null,
                    filesize(fileSize) {
                        if(fileSize <= K_SIZE){
                            return fileSize + ' B';
                        }

                        if(fileSize <= M_SIZE){
                            return (fileSize/K_SIZE).toFixed(2) + ' KB';
                        }

                        if(fileSize <= G_SIZE){
                            return (fileSize/M_SIZE).toFixed(2) + ' MB';
                        }

                        return (fileSize/G_SIZE).toFixed(2) + ' GB';
                    },
                    addFile(files) {
                        files.forEach(file => {
                            if(!this.files){
                                this.files = new Array(file)
                            }else{
                                this.files.push(file)
                            }
                        });
                    },
                    removeFile(index){
                        this.files.splice(index, 1)
                    }
                }
            }
        </script>
    @endpush
</div>
