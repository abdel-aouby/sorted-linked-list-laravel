@php use App\Enums\LinkedListType; @endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ (__('Edit Linked Lists')) }}
                </h2>
            </div>
            <div>
                <x-buttons.default-anchor :href="route('lists.index')"><-|<span class="underline">{{ __('Back To Listing') }}</span></x-buttons.default-anchor>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form name="linked-list-form" id="linked-list-save" method="POST" action="{{ route('lists.update', ['linkedList' => $linkedList]) }}">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-4">
                                        <label for="username" class="block text-sm/6 font-medium text-gray-900">{{ __('Type') }}</label>
                                        <div class="mt-2">
                                            <x-form.enums-select
                                                id="type"
                                                name="type"
                                                class="appearance-none border-gray-500 bg-gray-50 text-gray-500 shadow-none pointer-events-none"
                                                :options="LinkedListType::cases()"
                                                :selected_value="$linkedList->type->value"
                                            ></x-form.enums-select>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-4">
                                        <label for="username" class="block text-sm/6 font-medium text-gray-900">{{ __('Name') }} *</label>
                                        <div class="mt-2">
                                            <x-form.input
                                                name="name"
                                                id="name"
                                                class="mt-1"
                                                maxlength="100"
                                                value="{{ $linkedList->name }}"
                                            ></x-form.input>
                                            <x-form.input-error :messages="$errors->get('name')" />
                                        </div>
                                    </div>

                                    <div class="col-span-full">
                                        <label for="description" class="block text-sm/6 font-medium text-gray-900">{{ __('Short description') }}</label>
                                        <div class="mt-2">
                                            <x-form.textarea
                                                name="description"
                                                id="description"
                                                class="mt-1"
                                                rows="3"
                                                maxlength="255"
                                            >
                                                {{ $linkedList->description }}
                                            </x-form.textarea>

                                            <x-form.input-error :messages="$errors->get('description')" />
                                        </div>
                                        <p class="mt-3 ml-2 text-xs text-gray-600">{{ __('Write a short description about the list') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="mt-1 text-xs text-gray-600">* {{ __('Required fields') }}</p>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <x-buttons.default-anchor :href="route('lists.index')">{{ __('Cancel') }}</x-buttons.default-anchor>
                            <x-buttons.primary-button type="submit">{{ __('Save') }}</x-buttons.primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
