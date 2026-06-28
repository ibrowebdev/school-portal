@props(['name', 'label', 'type' => 'text', 'placeholder' => '', 'value' => '', 'required' => false])

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }} 
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="relative">
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ' . ($errors->has($name) ? 'border-red-500' : 'border-gray-300')]) }}
        >
        @if(isset($icon))
            <span class="absolute right-4 top-3 text-gray-400">
                {{ $icon }}
            </span>
        @endif
    </div>
    @error($name)
        <span class="text-sm text-red-500 mt-1 block">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
