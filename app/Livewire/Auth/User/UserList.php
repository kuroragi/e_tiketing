<?php

namespace App\Livewire\Auth\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\On;

class UserList extends Component
{
    use WithPagination;
    
    public $roles;

    public $roleBadges;

    public $search;
    public $role_search;
    public $department_search;

    #[On('user-saved')]
    public function refresh()
    {
        // Refresh component
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleSearch()
    {
        $this->resetPage();
    }

    public function updatingDepartmentSearch()
    {
        $this->resetPage();
    }

    public function mount(array $_roleBadges)
    {
        $this->roles = Role::all();
        $this->roleBadges = $_roleBadges;
    }
    
    public function render()
    {
        $users = User::query()
            ->when($this->search, function($query){
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->when($this->role_search, function($query){
                $query->whereHas('roles', function($query){
                    $query->where('name', $this->role_search);
                });
            })
            ->when($this->department_search, function($query){
                $query->where('department', $this->department_search);
            })
            ->paginate(10);

        return view('livewire.auth.user.user-list', [
            'users' => $users,
            
        ]);
    }
}
