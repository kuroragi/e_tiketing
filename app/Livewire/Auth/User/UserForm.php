<?php

namespace App\Livewire\Auth\User;

use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserForm extends Component
{
    public $user_id;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role = '';
    public $department_id = '';
    public $status = 'aktif';

    public $roles;
    public $departments;

    public $button_word = 'Simpan Pengguna';
    public $button_color = 'btn-primary';
    public $button_icon = 'bi-person-plus';

    public function mount()
    {
        $this->roles = Role::all();
        $this->departments = Department::all();
    }

    #[On('create-user')]
    public function create()
    {
        $this->reset(['user_id', 'name', 'email', 'password', 'password_confirmation', 'role', 'department_id']);
        $this->status = 'aktif';
        
        $this->button_word = 'Simpan Pengguna';
        $this->button_color = 'btn-primary';
        $this->button_icon = 'bi-person-plus';
        
        $this->dispatch('show-user-form');
    }

    #[On('edit-user')]
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->department_id = $user->department_id;
        $this->status = $user->status;
        $this->password = '';
        $this->password_confirmation = '';
        
        $this->button_word = 'Simpan Perubahan';
        $this->button_color = 'btn-warning text-dark';
        $this->button_icon = 'bi-save';
        
        $this->dispatch('show-user-form');
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user_id)],
            'role' => 'required|string|exists:roles,name',
            'status' => 'required|in:aktif,nonaktif',
            'department_id' => 'nullable|exists:departments,id'
        ];

        if (!$this->user_id) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else if ($this->password) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        $validated = $this->validate($rules);

        if (in_array($this->role, ['petugas', 'pimpinan'])) {
            $validated['department_id'] = null;
        }

        if ($this->password) {
            $validated['password'] = Hash::make($this->password);
        } else {
            unset($validated['password']);
        }

        if ($this->user_id) {
            $user = User::findOrFail($this->user_id);
            $user->update($validated);
            if ($this->role) {
                $user->syncRoles([$this->role]);
            }
            session()->flash('success', 'Data pengguna berhasil diupdate.');
        } else {
            $user = User::create($validated);
            if ($this->role) {
                $user->assignRole($this->role);
            }
            session()->flash('success', 'Pengguna baru berhasil ditambahkan.');
        }

        $this->dispatch('hide-user-form');
        $this->dispatch('user-saved');
    }

    public function render()
    {
        return view('livewire.auth.user.user-form');
    }
}
