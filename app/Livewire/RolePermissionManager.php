<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionManager extends Component
{
    public $activeTab = 'roles';

    // Roles state
    public $roles = [];
    public $permissions = [];
    public $selectedRole = null;
    public $rolePermissions = [];
    public $newRoleName = '';

    // Permissions state
    public $newPermissionName = '';

    // Users state
    public $searchUser = '';
    public $users = [];
    public $selectedUser = null;
    public $userRoles = [];
    public $userPermissions = [];

    public function mount()
    {
        $this->loadRolesAndPermissions();
    }

    public function loadRolesAndPermissions()
    {
        $this->roles = Role::all();
        $this->permissions = Permission::all();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        if ($tab === 'users') {
            $this->searchUsers();
        }
    }

    // --- Roles Methods ---

    public function createRole()
    {
        $this->validate([
            'newRoleName' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create(['name' => $this->newRoleName]);
        $this->newRoleName = '';
        $this->loadRolesAndPermissions();
        session()->flash('role_message', 'Role created successfully.');
    }

    public function selectRole($roleId)
    {
        $this->selectedRole = Role::findById($roleId);
        $this->rolePermissions = $this->selectedRole->permissions->pluck('name')->toArray();
    }

    public function syncRolePermissions()
    {
        if ($this->selectedRole) {
            $this->selectedRole->syncPermissions($this->rolePermissions);
            session()->flash('role_message', 'Permissions synchronized successfully.');
            $this->loadRolesAndPermissions();
        }
    }

    // --- Permissions Methods ---

    public function createPermission()
    {
        $this->validate([
            'newPermissionName' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create(['name' => $this->newPermissionName]);
        $this->newPermissionName = '';
        $this->loadRolesAndPermissions();
        session()->flash('permission_message', 'Permission created successfully.');
    }

    // --- Users Methods ---

    public function searchUsers()
    {
        if (!empty($this->searchUser)) {
            $this->users = User::where('first_name', 'like', '%' . $this->searchUser . '%')
                ->orWhere('last_name', 'like', '%' . $this->searchUser . '%')
                ->orWhere('email', 'like', '%' . $this->searchUser . '%')
                ->take(10)
                ->get();
        } else {
            $this->users = User::take(10)->get();
        }
    }

    public function updatedSearchUser()
    {
        $this->searchUsers();
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::find($userId);
        if ($this->selectedUser) {
            $this->userRoles = $this->selectedUser->roles->pluck('name')->toArray();
            // Get direct permissions (not via roles)
            $this->userPermissions = $this->selectedUser->getDirectPermissions()->pluck('name')->toArray();
        }
    }

    public function syncUserRolesPermissions()
    {
        if ($this->selectedUser) {
            // Check if Super Admin is being removed from self
            if ($this->selectedUser->id === auth()->id() && !in_array(User::SUPER_ADMIN, $this->userRoles)) {
                // Ensure auth user cannot remove super-admin from themselves
                session()->flash('user_error', 'You cannot remove Super Admin from yourself.');
                return;
            }

            // Sync roles and update user 'type'
            $this->selectedUser->syncRoles($this->userRoles);
            
            // Auto update type if possible
            if (count($this->userRoles) > 0) {
                $this->selectedUser->update(['type' => $this->userRoles[0]]);
            }

            // Sync direct permissions
            $this->selectedUser->syncPermissions($this->userPermissions);

            session()->flash('user_message', 'User roles and permissions synchronized.');
            
            // Re-fetch roles in case we need updated view
            $this->selectUser($this->selectedUser->id);
        }
    }

    public function render()
    {
        return view('livewire.role-permission-manager');
    }
}
