<?php

namespace App\Livewire\Skpd;

use App\Models\Department;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

class SkpdForm extends Component
{
    public $department_id;
    public $name;
    public $code;
    public $head;
    public $contact;
    public $address;
    public $status = 'aktif';
    public $pic_id;
    public $petugasList;
    public $button_word = 'Simpan';
    public $button_color = 'btn-primary';
    public $button_icon = 'bi-save';

    public function mount()
    {
        $this->petugasList = User::all()->filter(fn($user) => $user->isPetugas());
    }

    #[On('create-skpd')]
    public function create()
    {
        $this->reset(['department_id', 'name', 'code', 'head', 'contact', 'address', 'pic_id']);
        $this->status = 'aktif';
        
        $this->button_word = 'Simpan';
        $this->button_color = 'btn-primary';
        $this->button_icon = 'bi-save';
        
        $this->dispatch('show-skpd-form');
    }

    #[On('edit-skpd')]
    public function edit($id)
    {
        $skpd = Department::findOrFail($id);
        
        $this->department_id = $skpd->id;
        $this->name = $skpd->name;
        $this->code = $skpd->code;
        $this->head = $skpd->head;
        $this->contact = $skpd->contact;
        $this->address = $skpd->address;
        $this->status = $skpd->status;
        $this->pic_id = $skpd->pic_id;
        
        $this->button_word = 'Update';
        $this->button_color = 'btn-warning';
        $this->button_icon = 'bi-pencil';
        
        $this->dispatch('show-skpd-form');
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'head' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
            'pic_id' => 'nullable|exists:users,id',
        ]);

        if ($this->department_id) {
            Department::findOrFail($this->department_id)->update($validated);
            session()->flash('success', 'Data SKPD berhasil diupdate.');
        } else {
            Department::create($validated);
            session()->flash('success', 'Data SKPD berhasil ditambahkan.');
        }

        $this->dispatch('hide-skpd-form');
        $this->dispatch('skpd-saved');
    }

    public function render()
    {
        return view('livewire.skpd.skpd-form');
    }
}
