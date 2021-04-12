<?php

namespace App\Http\Livewire;

use App\Models\SectionFile;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Collection;
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

    public function render()
    {
        if($this->files->count()){

            $this->files = $this->files->sortBy($this->sortBy)
            ->when($this->sortDirection == 'desc', function ($query) {
                return $query->sortByDesc($this->sortBy);
            });
        }
        return view('livewire.list-files');
    }


    public function downloadSectionFile(SectionFile $file)
    {
        if (Storage::disk('local')->exists($file->path)) {
            alert()->livewire($this)->withTitle('Download Started')->send();
            return Storage::disk('local')->download( $file->path );
        } else {
            alert()->livewire($this)->withTitle('No one file to download')->send();
        }
    }

    public function removeFile(SectionFile $delete)
    {
        if (Storage::disk('local')->exists($delete->path)) {
            SectionFile::destroy($delete->id);
            Storage::disk('local')->delete($delete->path);
            $this->files = $this->files->filter(function ($file) use ($delete) {
                return $file->id != $delete->id;
            });
            alert()->livewire($this)->withTitle('The file was delted with success')->send();
        } else {
            alert()->livewire($this)->withTitle('No one file to delete')->send();
        }

    }
}
