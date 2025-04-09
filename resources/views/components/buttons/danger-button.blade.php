<button {{ $attributes->merge(['type' => 'button', 'class' => 'cursor-pointer rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 transition duration-200']) }}>
    {{ $slot }}
</button>
