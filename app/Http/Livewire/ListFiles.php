<?php

namespace App\Http\Livewire;

use App\Models\SectionFile;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ListFiles extends Component
{
    use FullTable;

    public Collection $files;

    public string $order = 'original_name';

    public function sortBy()
    {
        return $this->order;
    }

    public function mount(Collection $files)
    {
        $this->files = $files;
    }

    public function render()
    {
        $this->files = $this->files->sortBy($this->sortBy)
            ->when($this->sortDirection === 'desc', function ($query) {
                return $query->sortByDesc($this->sortBy);
            });

        return view('livewire.list-files');
    }

    public function downloadSectionFile(SectionFile $file)
    {
        if (!Storage::disk('local')->exists($file->path)) {
            alert()->livewire($this)->withTitle('File not found')->send();
        }

        alert()->livewire($this)->withTitle('Download successfully')->send();

        return Storage::disk('local')->download( $file->path );
    }

    public function removeFile(SectionFile $delete)
    {
        if (!Storage::disk('local')->exists($delete->path)) {
            alert()->livewire($this)->withTitle('File not found')->send();

            return;
        }

        DB::transaction(function () use ($delete) {
            SectionFile::destroy($delete->id);

            $this->files = $this->files->filter(function ($file) use ($delete) {
                return $file->id != $delete->id;
            });

            Storage::disk('local')->delete($delete->path);
        });

        alert()->livewire($this)->withTitle('File deleted successfully')->send();
    }
}
