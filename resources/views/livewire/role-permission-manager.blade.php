<div>
    <!-- Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button wire:click="setTab('roles')"
                    class="{{ $activeTab === 'roles' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Roles & Permissions
            </button>
            <button wire:click="setTab('permissions')"
                    class="{{ $activeTab === 'permissions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Manage Permissions
            </button>
            <button wire:click="setTab('users')"
                    class="{{ $activeTab === 'users' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                User Authorization
            </button>
        </nav>
    </div>

    <!-- ROLES TAB -->
    @if ($activeTab === 'roles')
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Roles List -->
        <div class="bg-white shadow rounded-lg border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Roles</h3>
            </div>
            
            <ul class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                @foreach($roles as $role)
                    <li wire:click="selectRole({{ $role->id }})" class="{{ $selectedRole && $selectedRole->id === $role->id ? 'bg-blue-50' : 'hover:bg-gray-50' }} cursor-pointer p-4 flex justify-between items-center transition">
                        <span class="font-medium text-sm text-gray-700">{{ $role->name }}</span>
                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    </li>
                @endforeach
            </ul>
            
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                <form wire:submit.prevent="createRole" class="flex gap-2">
                    <input type="text" wire:model.defer="newRoleName" placeholder="New Role Name" class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition">
                        Add
                    </button>
                </form>
                @error('newRoleName') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Role Permissions Edit -->
        <div class="lg:col-span-2 bg-white shadow rounded-lg border border-gray-200 p-6">
            @if($selectedRole)
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Permissions for '{{ $selectedRole->name }}'</h3>
                
                @if (session()->has('role_message'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm border border-green-200">
                        {{ session('role_message') }}
                    </div>
                @endif

                <form wire:submit.prevent="syncRolePermissions">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-[28rem] overflow-y-auto p-2 border border-gray-100 rounded-lg bg-gray-50/50">
                        @foreach($permissions as $permission)
                            <label class="inline-flex items-center p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition shadow-sm">
                                <input type="checkbox" wire:model.defer="rolePermissions" value="{{ $permission->name }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-3 text-sm text-gray-700">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-5 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            @else
                <div class="h-full flex flex-col items-center justify-center text-gray-400 py-12">
                    <i class="fas fa-shield-alt text-5xl mb-4 text-gray-300"></i>
                    <p class="text-sm">Select a role from the left to manage its permissions.</p>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- PERMISSIONS TAB -->
    @if ($activeTab === 'permissions')
    <div class="mt-6 bg-white shadow rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">All System Permissions</h3>
        
        @if (session()->has('permission_message'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm border border-green-200">
                {{ session('permission_message') }}
            </div>
        @endif

        <form wire:submit.prevent="createPermission" class="flex gap-4 mb-8 max-w-md">
            <input type="text" wire:model.defer="newPermissionName" placeholder="E.g., edit-articles" class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition">
                <i class="fas fa-plus mr-2"></i> Create
            </button>
        </form>
        @error('newPermissionName') <span class="text-xs text-red-500 -mt-6 mb-4 block">{{ $message }}</span> @enderror

        <div class="flex flex-wrap gap-2">
            @foreach($permissions as $permission)
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                    {{ $permission->name }}
                </span>
            @endforeach
        </div>
    </div>
    @endif

    <!-- USERS TAB -->
    @if ($activeTab === 'users')
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Search -->
        <div class="bg-white shadow rounded-lg border border-gray-200">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="searchUser" class="block w-full pl-10 sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Search users...">
                </div>
            </div>
            
            <ul class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                @foreach($users as $user)
                    <li wire:click="selectUser({{ $user->id }})" class="{{ $selectedUser && $selectedUser->id === $user->id ? 'bg-blue-50' : 'hover:bg-gray-50' }} cursor-pointer p-4 flex items-center transition">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3 shrink-0">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            <p class="text-xs text-blue-600 mt-0.5 truncate">{{ $user->type }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- User Roles & Permissions -->
        <div class="lg:col-span-2 bg-white shadow rounded-lg border border-gray-200 p-6">
            @if($selectedUser)
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                    <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl">
                        {{ substr($selectedUser->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $selectedUser->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $selectedUser->email }}</p>
                    </div>
                </div>
                
                @if (session()->has('user_message'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm border border-green-200">
                        {{ session('user_message') }}
                    </div>
                @endif
                @if (session()->has('user_error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm border border-red-200">
                        {{ session('user_error') }}
                    </div>
                @endif

                <form wire:submit.prevent="syncUserRolesPermissions">
                    
                    <h4 class="text-md font-semibold text-gray-800 mb-3">Roles</h4>
                    <div class="flex flex-wrap gap-4 mb-8">
                        @foreach($roles as $role)
                            <label class="inline-flex items-center p-2 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition shadow-sm">
                                <input type="checkbox" wire:model.defer="userRoles" value="{{ $role->name }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 font-medium">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <h4 class="text-md font-semibold text-gray-800 mb-3">Direct Permissions (Optional)</h4>
                    <p class="text-xs text-gray-500 mb-3">Usually, permissions are inherited from roles. You can grant specific extra permissions here.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-60 overflow-y-auto p-2 border border-gray-100 rounded-lg bg-gray-50/50">
                        @foreach($permissions as $permission)
                            <label class="inline-flex items-center p-2 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition shadow-sm">
                                <input type="checkbox" wire:model.defer="userPermissions" value="{{ $permission->name }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 border border-transparent rounded-lg text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition flex items-center gap-2">
                            <i class="fas fa-save"></i> Save User Authorization
                        </button>
                    </div>
                </form>
            @else
                <div class="h-full flex flex-col items-center justify-center text-gray-400 py-12">
                    <i class="fas fa-user-shield text-5xl mb-4 text-gray-300"></i>
                    <p class="text-sm">Search and select a user to manage their roles and permissions.</p>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>
