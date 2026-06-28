
@extends('layouts.app')
@section('content')
<div class="">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome to Dashboard</h1>
        <p class="text-gray-500">Need an account? <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">Sign Up</a></p>
    </div>
    
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Sign in</h2>

    {{-- Error container for AJAX validation errors --}}
    <div id="form-errors-container" class="hidden bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200" style="display: none;">
        <ul id="form-errors-list" class="list-disc pl-5 mb-0 text-sm"></ul>
    </div>

    <form action="{{ route('login') }}" method="POST" class="x-submit space-y-5" data-then="redirect:{{ route('home') }}">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
            <div class="relative">
                <input type="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" name="email" required>
                <span class="absolute right-4 top-3 text-gray-400"><i class="fas fa-envelope"></i></span>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
            <div class="relative">
                <input type="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pass-input" name="password" required>
                <span class="absolute right-4 top-3 text-gray-400 cursor-pointer toggle-password"><i class="fas fa-eye"></i></span>
            </div>
        </div>
        <div class="flex items-center justify-between mt-4">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:underline">Forgot Password?</a>
        </div>
        <div class="pt-2">
            <button class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors" type="submit">Login</button>
        </div>
    </form>
    
    <div class="relative my-8">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">or</span>
        </div>
    </div>
    
    <div class="flex justify-center gap-4">
        <a href="#" class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition-colors"><i class="fab fa-google-plus-g"></i></a>
        <a href="#" class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition-colors"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="w-10 h-10 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center hover:bg-sky-200 transition-colors"><i class="fab fa-twitter"></i></a>
        <a href="#" class="w-10 h-10 rounded-full bg-blue-50 text-blue-800 flex items-center justify-center hover:bg-blue-100 transition-colors"><i class="fab fa-linkedin-in"></i></a>
    </div>
</div>

@endsection
