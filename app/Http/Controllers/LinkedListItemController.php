<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateListItemRequest;
use App\Models\LinkedList;
use App\Models\LinkedListItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;
use Illuminate\Http\Request;

class LinkedListItemController extends Controller
{
    /**
     * Display a listing of the list items.
     */
    public function index(Request $request, LinkedList $linkedList): View
    {
        $validated = $request->validate(['search' => 'sometimes|required|min:1|max:255']);

        $items = $linkedList->findItem($validated['search'] ?? '');

        $perPage = 50;
        $page = request('page', 1);
        $paginated = new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('lists.items.index', [
            'linkedList' => $linkedList,
            'items' => $paginated
        ]);
    }

    /**
     * Show the form for creating a new list item.
     */
    public function create(LinkedList $linkedList): View
    {
        return view('lists.items.create', ['linkedList' => $linkedList]);
    }

    /**
     * Store a newly created list item.
     */
    public function store(CreateListItemRequest $request, LinkedList $linkedList): RedirectResponse
    {
        try {
            $linkedList->items()->create($request->only(['value', 'linked_list_id']));

            return redirect()
                ->route('lists.items.index', $linkedList)
                ->with('success_message', __('Item added successfully.'));
        } catch (InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors(['value' => $e->getMessage()]);
        } catch (\Exception $e) {
            report($e);
            return back()->with('error_message', __('Unable to save item. Please try again.'));
        }
    }

    /**
     * Show the form for editing the list item.
     */
    public function edit(LinkedList $linkedList, LinkedListItem $item): View
    {
        return view('lists.items.edit', [
            'linkedList' => $linkedList,
            'item' => $item
        ]);
    }

    /**
     * Update the specified list item.
     */
    public function update(CreateListItemRequest $request, LinkedList $linkedList, LinkedListItem $item): RedirectResponse
    {
        if ($item->linked_list_id !== $linkedList->id) {
            abort(404);
        }

        try {
            $item->update($request->only(['value']));

            return redirect()
                ->route('lists.items.index', $linkedList)
                ->with('success_message', __('Item updated successfully.'));
        } catch (InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors(['value' => $e->getMessage()]);
        } catch (\Exception $e) {
            report($e);
            return back()->with('error_message', __('Unable to update item. Please try again.'));
        }
    }

    /**
     * Remove the specified list item.
     */
    public function destroy(LinkedList $linkedList, LinkedListItem $item): RedirectResponse
    {
        if ($item->linked_list_id !== $linkedList->id) {
            abort(404);
        }

        try {
            $item->delete();
        } catch (\Exception $e) {
            report($e);
            return back()->with('error_message', __('Unable to delete item. Please try again.'));
        }

        return redirect()
            ->route('lists.items.index', $linkedList)
            ->with('success_message', __('Item deleted successfully.'));
    }
}
