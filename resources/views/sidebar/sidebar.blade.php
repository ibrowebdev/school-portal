<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main Menu</span>
                </li>

                <!-- Dashboard -->
                <li class="submenu {{ request()->routeIs('home', 'teacher/dashboard', 'student/dashboard') ? 'active' : '' }}">
                    <a href="#"><i class="feather-grid"></i> <span> Dashboard</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Admin Dashboard</a></li>
                    </ul>
                </li>

                <!-- Students -->
                <li class="submenu {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-graduation-cap"></i> <span> Students</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.index') ? 'active' : '' }}">Student List</a></li>
                        <li><a href="{{ route('students.create') }}" class="{{ request()->routeIs('students.create') ? 'active' : '' }}">Student Add</a></li>
                    </ul>
                </li>

                <!-- Teachers -->
                <li class="submenu {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-chalkboard-teacher"></i> <span> Teachers</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('teachers.index') }}" class="{{ request()->routeIs('teachers.index') ? 'active' : '' }}">Teacher List</a></li>
                        <li><a href="{{ route('teachers.create') }}" class="{{ request()->routeIs('teachers.create') ? 'active' : '' }}">Teacher Add</a></li>
                    </ul>
                </li>

                <!-- Departments -->
                <li class="submenu {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-building"></i> <span> Departments</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('departments.index') }}" class="{{ request()->routeIs('departments.index') ? 'active' : '' }}">Department List</a></li>
                        <li><a href="{{ route('departments.create') }}" class="{{ request()->routeIs('departments.create') ? 'active' : '' }}">Department Add</a></li>
                    </ul>
                </li>

                <!-- Subjects -->
                <li class="submenu {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-book-reader"></i> <span> Subjects</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('subjects.index') }}" class="{{ request()->routeIs('subjects.index') ? 'active' : '' }}">Subject List</a></li>
                        <li><a href="{{ route('subjects.create') }}" class="{{ request()->routeIs('subjects.create') ? 'active' : '' }}">Subject Add</a></li>
                    </ul>
                </li>

                <!-- Invoices -->
                <li class="submenu {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-clipboard"></i> <span> Invoices</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('invoices.index') }}" class="{{ request()->routeIs('invoices.index') ? 'active' : '' }}">Invoices List</a></li>
                        <li><a href="{{ route('invoices.create') }}" class="{{ request()->routeIs('invoices.create') ? 'active' : '' }}">Invoices Add</a></li>
                    </ul>
                </li>

                <!-- Accounts/Fees -->
                <li class="submenu {{ request()->routeIs('fees.*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-file-invoice-dollar"></i> <span> Accounts</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('fees.index') }}" class="{{ request()->routeIs('fees.index') ? 'active' : '' }}">Fees Collection</a></li>
                        <li><a href="{{ route('fees.create') }}" class="{{ request()->routeIs('fees.create') ? 'active' : '' }}">Add Fees</a></li>
                    </ul>
                </li>

                <!-- User Management -->
                <li class="submenu {{ request()->routeIs('users.*', 'get-users-data') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-users"></i> <span> Users</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.index') ? 'active' : '' }}">User List</a></li>
                    </ul>
                </li>

                <!-- Settings -->
                <li class="submenu {{ request()->routeIs('setting/page') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-cog"></i> <span> Settings</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('setting/page') }}" class="{{ request()->routeIs('setting/page') ? 'active' : '' }}">General Settings</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
