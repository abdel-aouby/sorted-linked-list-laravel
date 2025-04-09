<textarea
    {{ $attributes->merge(['class' => 'block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 border border-gray-400 placeholder:text-gray-400 sm:text-sm/6']) }}
>{{ $slot }}</textarea>
