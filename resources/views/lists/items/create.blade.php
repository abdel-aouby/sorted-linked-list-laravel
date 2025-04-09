@php use App\Enums\LinkedListType; @endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Add Item to List:') }} {{ $linkedList->name }}
                </h2>
            </div>
            <div>
                <x-buttons.default-anchor :href="route('lists.items.index', $linkedList)"><-|<span
                        class="underline">{{ __('Back To Items') }}</span></x-buttons.default-anchor>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('lists.items.store', $linkedList) }}">
                    @csrf
                    <input type="hidden" name="linked_list_id" value="{{ $linkedList->id }}">

                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-4 mb-4">
                                    <div class="rounded-md bg-blue-50 p-4">
                                        <div class="flex">
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium {{ $linkedList->type->textColor() }}">
                                                    {{ __('List Type') }}: {{ $linkedList->type->label() }}
                                                </h3>
                                                <div class="mt-2 text-sm {{ $linkedList->type->textColor() }}">
                                                    <p>
                                                        @if($linkedList->type->value === LinkedListType::INTEGERS_LINKED_LIST->value)
                                                            {{ __('This list accepts only integer values (e.g., -1, 0, 42)') }}
                                                        @else
                                                            {{ __('This list accepts only text values') }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sm:col-span-4">
                                    <label for="value" class="block text-sm font-medium leading-6 text-gray-900">
                                        {{ __('Value') }}
                                    </label>
                                    <div class="mt-2">
                                        <x-form.input
                                            name="value"
                                            id="value"
                                            type="text"
                                            required
                                            autofocus
                                            maxlength="255"
                                            value="{{ old('value') }}"
                                        />
                                        <x-form.input-error :messages="$errors->get('value')"/>
                                    </div>
                                    <p class="mt-3 ml-2 text-xs text-gray-600">
                                        {{ __('Edit the value for your list item.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <x-buttons.default-anchor
                            :href="route('lists.items.index', $linkedList)">{{ __('Cancel') }}</x-buttons.default-anchor>
                        <x-buttons.primary-button type="submit">{{ __('Add Item') }}</x-buttons.primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
