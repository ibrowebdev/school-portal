
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Login</title>
    <link rel="shortcut icon" href="{{ URL::to('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/fontawesome/css/all.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl flex overflow-hidden">
            <!-- Left Side Image -->
            <div class="hidden md:flex md:w-1/2 bg-blue-50 items-center justify-center p-8">
                <img class="max-w-xs object-contain" src="{{ URL::to('assets/img/login.png') }}" alt="Logo">
            </div>
            <!-- Right Side Form Content -->
            <div class="w-full md:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ URL::to('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/feather.min.js') }}"></script>
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
    @yield('script')
</body>

</html>
