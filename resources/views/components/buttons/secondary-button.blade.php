<button {{ $attributes->merge(['type' => 'button', 'class' => 'cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50']) }}>
    {{ $slot }}
</button>
