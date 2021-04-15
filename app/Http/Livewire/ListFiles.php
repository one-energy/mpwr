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

    public bool $showDeleteButton;

    public ?SectionFile $selectedFile;

    public function sortBy()
    {
        return $this->order;
    }

    public function render()
    {
        $this->files = $this->files->sortBy($this->sortBy)
            ->when($this->sortDirection === 'desc', function ($query) {
                return $query->sortByDesc($this->sortBy);
            });

        return view('livewire.list-files');
    }

    public function onDestroy(SectionFile $file)
    {
        $this->selectedFile = $file;

        $this->dispatchBrowserEvent('confirm', ['sectionFile' => $file]);
    }

    public function downloadSectionFile(SectionFile $file)
    {
        if (!Storage::disk('local')->exists($file->path)) {
            alert()->livewire($this)->withTitle('File not found')->send();
        }

        alert()->livewire($this)->withTitle('Download successfully')->send();

        return Storage::disk('local')->download( $file->path );
    }

    public function removeFile()
    {
        if (!Storage::disk('local')->exists($this->selectedFile->path)) {
            alert()->livewire($this)->withTitle('File not found')->send();

            return;
        }

        DB::transaction(function () {
            SectionFile::destroy($this->selectedFile->id);

            $this->files = $this->files->filter(function ($file) {
                return $file->id !== $this->selectedFile->id;
            });

            Storage::disk('local')->delete($this->selectedFile->path);

            $this->selectedFile = new SectionFile();
        });

        $this->dispatchBrowserEvent('close-modal');
        alert()->livewire($this)->withTitle('File deleted successfully')->send();
    }
}
