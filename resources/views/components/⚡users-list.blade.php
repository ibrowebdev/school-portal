<?php

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterType = '';

    public function updating($property)
    {
        if (in_array($property, ['search', 'filterType'])) {
            $this->resetPage();
        }
    }

    public function with(): array
    {
        $query = User::with('roles')->latest('id');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('unique_id', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        return [
            'users' => $query->paginate(10),
        ];
    }
};
?>

<div class="w-full">
    <!-- Search Filter -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="w-full md:w-1/2 lg:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="p-2 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Search by ID, Name, Email or Phone...">
            </div>
            <div class="md:w-1/4 lg:w-1/5">
                <select wire:model.live="filterType" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-gray-700 p-2">
                    <option value="">All Types</option>
                    <option value="{{ User::SUPER_ADMIN }}">Super Admin</option>
                    <option value="{{ User::ADMIN }}">Admin</option>
                    <option value="{{ User::TEACHER }}">Teacher</option>
                    <option value="{{ User::STUDENT }}">Student</option>
                    <option value="{{ User::PARENT }}">Parent</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative w-full">
        <!-- Loading Overlay -->
        <div wire:loading class="absolute inset-0 bg-white/50 z-10 backdrop-blur-[1px]">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <i class="fas fa-spinner fa-spin text-blue-600 text-2xl"></i>
            </div>
        </div>

        <div class="p-4 border-b border-gray-100 flex justify-between items-center">
            <h5 class="font-bold text-gray-800">Users List</h5>
        </div>
        
        <!-- Table container with overflow-x-auto to scroll horizontally on small screens -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full text-sm text-left whitespace-nowrap min-w-full">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">User ID</th>
                        <th class="px-4 py-3">Profile</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Phone Number</th>
                        <th class="px-4 py-3">Date Join</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">{{ $user->unique_id }}</td>
                            <td class="px-4 py-3">
                                <img class="w-8 h-8 rounded-full object-cover" src="{{ url('images/' . ($user->avatar ?? 'photo_defaults.jpg')) }}" alt="User Image">
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">{{ $user->phone_number ?? '--' }}</td>
                            <td class="px-4 py-3">{{ $user->join_date ?? '--' }}</td>
                            <td class="px-4 py-3">{{ ucwords($user->type) }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $color = $user->status == \App\Enums\StatusEnum::ACTIVE->value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                @endphp
                                 <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $color }} uppercase">{{ $user->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-right relative">
                                <span class="hidden unique_id" data-unique_id="{{ $user->id }}"></span>
                                <span class="hidden avatar" data-avatar="{{ $user->avatar }}"></span>
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded transition" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="post" class="x-submit" data-then="reload" data-confirm="Are you sure you want to delete user?" data-confirm-text="Delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded transition delete" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                No users found matching your search criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>