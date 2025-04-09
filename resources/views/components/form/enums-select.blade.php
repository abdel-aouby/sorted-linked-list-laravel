@props(['options' => [], 'selected_value' => ''])

<select
    {{ $attributes->merge(['class' => 'col-start-1 row-start-1 w-full rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 border border-gray-400 sm:text-sm/6']) }}
>
    @foreach($options as $type)
        <option
            value="{{ $type->value }}"
            @selected(($selectedValue ?? '') == $type->value)
        >{{ $type->label() }}</option>
    @endforeach
</select>
