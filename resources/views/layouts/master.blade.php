<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>@setting('website_name', 'School Portal')</title>
    <link rel="shortcut icon" href="{{ asset(\App\Models\Setting::get('favicon', 'assets/img/favicon.png')) }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/select2/css/select2.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Top Header -->
        <header class="fixed top-0 w-full bg-white border-b border-gray-200 z-50 flex items-center justify-between px-4 lg:px-6 h-16 shadow-sm">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ asset(\App\Models\Setting::get('logo', 'assets/img/logo-small.png')) }}" alt="Logo" class="w-8 h-8 object-contain">
                    <span class="font-bold text-xl text-gray-800 hidden md:block">@setting('website_name', 'School Portal')</span>
                </a>
                <button id="toggle_btn" class="text-gray-500 hover:text-gray-700 focus:outline-none transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            
            <div class="flex items-center gap-6">
                <!-- Search -->
                <form class="hidden md:block relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" class="bg-gray-100 border border-transparent rounded-full pl-10 pr-4 py-2 text-sm focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all w-64" placeholder="Search here...">
                </form>

                <!-- Notifications -->
                <button class="relative text-gray-500 hover:text-gray-700 focus:outline-none transition">
                    <i class="far fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-3 focus:outline-none py-2">
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-semibold text-gray-800 leading-tight">{{ Session::get('name') }}</p>
                            <p class="text-xs text-gray-500">{{ Session::get('role_name') }}</p>
                        </div>
                        <img class="rounded-full w-9 h-9 object-cover border-2 border-white shadow-sm" src="/images/{{ Session::get('avatar') }}" alt="Avatar">
                        <i class="fas fa-chevron-down text-xs text-gray-400 hidden md:block"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg py-2 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="px-4 py-3 border-b border-gray-50 md:hidden">
                            <p class="text-sm font-semibold text-gray-800">{{ Session::get('name') }}</p>
                            <p class="text-xs text-gray-500">{{ Session::get('role_name') }}</p>
                        </div>
                        <a href="{{ route('user/profile/page') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition"><i class="far fa-user w-5"></i> My Profile</a>
                        <div class="border-t border-gray-50 my-1"></div>
                        <form action="{{ route('logout') }}" method="post" class="x-submit" data-then="reload">

                            <button type="submit">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex flex-1 pt-16">
            <!-- Sidebar -->
            @include('sidebar.sidebar')

            <!-- Main Content -->
            <main class="flex-1 bg-gray-50 min-h-[calc(100vh-4rem)] p-4 md:p-6 lg:p-8 transition-all duration-300">
                @yield('content')
            </main>
        </div>
        
        <x-footer />
    </div>

    <script src="{{ URL::to('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/feather.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/apexchart/chart-data.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/simple-calendar/jquery.simple-calendar.js') }}"></script>
    <script src="{{ URL::to('assets/js/calander.js') }}"></script>
    <script src="{{ URL::to('assets/js/circle-progress.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/script.js') }}"></script>
    <!-- imessage -->
    <script src="{{ asset('assets/js/imessage.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let messages = {
                success: "{{ session('success') }}",
                error: "{{ session('error') }}",
                warning: "{{ session('warning') }}",
                info: "{{ session('info') }}"
            };
    
            Object.keys(messages).forEach(type => {
                if (messages[type]) {
                    new Message('imessage').show(messages[type], type === "error" ? "fail" : type, "top-center");
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2s-hidden-accessible').select2({
                closeOnSelect: false
            });
        });
    </script>
    @yield('script')
    @livewireScripts
</body>
</html>