@props(['title' => null, 'actions' => null, 'noPadding' => false])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden']) }}>
    @if($title || $actions)
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            @if($title)
                <h5 class="text-lg font-bold text-gray-800">{{ $title }}</h5>
            @endif
            @if($actions)
                <div class="flex items-center gap-3">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    <div class="{{ $noPadding ? '' : 'p-6' }}">
        {{ $slot }}
    </div>
</div>
