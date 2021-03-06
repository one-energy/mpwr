@props(['namedRoute', 'meta' => []])

<div>
    <div x-data="initDropFileComponent()">
        <textarea hidden x-ref="meta">@json($meta)</textarea>
        <div class="flex flex-col flex-grow mb-3 border-gray-300  border-2 border-dashed rounded-md">
            <div id="FileUpload" class="block w-full py-2 px-3 relative bg-white appearance-none cursor-pointer">
                <input type="file" multiple accept="image/*,application/pdf,.odp,.otp,.pptx,.ppt,.pps,.ppsx,.pot,.potx" name="inputFiles"
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
        </div>
        <template x-if="files !== null">
            <div class="h-auto">
                <template x-for="(file, index) in files">
                    <div class="flex flex-start items-center space-x-2 mb-">
                        <template x-if="!isUploaded(file)">
                            <div class="cursor-pointer">
                                <x-icon class="w-4 text-red-500" icon="x" x-on:click="removeFile(index)" />
                            </div>
                        </template>
                        <span class="truncate font-medium text-gray-900" x-text="file.name">Uploading</span>
                        <span class="text-xs text-gray-500" x-text="filesize(file.size)">...</span>
                        <template x-if="isUploaded(file)">
                            <div>
                                <x-icon class="w-4  text-green-400" icon="check" />
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </template>
        <div
            class="mt-8 w-full text-center flex justify-center"
            :class="{'pointer-events-none': loading, 'cursor-not-allowed': loading}"
        >
            <x-button class="bg-green-base" x-on:click="saveFiles">
                <div class="flex items-center" :class="{'flex-row-reverse': loading}">
                    <template x-if="loading">
                        <x-icon icon="spinner" class="w-5 h-5 mr-3" />
                    </template>
                    Save
                </div>
            </x-button>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        const G_SIZE     = 1000000000;
        const M_SIZE     = 1000000;
        const K_SIZE     = 1000;
        const UPLOAD_URL = '{{ $namedRoute }}';

        function initDropFileComponent() {

            return {
                files: [],
                uploadedFiles: [],
                loading: false,
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
                    return this.uploadedFiles
                     ? this.uploadedFiles.indexOf(listedFile.name) >= 0
                     : false;
                },
                async saveFiles() {
                    this.loading = true;

                    if(this.files.length === 0) {
                        window.$app.alert({ title: 'Nothing to upload', color: 'green' });
                        this.loading = false;
                        return;
                    }

                    const formData = new FormData();
                    const metadata = JSON.parse(this.$refs.meta.value);

                    Object.keys(metadata).map(key => formData.append(`meta[${key}]`, metadata[key]))
                    this.files.forEach((file, index) => formData.append(`files[${index}]`, file));

                    try {
                        await axios.post(UPLOAD_URL, formData, {
                            headers: {
                                'X-CSRF-TOKEN': this.token,
                                'Content-Type': 'application/json'
                            }
                        });
                        window.$app.alert({ title:'Your files have been uploaded', color:'green' });
                        window.Livewire.emit('filesUploaded');
                        this.files = [];
                    } catch (error) {
                        let message = 'There is a problem with your upload';

                        if (error.response.status === 422) {
                            const { errors } = error.response.data;

                            message = Object.keys(errors).length > 1
                                ? 'There is an extension that is not supported'
                                : `This extension isn't supported`;
                        }
                        window.$app.alert({ title: message, color: 'red' })
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
@endpush
