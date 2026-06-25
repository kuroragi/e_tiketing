<?php

namespace App\Livewire\Skpd;

use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class SkpdList extends Component
{
    use WithPagination;
    
    public $departments;

    public $departement_search = '';
    public $status_search = 'aktif';

    public function updatingDepartmentSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        session()->flash('success', 'SKPD berhasil dihapus.');
    }

    public function render()
    {
        $this->departments = Department::query()
            ->when($this->departement_search, function ($query) {
                $query->where('name', 'like', "%{$this->departement_search}%");
            })
            ->when($this->status_search, function ($query) {
                $query->where('status', $this->status_search);
            })
            ->latest()
            ->get();
        return view('livewire.skpd.skpd-list');
    }
}
