<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\LinkedListFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\LinkedListType;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class LinkedList extends Model
{
    /** @use HasFactory<LinkedListFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description', 'type'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => LinkedListType::class,
    ];

    /**
     * Get the items for the linked list in sorted order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(LinkedListItem::class);
    }

    /**
     * Get head item
     */
    public function headItem(?HasMany $items = null): ?LinkedListItem
    {
        if ($items === null) {
            $items = $this->items();
        }

        $items = $items->get()->keyBy('id');

        $itemsNextIds = $items->pluck('next_id')->filter()->toArray();
        return $items->first(function ($item) use ($itemsNextIds) {
            return !in_array($item->id, $itemsNextIds);
        });
    }

    /**
     * Get ordered items
     */
    public function sortedItems(?HasMany $items = null): Collection
    {
        if ($items === null) {
            $items = $this->items();
        }

        $head = $this->headItem($items);
        $items = $items->get()->keyBy('id');

        $ordered = collect();
        $current = $head;

        while ($current) {
            $ordered->push($current);
            $current = $current->next_id ? $items->get($current->next_id) : null;
        }

        return $ordered;
    }

    /**
     * Get exact match from list, all items ordered if value is empty
     */
    public function findItem(string $value, ?HasMany $items = null): Collection
    {
        if ($items === null) {
            $items = $this->items();
        }

        if (empty($value)) {
            return $this->sortedItems($items);
        }

        $head = $this->headItem($items);
        $items = $items->get()->keyBy('id');

        $collection = collect();
        $current = $head;

        while ($current) {
            if ($current->value === $value) {
                $collection->push($current);
                return $collection;
            }
            $current = $current->next_id ? $items->get($current->next_id) : null;
        }

        return $collection;
    }

    /**
     * Scope a query to search for lists by name.
     */
    #[Scope]
    protected function searchByName(Builder $query, ?string $search): void
    {
        $query->when($search, function (Builder $query, string $search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to filter lists by type.
     */
    #[Scope]
    protected function filterByType(Builder $query, ?string $filter): void
    {
        $query->when($filter, function (Builder $query, string $filter) {
            $query->where('type', $filter);
        });
    }
}
