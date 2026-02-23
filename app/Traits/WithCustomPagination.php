<?php

namespace App\Traits;

use Livewire\WithPagination;

trait WithCustomPagination
{
    use WithPagination;
    public $perPage = 1;

    public function mountWithCustomPagination()
    {
        $this->perPage = session()->get('perPage', $this->perPage);
    }

    public function updatedPerPage($value)
    {
        session(['perPage' => $value]);
        $this->resetPage(); // VERY IMPORTANT
    }
}