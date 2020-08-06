<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
    <x-form :route="route('profile.update')" put>
        <x-card>
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            {{ __('Profile') }}
                        </h3>
                        <p class="mt-1 text-sm leading-5 text-gray-500">
                            {{ __('Main information about yourself.') }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="px-4 sm:px-0">
                        <x-input :label="__('First Name')" name="first_name" autofocus :value="user()->first_name"/>
                            
                        <x-input :label="__('Last Name')" name="last_name" class="mt-6" autofocus :value="user()->last_name"/>

                        <x-input :label="__('Email')" name="email" class="mt-6" :value="user()->email"/>

                        <div class="mt-6" x-data="pasteImage()">
                            <label class="block text-sm leading-5 font-medium text-gray-700">
                                Photo
                            </label>
                            <div class="mt-2 flex items-center">
                                <div
                                    class="flex items-center justify-center w-20 h-20 rounded-full border border-gray-300 @error('photo') border-red-300 @enderror">
                                    <svg class="mx-auto h-12 w-12 text-gray-400"
                                         @if ($errors->has('photo')) stroke="#e02424" @else stroke="currentColor"
                                         @endif fill="none"
                                         viewBox="0 0 48 48"
                                         x-show="!photoUrl">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <img class="rounded-full w-16"
                                        x-show="photoUrl"
                                        :src="photoUrl"
                                        alt=""/>
                                </div>
                                
                                <input type="file" name="photo" id="photo" accept="image/png, image/jpeg"
                                        class="hidden" x-on:change="convertImage(event)">

                                <x-input name="photo_url" label="" class="hidden"></x-input>

                                <span class="ml-5 rounded-md shadow-sm">
                                    <label for="photo"
                                            class="py-2 px-3 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-green-300 focus:shadow-outline-green active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out cursor-pointer">
                                        Change
                                    </label >
                                </span>
                            </div>
                            @error('photo')
                                <div class="inset-y-0 right-0 pr-3 flex items-center pointer-events-none mt-2">
                                    <x-svg.alert class="h-5 w-5 text-red-500 ml-1"></x-svg.alert>
                                     <p class="text-sm text-red-600 ml-1">
                                        {{ $message }}
                                    </p>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <x-slot name="footer">
                        <x-button type="submit" color="green">
                            {{ __('Save') }}
                        </x-button>
                    </x-slot>
                </div>
            </div>
        </x-card>
    </x-form>
</div>
<script>
    function pasteImage() {
        
        var url = <?php echo json_encode(user()->photo_url); ?>;

        return {
            photoUrl: url,
            clear() {
                this.photoUrl = null;
            },
            convertImage(event) {
                let self = this;
                if (event.target.files && event.target.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        self.photoUrl = e.target.result;
                    };

                    reader.readAsDataURL(event.target.files[0]); // convert to base64 string
                }

                const fileInput = document.getElementById('photo');

                var file     = fileInput.files[0];
                let formData = new FormData();
                formData.append('photo', file);
                formData.append('_token', '{{ csrf_token() }}');
                axios.post("{{ route('profile.photo-upload') }}", formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(res => {
                    const {url} = res.data;
                    document.getElementById('photo_url').value = url;
                });
            },
            changeUrl(data) {
                this.photoUrl = data;
            }
        };
    }
    window.addEventListener('paste', function (e) {
        if (!e.clipboardData.files || !e.clipboardData.files.length) {
            return;
        }
        const fileInput = document.getElementById('photo');
        fileInput.files = e.clipboardData.files;
        fileInput.dispatchEvent(new Event('change'));
    }, false);
</script>
