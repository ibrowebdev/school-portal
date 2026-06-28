@extends('layouts.error')
@section('content')
    <div class="text-center p-8 bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 border border-gray-100">
        <h1 class="text-8xl font-black text-blue-600 tracking-tighter mb-4">404</h1>
        <h3 class="text-2xl font-bold text-gray-800 mb-3 flex items-center justify-center gap-2">
            <i class="fas fa-exclamation-triangle text-orange-500"></i> Oops! Page not found!
        </h3>
        <p class="text-gray-600 mb-8">The page you requested was not found.</p>
        <a href="{{route('home')}}" class="inline-block px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-sm hover:shadow">Back to Home</a>
    </div>
@endsection
