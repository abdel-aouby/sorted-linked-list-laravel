@props(['linked_list'])

<span class="inline-flex items-center rounded-md rounded-bl-none bg-blue-50 px-2 py-1 text-xs font-medium {{ $linkedList->type->textColor() }} ring-1 ring-inset ring-blue-700/10">
    {{ $linkedList->type->label() }}
</span>
