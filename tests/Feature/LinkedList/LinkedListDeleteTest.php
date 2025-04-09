<?php

namespace Tests\Feature\LinkedList;

use App\Models\LinkedList;
use App\Enums\LinkedListType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkedListDeleteTest extends TestCase
{
    use RefreshDatabase;

    private LinkedList $list;

    protected function setUp(): void
    {
        parent::setUp();
        $this->list = LinkedList::factory()->create([
            'type' => LinkedListType::STRINGS_LINKED_LIST
        ]);
    }

    public function test_it_deletes_list(): void
    {
        $response = $this->delete(route('lists.destroy', $this->list));

        $response->assertRedirect(route('lists.index'))
            ->assertSessionHas('success_message');

        $this->assertDatabaseMissing('linked_lists', [
            'id' => $this->list->id
        ]);
    }

    public function test_it_deletes_list_with_items(): void
    {
        // Create some items
        $this->list->items()->createMany([
            ['value' => 'item 1'],
            ['value' => 'item 2']
        ]);

        $itemIds = $this->list->items()->pluck('id')->toArray();

        $response = $this->delete(route('lists.destroy', $this->list));

        $response->assertRedirect(route('lists.index'));

        // Verify list is deleted
        $this->assertDatabaseMissing('linked_lists', [
            'id' => $this->list->id
        ]);

        // Verify all items are deleted
        foreach ($itemIds as $itemId) {
            $this->assertDatabaseMissing('linked_list_items', [
                'id' => $itemId
            ]);
        }
    }

    public function test_it_handles_nonexistent_list(): void
    {
        $nonexistentId = $this->list->id + 1;

        $response = $this->delete(route('lists.destroy', $nonexistentId));

        $response->assertStatus(404);
    }
}
