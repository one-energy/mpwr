@props(['namedRoute'])

<div>
    <div x-data="initDropFileComponent()">
        <div class="flex flex-col flex-grow mb-3 border-gray-300  border-2 border-dashed rounded-md">
            <div id="FileUpload" class="block w-full py-2 px-3 relative bg-white appearance-none ">
                <input type="file" multiple accept=".pdf" name="inputFiles"
                       class="absolute inset-0 z-50 m-0 p-0 w-full h-full outline-none opacity-0"
                       x-on:change="addFile($event.target.files)"
                       x-on:dragover="$el.classList.add('active')" x-on:dragleave="$el.classList.remove('active')" x-on:drop="$el.classList.remove('active')"
                >
                <div class="flex flex-col space-y-2 items-center justify-center">
                    <x-icon class="w-1/3 h-auto" icon="upload-file"></x-icon>
                    <p class="hidden md:block text-gray-700 text-center">Drag your files here or click in this area.</p>
                    <p class="md:hidden text-gray-700 text-center">Click here to select your files.</p>
                </div>
            </div>
            <template x-if="files !== null">
                <div class="grid grid-cols-6 h-auto">
                    <template x-for="(_,index) in Array.from({ length: files.length })">
                        <div class="flex flex-row justify-center items-center space-x-2 bg-gray-200 h-1/8 m-2 p-1 shadow-md rounded-md col-span-1">
                            <span class="truncate font-medium text-gray-900" x-text="files[index].name">Uploading</span>
                            <span class="text-xs text-gray-500" x-text="filesize(files[index].size)">...</span>
                            <template x-if="!isUploaded(files[index])">
                                <div><x-icon class="w-4" icon="x" x-on:click="removeFile(index)"></x-icon></div>
                            </template>
                            <template x-if="isUploaded(files[index])">
                                <div><x-icon class="w-4" icon="check"></x-icon></div>
                            </template>
                        </div>
                    </template>
                </div>
            </template>
        </div>
        <div class="mt-8 w-full text-center">
            <x-button class="w-1/4 bg-green-base" x-on:click="saveFiles">Save</x-button>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        const G_SIZE     = 1000000000;
        const M_SIZE     = 1000000;
        const K_SIZE     = 1000;
        const UPLOAD_URL = '{{ $namedRoute }}';
        function initDropFileComponent (){
            return {
                files: [],
                uploadedFiles: [],
                token: document.head.querySelector('meta[name=csrf-token]').content,
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
                    this.files = [...this.files, ...files];
                },
                removeFile(index){
                    this.files.splice(index, 1)
                },
                isUploaded(listedFile) {
                    if(this.uploadedFiles) {
                        return this.uploadedFiles.indexOf(listedFile.name) >= 0;
                    }
                    return false;
                },
                saveFiles() {
                    if(this.files.length){
                        var formData = new FormData()
                        this.files.forEach((file, index) => {
                            formData.append(`files[${index}]`, file);
                        });
                        fetch(UPLOAD_URL, {
                            method: 'post',
                            headers: {
                                'X-CSRF-TOKEN': this.token
                            },
                            body: formData,
                        }).then((res) => {
                            if (res.ok) {
                                window.$app.alert({title:"Your files have been uploaded", color:"green"});
                                return res.json();
                            } else {
                                window.$app.alert({title:"there is a problem with your upload"})
                                return false;
                            }
                        }).then((fileUploadResponse) => {
                            this.uploadedFiles = fileUploadResponse;
                            console.log('uploaded', fileUploadResponse)
                        });
                    } else {
                        window.$app.alert({title:"Nothing to upload", color:"green"});
                    }
                }
            }
        }
    </script>
@endpush
