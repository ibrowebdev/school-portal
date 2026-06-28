@props(['name', 'label', 'required' => false, 'options' => [], 'selected' => null])

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }} 
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="relative">
        <select 
            name="{{ $name }}" 
            id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white ' . ($errors->has($name) ? 'border-red-500' : 'border-gray-300')]) }}
        >
            <option selected disabled value="">Please Select {{ $label }}</option>
            @foreach($options as $value => $text)
                <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
                    {{ $text }}
                </option>
            @endforeach
            {{ $slot }}
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
            <i class="fas fa-chevron-down text-xs"></i>
        </div>
    </div>
    @error($name)
        <span class="text-sm text-red-500 mt-1 block">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
