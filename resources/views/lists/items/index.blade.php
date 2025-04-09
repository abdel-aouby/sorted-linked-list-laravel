<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ (__('Linked List Items')) }}
                </h2>
            </div>
            <div>
                <x-buttons.default-anchor :href="route('lists.index')"><-|<span class="underline">{{ __('Back To Listing') }}</span></x-buttons.default-anchor>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-badges.list-type-badge :linked_list="$linkedList"/>
            <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg sm:rounded-tl-none">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $linkedList->name }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">{{ $linkedList->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-5">
                <div class="flex items-center gap-2">
                    <x-buttons.primary-anchor-button href="{{ route('lists.items.create', $linkedList) }}">
                        {{ __('Add item') }}
                    </x-buttons.primary-anchor-button>
                </div>
            </div>

            @if(session('success_message'))
                <x-alerts.success>{{ session('success_message') }}</x-alerts.success>
            @endif

            @if(session('error_message'))
                <x-alerts.error>{{ session('error_message') }}</x-alerts.error>
            @endif

            <!-- Search Form -->
            <form method="GET" action="{{ route('lists.items.index', $linkedList) }}" class="mb-6">
                <div class="flex items-center gap-2">
                    <x-form.input
                        name="search"
                        id="search"
                        class="mt-1"
                        maxlength="100"
                        value="{{ request('search') }}"
                        placeholder="{{ __('Search by value...') }}"
                    ></x-form.input>
                    <x-buttons.secondary-button type="submit">{{ __('Search') }}</x-buttons.secondary-button>
                    <x-buttons.default-anchor :href="route('lists.items.index', $linkedList)">{{ __('Reset') }}</x-buttons.default-anchor>
                </div>
                <x-form.input-error :messages="$errors->get('search')" />
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <ul role="list" class="divide-y divide-gray-200">
                    @forelse($items as $item)
                        <li class="flex items-center justify-between gap-x-6 p-5">
                            <div class="flex items-center gap-x-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold leading-6 text-gray-900">{{ $item->value }}</p>
                                    <p class="mt-1 truncate text-xs text-gray-500">
                                        {{ __('Added: ') }} {{ $item->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-x-4">
                                <x-buttons.secondary-anchor-button
                                    href="{{ route('lists.items.edit', ['linkedList' => $linkedList, 'item' => $item]) }}"
                                >
                                    {{ __('Edit') }}
                                </x-buttons.secondary-anchor-button>
                                <form action="{{ route('lists.items.destroy', ['linkedList' => $linkedList, 'item' => $item]) }}"
                                      method="POST"
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this item?') }}')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <x-buttons.danger-button type="submit">{{ __('Delete') }}</x-buttons.danger-button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="p-4 text-center text-gray-500">
                            {{ __('No items yet. Add your first item!') }}
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="mt-6">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
