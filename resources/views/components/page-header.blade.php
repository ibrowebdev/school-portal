@props(['title', 'parent' => 'Dashboard', 'parentRoute' => route('home')])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h3 class="text-2xl font-bold text-gray-800">{{ $title }}</h3>
        <div class="flex items-center text-sm text-gray-500 mt-1">
            <a href="{{ $parentRoute }}" class="hover:text-blue-600 transition">{{ $parent }}</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-medium">{{ $title }}</span>
        </div>
    </div>
    @if(isset($slot) && $slot->isNotEmpty())
        <div class="mt-4 sm:mt-0 flex items-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
