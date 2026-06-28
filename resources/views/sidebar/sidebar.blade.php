<div class="sidebar w-64 bg-white border-r border-gray-200 hidden md:block shrink-0 h-[calc(100vh-4rem)] sticky top-16 overflow-y-auto transition-all duration-300" id="sidebar">
    <div class="sidebar-inner slimscroll p-4">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="space-y-1">
                <li class="menu-title text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 mt-2 px-3">
                    <span>Main Menu</span>
                </li>

                <!-- Dashboard -->
                <li class="submenu group {{ request()->routeIs('home', 'teacher/dashboard', 'student/dashboard') ? 'active' : '' }}">
                    <a href="#" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('home', 'teacher/dashboard', 'student/dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <div class="flex items-center gap-3"><i class="feather-grid text-lg w-5 text-center"></i> <span>Dashboard</span></div>
                        <span class="menu-arrow fas fa-chevron-right text-xs transition-transform duration-200 group-[.active]:rotate-90"></span>
                    </a>
                    <ul class="mt-1 space-y-1 pl-9 hidden group-[.active]:block">
                        <li><a href="{{ route('home') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('home') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Admin Dashboard</a></li>
                    </ul>
                </li>

                <!-- Students -->
                <li class="submenu group {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <a href="#" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('students.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <div class="flex items-center gap-3"><i class="fas fa-graduation-cap text-lg w-5 text-center"></i> <span>Students</span></div>
                        <span class="menu-arrow fas fa-chevron-right text-xs transition-transform duration-200 group-[.active]:rotate-90"></span>
                    </a>
                    <ul class="mt-1 space-y-1 pl-9 hidden group-[.active]:block">
                        <li><a href="{{ route('students.index') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('students.index') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Student List</a></li>
                        <li><a href="{{ route('students.create') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('students.create') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Student Add</a></li>
                    </ul>
                </li>

                <!-- Teachers -->
                <li class="submenu group {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                    <a href="#" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('teachers.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <div class="flex items-center gap-3"><i class="fas fa-chalkboard-teacher text-lg w-5 text-center"></i> <span>Teachers</span></div>
                        <span class="menu-arrow fas fa-chevron-right text-xs transition-transform duration-200 group-[.active]:rotate-90"></span>
                    </a>
                    <ul class="mt-1 space-y-1 pl-9 hidden group-[.active]:block">
                        <li><a href="{{ route('teachers.index') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('teachers.index') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Teacher List</a></li>
                        <li><a href="{{ route('teachers.create') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('teachers.create') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Teacher Add</a></li>
                    </ul>
                </li>

                <!-- Departments -->
                <li class="submenu group {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                    <a href="#" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('departments.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <div class="flex items-center gap-3"><i class="fas fa-building text-lg w-5 text-center"></i> <span>Departments</span></div>
                        <span class="menu-arrow fas fa-chevron-right text-xs transition-transform duration-200 group-[.active]:rotate-90"></span>
                    </a>
                    <ul class="mt-1 space-y-1 pl-9 hidden group-[.active]:block">
                        <li><a href="{{ route('departments.index') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('departments.index') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Department List</a></li>
                        <li><a href="{{ route('departments.create') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('departments.create') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Department Add</a></li>
                    </ul>
                </li>

                <!-- Subjects -->
                <li class="submenu group {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                    <a href="#" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('subjects.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <div class="flex items-center gap-3"><i class="fas fa-book-reader text-lg w-5 text-center"></i> <span>Subjects</span></div>
                        <span class="menu-arrow fas fa-chevron-right text-xs transition-transform duration-200 group-[.active]:rotate-90"></span>
                    </a>
                    <ul class="mt-1 space-y-1 pl-9 hidden group-[.active]:block">
                        <li><a href="{{ route('subjects.index') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('subjects.index') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Subject List</a></li>
                        <li><a href="{{ route('subjects.create') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('subjects.create') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Subject Add</a></li>
                    </ul>
                </li>

                <!-- Invoices -->
                <li class="submenu group {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <a href="#" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('invoices.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <div class="flex items-center gap-3"><i class="fas fa-clipboard text-lg w-5 text-center"></i> <span>Invoices</span></div>
                        <span class="menu-arrow fas fa-chevron-right text-xs transition-transform duration-200 group-[.active]:rotate-90"></span>
                    </a>
                    <ul class="mt-1 space-y-1 pl-9 hidden group-[.active]:block">
                        <li><a href="{{ route('invoices.index') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('invoices.index') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Invoices List</a></li>
                        <li><a href="{{ route('invoices.create') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('invoices.create') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Invoices Add</a></li>
                    </ul>
                </li>

                <!-- Accounts/Fees -->
                <li class="submenu group {{ request()->routeIs('fees.*') ? 'active' : '' }}">
                    <a href="#" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('fees.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <div class="flex items-center gap-3"><i class="fas fa-file-invoice-dollar text-lg w-5 text-center"></i> <span>Accounts</span></div>
                        <span class="menu-arrow fas fa-chevron-right text-xs transition-transform duration-200 group-[.active]:rotate-90"></span>
                    </a>
                    <ul class="mt-1 space-y-1 pl-9 hidden group-[.active]:block">
                        <li><a href="{{ route('fees.index') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('fees.index') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Fees Collection</a></li>
                        <li><a href="{{ route('fees.create') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('fees.create') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">Add Fees</a></li>
                    </ul>
                </li>

                <!-- User Management -->
                <li class="submenu group {{ request()->routeIs('users.*', 'get-users-data') ? 'active' : '' }}">
                    <a href="#" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('users.*', 'get-users-data') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <div class="flex items-center gap-3"><i class="fas fa-users text-lg w-5 text-center"></i> <span>Users</span></div>
                        <span class="menu-arrow fas fa-chevron-right text-xs transition-transform duration-200 group-[.active]:rotate-90"></span>
                    </a>
                    <ul class="mt-1 space-y-1 pl-9 hidden group-[.active]:block">
                        <li><a href="{{ route('users.index') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('users.index') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">User List</a></li>
                    </ul>
                </li>

                <!-- Settings -->
                <li class="submenu group {{ request()->routeIs('setting/page') ? 'active' : '' }}">
                    <a href="#" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors {{ request()->routeIs('setting/page') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <div class="flex items-center gap-3"><i class="fas fa-cog text-lg w-5 text-center"></i> <span>Settings</span></div>
                        <span class="menu-arrow fas fa-chevron-right text-xs transition-transform duration-200 group-[.active]:rotate-90"></span>
                    </a>
                    <ul class="mt-1 space-y-1 pl-9 hidden group-[.active]:block">
                        <li><a href="{{ route('setting/page') }}" class="block px-3 py-2 text-sm rounded-md hover:text-blue-600 {{ request()->routeIs('setting/page') ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">General Settings</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
