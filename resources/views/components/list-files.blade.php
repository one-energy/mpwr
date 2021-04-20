@props(['sectionFiles', 'sortBy', 'sortDirection' => null])

<div>
    <div x-data="initListFile()">
        <x-table>
            <x-slot name="header">
                <x-table.th-tr>
                    <x-table.th-searchable by="original_name" :sortedBy="$sortBy" :direction="$sortDirection">
                        @lang('Name')
                    </x-table.th-searchable>
                    <x-table.th-searchable by="size" :sortedBy="$sortBy" :direction="$sortDirection">
                        @lang('size')
                    </x-table.th-searchable>
                    <x-table.th-searchable by="created_at" :sortedBy="$sortBy" :direction="$sortDirection">
                        @lang('uploaded_at')
                    </x-table.th-searchable>
                </x-table.th-tr>
            </x-slot>
            <x-slot name="body">
                @foreach ($sectionFiles as $file)
                    <x-table.tr :loop="$loop">
                        <x-table.td class="flex align-middle">
                            <x-icon class="h-5 w-auto mr-5" icon="download-file"/>
                            {{ $file->original_name }}
                        </x-table.td>
                        <x-table.td>{{ $file->abbreviatedSize }}</x-table.td>
                        <x-table.td>{{ $file->created_at->format('d/m/Y') }} at {{$file->created_at->format('h:ia')}}</x-table.td>
                    </x-table.tr>
                @endforeach
            </x-slot>
        </x-table>
    </div>
</div>

@push('scripts')
    <script>
        const DOWNLOAD_URL = '{{route("downloadSectionFile")}}';
        function initListFile() {
            return {
                files: @json($sectionFiles),
                token: document.head.querySelector('meta[name=csrf-token]').content,
                async downloadFile({ path, original_name, type }){
                    var formData = new FormData()
                    formData.append('path', path)
                    const response = await fetch(DOWNLOAD_URL, {
                        method: 'post',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': this.token
                        }
                    });
                    const blob = await response.blob();
                    const newBlob = new Blob([blob]);
                    const blobUrl = window.URL.createObjectURL(newBlob)
                    const anchor = document.createElement('a');
                    anchor.href=blobUrl;
                    anchor.setAttribute('download', `${original_name}.${type}`);
                    anchor.click();
                },
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
            }
        }
    </script>
@endpush
