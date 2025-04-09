<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LinkedListType;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Builder;

class LinkedListItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'linked_list_id',
        'value',
        'next_id',
    ];

    /**
     * Get the list that owns the item.
     */
    public function linkedList(): BelongsTo
    {
        return $this->belongsTo(LinkedList::class);
    }

    /**
     * Get the next item.
     *
     * @return BelongsTo
     */
    public function next(): BelongsTo
    {
        return $this->belongsTo(LinkedListItem::class, 'next_id');
    }

    /**
     * Validate and cast the value based on list type on item saving.
     * Update surrounding items next_id on item update/delete
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saved(function (LinkedListItem $item) {
            $item->saveItemNextId($item);
        });

        static::updating(function (LinkedListItem $item) {
            $item->updateNextIdSurroundingItems($item);
        });

        static::deleting(function (LinkedListItem $item) {
            $item->updateNextIdSurroundingItems($item);
        });
    }

    /**
     * Validate and cast integer value
     */
    private static function validateAndCastInteger(mixed $value): int
    {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException(__('Value must be a valid integer.'));
        }

        $intValue = (int) $value;

        if ((string) $intValue !== (string) $value) {
            throw new InvalidArgumentException(__('Value must be a valid integer without decimals.'));
        }

        return $intValue;
    }

    /**
     * Validate and cast string value
     */
    private static function validateAndCastString(mixed $value): string
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException(__('Value must be a valid string.'));
        }

        $value = trim($value);

        if (empty($value)) {
            throw new InvalidArgumentException(__('Value cannot be empty.'));
        }

        return $value;
    }

    private function saveItemNextId(LinkedListItem $item): void
    {
        DB::transaction(function() use ($item) {
            $listType = $item->linkedList->type;

            // Validate and cast the value based on list type
            $item->value = match($listType) {
                LinkedListType::INTEGERS_LINKED_LIST => self::validateAndCastInteger($item->value),
                LinkedListType::STRINGS_LINKED_LIST => self::validateAndCastString($item->value),
            };

            // Case 1: If the list is empty, keep the inserted item next_id as null
            $countWithoutSavedItem = LinkedListItem::query()
                ->where('id', '!=', $item->id)
                ->count();

            if ($countWithoutSavedItem === 0) {
                return;
            }

            // Case 2: Find the head traverse the list to determine the right position
            $allItems = $item->linkedList->items()
                ->where('id', '!=', $item->id);

            $head = $item->linkedList->headItem($allItems);

            $allItems = $allItems->get()
                ->keyBy('id');

            $current = $head;
            $prev = null;
            while ($current) {
                if ($current->value >= $item->value) {
                    break;
                }

                $prev = $current;
                $current = $current->next_id ? $allItems->get($current->next_id) : null;
            }

            $item->updateQuietly(['next_id' => $current?->id]);

            if ($prev) {
                $prev->updateQuietly(['next_id' => $item->id]);
            }
        });
    }

    public function updateNextIdSurroundingItems(LinkedListItem $item): void
    {
        DB::transaction(function() use ($item) {
            $allItems = $item->linkedList->items();

            $head = $item->linkedList->headItem($allItems);

            // Case 1: Item is alone
            if ($head === null) {
                return;
            }

            $allItems = $allItems->get()
                ->keyBy('id');

            // traverse the list and Update the old  item surrounding items next_id
            $current = $head;
            $prev = null;
            $next = null;
            while ($current) {
                if ($current->id === $item->id) {
                    break;
                }

                $prev = $current;
                $current = $current->next_id ? $allItems->get($current->next_id) : null;
                $next = $current->next_id ? $allItems->get($current->next_id) : null;
            }

            if ($prev) {
                $prev->updateQuietly(['next_id' => $next?->id]);
            }
        });
    }
}
