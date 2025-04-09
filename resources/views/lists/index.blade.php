@php use App\Enums\LinkedListType; @endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ (__('Linked Lists')) }}
                </h2>
            </div>
            <div>
                <x-buttons.primary-anchor-button
                    href="{{ route('lists.create') }}">{{ __('Create List') }}</x-buttons.primary-anchor-button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

            @if(session('success_message'))
                <x-alerts.success>{{ session('success_message') }}</x-alerts.success>
            @endif

            @if(session('error_message'))
                <x-alerts.error>{{ session('error_message') }}</x-alerts.error>
            @endif

            <div class="space-y-4 mb-6">
                <div class="flex justify-end">
                    <div class="w-full lg:w-1/2">
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('lists.index') }}" class="flex items-center gap-2">
                            <select name="filter"
                                    class="rounded-md w-full bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 border border-gray-400 sm:text-sm/6"
                                    onchange="handleTypeChange(this)">
                                <option value="">{{ __('All Types') }}</option>
                                @foreach(LinkedListType::cases() as $type)
                                    <option
                                        value="{{ $type->value }}" {{ request('filter') == $type->value ? 'selected' : '' }}>
                                        {{ $type->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                        </form>
                        <x-form.input-error :messages="$errors->get('filter')" />
                    </div>
                </div>

                <div class="flex justify-end">
                    <div class="w-full lg:w-1/2">
                        <!-- Search Form -->
                        <form method="GET" action="{{ route('lists.index') }}" class="flex items-center gap-2">
                            <x-form.input
                                name="search"
                                id="search"
                                class="mt-1"
                                minlength="3"
                                maxlength="100"
                                value="{{ request('search') }}"
                                placeholder="{{ __('Search by name...') }}"
                            ></x-form.input>
                            <x-buttons.secondary-button type="submit">{{ __('Search') }}</x-buttons.secondary-button>
                            <x-buttons.default-anchor :href="route('lists.index')">{{ __('Reset') }}</x-buttons.default-anchor>
                            @if(request('filter'))
                                <input type="hidden" name="filter" value="{{ request('filter') }}">
                            @endif
                        </form>
                        <x-form.input-error :messages="$errors->get('search')" />
                    </div>
                </div>
            </div>

            <ul role="list" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($linkedLists as $linkedList)
                    <li class="col-span-1 rounded-lg bg-white shadow">
                        <div class="flex flex-col h-full">
                            <div class="p-4">
                                <div class="flex justify-between mb-4">
                                    <x-badges.list-type-badge :linked_list="$linkedList"/>
                                    <div class="relative inline-block text-left">
                                        <x-buttons.secondary-button id="lists-card-action-menu-button"
                                                                    data-dropdown-toggle="dropdownDots">
                                            <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"
                                                 aria-hidden="true">
                                                <path
                                                    d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                                            </svg>
                                            {{ __('Actions') }}
                                        </x-buttons.secondary-button>

                                        <!-- Dropdown Menu -->
                                        <div id="lists-card-action-dropdown-menu"
                                             class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div class="py-1">
                                                <a href="{{ route('lists.items.index', $linkedList) }}"
                                                   class="flex items-center text-sm text-gray-700 hover:font-semibold hover:bg-gray-100 px-4 py-2">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                                    </svg>
                                                    {{ __('View Items') }}
                                                </a>
                                                <a href="{{ route('lists.edit', ['linkedList' => $linkedList]) }}"
                                                   class="flex items-center text-sm text-gray-700 hover:font-semibold hover:bg-gray-100 px-4 py-2">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2" d="M17 3l4 4m0 0L7 19H3v-4L15 3l4 4z"/>
                                                    </svg>
                                                    {{ __('Edit') }}
                                                </a>
                                                <button form="form-delete-list-{{ $linkedList->id }}"
                                                        onclick="return confirm('{{ __('Are you sure you want to delete this list?') }}')"
                                                        class="flex items-center w-full cursor-pointer text-sm bg-red-400 text-white hover:bg-red-500 hover:font-semibold px-4 py-2"
                                                >
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    {{ __('Delete') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start justify-between space-x-3">
                                    <div class="flex-1 truncate">
                                        <div class="flex items-center space-x-2">
                                            <h3 class="truncate text-lg font-medium text-gray-900">{{ $linkedList->name }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 text-sm text-gray-500 break-words">{{ $linkedList->description }}</p>
                            </div>

                            <div class="mt-auto border-t border-gray-200 p-2 space-y-1">
                                <div class="flex flex-wrap gap-1">
                                    <div class="flex items-center text-[10px] text-gray-500">
                                        <svg class="mr-1.5 h-4 w-4 flex-shrink-0 text-gray-400" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ __('Created: ') }} {{ $linkedList->created_at }}
                                    </div>
                                    <div class="flex items-center text-[10px] text-gray-500">
                                        <svg class="mr-1.5 h-4 w-4 flex-shrink-0 text-gray-400" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('Updated: ') }} {{ $linkedList->updated_at }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </li>

                    <form id="form-delete-list-{{ $linkedList->id }}" method="POST" class="hidden"
                          action="{{ route('lists.destroy', ['linkedList' => $linkedList]) }}">
                        @csrf
                        @method('DELETE')
                    </form>
                @empty
                    <li class="p-4 text-center text-gray-500">
                        {{ __('No lists yet. Add your first list!') }}
                    </li>
                @endforelse
            </ul>

            <div class="mt-6">
                {{ $linkedLists->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function handleTypeChange(select) {
                if (select.value === '') {
                    window.location.href = '{{ route('lists.index') }}';
                } else {
                    select.form.submit();
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const buttons = document.querySelectorAll("[id^='lists-card-action-menu-button']");
                const dropdowns = document.querySelectorAll("[id^='lists-card-action-dropdown-menu']");
                const closeAllDropdowns = (exceptIndex) => {
                    dropdowns.forEach((dropdown, idx) => {
                        if (idx !== exceptIndex) {
                            dropdown.classList.add('hidden');
                        }
                    });
                };

                buttons.forEach((button, index) => {
                    button.addEventListener("click", (e) => {
                        e.stopPropagation();
                        const dropdown = dropdowns[index];
                        const isHidden = dropdown.classList.contains('hidden');
                        closeAllDropdowns(index);
                        dropdown.classList.toggle('hidden', !isHidden);
                    });
                });

                document.addEventListener('click', () => {
                    closeAllDropdowns(-1);
                });
            });
        </script>
    @endpush
</x-app-layout>
