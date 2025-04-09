<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\LinkedListType;
use App\Http\Requests\CreateListRequest;
use App\Models\LinkedList;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LinkedListController extends Controller
{
    /**
     * Display a listing of the linked lists.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate(
            [
                'search' => ['sometimes', 'required', 'string', 'min:3', 'max:100'],
                'filter' => ['sometimes', 'required', Rule::enum(LinkedListType::class)],
            ]
        );

        $linkedLists = LinkedList::query()
            ->searchByName($validated['search'] ?? null)
            ->filterByType($validated['filter'] ?? null)
            ->latest()
            ->paginate(50);

        return view('lists.index', compact('linkedLists'));
    }

    /**
     * Show the form for creating a new linked list.
     */
    public function create(): View
    {
        return view('lists.create');
    }

    /**
     * Store a newly created linked list.
     */
    public function store(CreateListRequest $request): RedirectResponse
    {
        try {
            LinkedList::create($request->validated());
        } catch (\Exception $e) {
            report($e);
            return back()->with('error_message', __('Unable to create list. Please try again.'));
        }

        return redirect()->route('lists.index')->with('success_message', __('List created successfully.'));
    }

    /**
     * Show the form for editing the linked list.
     */
    public function edit(LinkedList $linkedList): View
    {
        return view('lists.edit', ['linkedList' => $linkedList]);
    }

    /**
     * Update the specified linked list.
     */
    public function update(CreateListRequest $request, LinkedList $linkedList): RedirectResponse
    {
        try {
            $linkedList->update($request->only(['name', 'description']));
        } catch (\Exception $e) {
            report($e);
            return back()->with('error_message', __('Unable to update list. Please try again.'));
        }

        return redirect()->route('lists.index')->with('success_message', __('List updated successfully.'));
    }

    /**
     * Remove the specified linked list.
     */
    public function destroy(LinkedList $linkedList): RedirectResponse
    {
        try {
            $linkedList->delete();
        } catch (\Exception $e) {
            report($e);
            return back()->with('error_message', __('Unable to delete list. Please try again.'));
        }

        return redirect()->route('lists.index')->with('success_message', __('List deleted successfully.'));
    }
}
