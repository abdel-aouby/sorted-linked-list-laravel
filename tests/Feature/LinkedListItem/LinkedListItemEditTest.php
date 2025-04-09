<?php

namespace Tests\Feature\LinkedListItem;

use App\Models\LinkedList;
use App\Models\LinkedListItem;
use App\Enums\LinkedListType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkedListItemEditTest extends TestCase
{
    use RefreshDatabase;

    private LinkedList $integerList;
    private LinkedList $stringList;
    private LinkedListItem $integerItem;
    private LinkedListItem $stringItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->integerList = LinkedList::factory()->create([
            'type' => LinkedListType::INTEGERS_LINKED_LIST
        ]);

        $this->stringList = LinkedList::factory()->create([
            'type' => LinkedListType::STRINGS_LINKED_LIST
        ]);

        $this->integerItem = $this->integerList->items()->create(['value' => '42']);
        $this->stringItem = $this->stringList->items()->create(['value' => 'test item']);
    }

    public function test_it_shows_edit_form(): void
    {
        $response = $this->get(route('lists.items.edit', [
            'linkedList' => $this->integerList,
            'item' => $this->integerItem
        ]));

        $response->assertStatus(200)
            ->assertViewIs('lists.items.edit')
            ->assertSee(__('Update Item'))
            ->assertSee('42');
    }

    public function test_it_updates_integer_item(): void
    {
        $response = $this->patch(route('lists.items.update', [
            'linkedList' => $this->integerList,
            'linked_list_id' => $this->integerList->id,
            'item' => $this->integerItem
        ]), [
            'value' => '24'
        ]);

        $response->assertRedirect(route('lists.items.index', $this->integerList))
            ->assertSessionHas('success_message');

        $this->assertDatabaseHas('linked_list_items', [
            'id' => $this->integerItem->id,
            'value' => '24'
        ]);
    }

    public function test_it_updates_string_item(): void
    {
        $response = $this->patch(route('lists.items.update', [
            'linkedList' => $this->stringList,
            'linked_list_id' => $this->stringList->id,
            'item' => $this->stringItem
        ]), [
            'value' => 'updated item'
        ]);

        $response->assertRedirect(route('lists.items.index', $this->stringList));

        $this->assertDatabaseHas('linked_list_items', [
            'id' => $this->stringItem->id,
            'value' => 'updated item'
        ]);
    }

    public function test_it_maintains_sort_order_after_update(): void
    {
        // Create additional items
        $this->integerList->items()->createMany([
            ['value' => '10'],
            ['value' => '30'],
            ['value' => '50']
        ]);

        // Update item to a value that should change its position
        $this->patch(route('lists.items.update', [
            'linkedList' => $this->integerList,
            'linked_list_id' => $this->integerList->id,
            'item' => $this->integerItem
        ]), [
            'value' => '20'
        ]);

        $items = $this->integerList->items()->pluck('value')->toArray();
        $this->assertEquals(['10', '20', '30', '50'], $items);
    }

    public function test_it_validates_required_value(): void
    {
        $response = $this->patch(route('lists.items.update', [
            'linkedList' => $this->integerList,
            'linked_list_id' => $this->integerList->id,
            'item' => $this->integerItem
        ]), [
            'value' => ''
        ]);

        $response->assertSessionHasErrors(['value']);
    }

    public function test_it_validates_integer_type(): void
    {
        $response = $this->patch(route('lists.items.update', [
            'linkedList' => $this->integerList,
            'linked_list_id' => $this->integerList->id,
            'item' => $this->integerItem
        ]), [
            'value' => 'not an integer'
        ]);

        $response->assertSessionHasErrors(['value']);
    }

    public function test_it_prevents_duplicate_values(): void
    {
        // Create another item
        $this->integerList->items()->create(['value' => '100']);

        // Try to update to existing value
        $response = $this->patch(route('lists.items.update', [
            'linkedList' => $this->integerList,
            'linked_list_id' => $this->integerList->id,
            'item' => $this->integerItem
        ]), [
            'value' => '100'
        ]);

        $response->assertSessionHasErrors(['value']);
    }

    public function test_it_allows_same_value_for_same_item(): void
    {
        $response = $this->patch(route('lists.items.update', [
            'linkedList' => $this->integerList,
            'linked_list_id' => $this->integerList->id,
            'item' => $this->integerItem
        ]), [
            'value' => '42'
        ]);

        $response->assertRedirect()->assertSessionDoesntHaveErrors();
    }
}
