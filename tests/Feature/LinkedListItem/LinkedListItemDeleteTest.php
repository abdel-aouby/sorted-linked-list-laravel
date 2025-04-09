<?php

namespace Tests\Feature\LinkedListItem;

use App\Models\LinkedList;
use App\Models\LinkedListItem;
use App\Enums\LinkedListType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkedListItemDeleteTest extends TestCase
{
    use RefreshDatabase;

    private LinkedList $list;
    private LinkedListItem $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->list = LinkedList::factory()->create([
            'type' => LinkedListType::STRINGS_LINKED_LIST
        ]);

        $this->item = $this->list->items()->create([
            'value' => 'test item'
        ]);
    }

    public function test_it_deletes_item(): void
    {
        $response = $this->delete(route('lists.items.destroy', [
            'linkedList' => $this->list,
            'item' => $this->item
        ]));

        $response->assertRedirect(route('lists.items.index', $this->list))
            ->assertSessionHas('success_message');

        $this->assertDatabaseMissing('linked_list_items', [
            'id' => $this->item->id
        ]);
    }

    public function test_it_maintains_sort_order_after_delete(): void
    {
        // Create additional items
        $this->list->items()->createMany([
            ['value' => 'apple'],
            ['value' => 'banana'],
            ['value' => 'cherry']
        ]);

        // Delete middle item
        $middleItem = $this->list->items()->where('value', 'banana')->first();

        $this->delete(route('lists.items.destroy', [
            'linkedList' => $this->list,
            'item' => $middleItem
        ]));

        $items = $this->list->items()->pluck('value')->toArray();
        $this->assertEquals(['apple', 'cherry', 'test item'], $items);
    }

    public function test_it_handles_nonexistent_item(): void
    {
        $nonexistentId = $this->item->id + 1;

        $response = $this->delete(route('lists.items.destroy', [
            'linkedList' => $this->list,
            'item' => $nonexistentId
        ]));

        $response->assertStatus(404);
    }

    public function test_it_validates_item_belongs_to_list(): void
    {
        // Create another list and item
        $anotherList = LinkedList::factory()->create([
            'type' => LinkedListType::STRINGS_LINKED_LIST
        ]);

        $anotherItem = $anotherList->items()->create([
            'value' => 'another item'
        ]);

        // Try to delete item using wrong list
        $response = $this->delete(route('lists.items.destroy', [
            'linkedList' => $this->list,
            'item' => $anotherItem
        ]));

        $response->assertStatus(404);
    }

    public function test_it_updates_list_item_count(): void
    {
        $initialCount = $this->list->items()->count();

        $this->delete(route('lists.items.destroy', [
            'linkedList' => $this->list,
            'item' => $this->item
        ]));

        $this->assertEquals($initialCount - 1, $this->list->items()->count());
    }
}
