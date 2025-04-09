<div class="flex p-4 mb-4 text-sm text-red-700 rounded-lg bg-red-100" role="alert">
    <div class="w-4 h-4">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
            data-slot="icon"
            class="om baz"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <div class="ml-2"> {{ $slot }}</div>
</div>
