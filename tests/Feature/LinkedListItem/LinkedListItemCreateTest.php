<?php

namespace Tests\Feature\LinkedListItem;

use App\Models\LinkedList;
use App\Models\LinkedListItem;
use App\Enums\LinkedListType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkedListItemCreateTest extends TestCase
{
    use RefreshDatabase;

    private LinkedList $integerList;
    private LinkedList $stringList;

    protected function setUp(): void
    {
        parent::setUp();

        $this->integerList = LinkedList::factory()->create([
            'type' => LinkedListType::INTEGERS_LINKED_LIST,
            'name' => 'Test Integer List'
        ]);

        $this->stringList = LinkedList::factory()->create([
            'type' => LinkedListType::STRINGS_LINKED_LIST,
            'name' => 'Test String List'
        ]);
    }

    public function test_it_shows_create_form(): void
    {
        $response = $this->get(route('lists.items.create', $this->integerList));

        $response->assertStatus(200)
            ->assertViewIs('lists.items.create')
            ->assertSee(__('Add Item'))
            ->assertSee($this->integerList->name)
            ->assertSee(__('Edit the value for your list item.'));
    }

    public function test_it_creates_integer_item(): void
    {
        $response = $this->post(route('lists.items.store', $this->integerList), [
            'linked_list_id' => $this->integerList->id,
            'value' => '42'
        ]);

        $response->assertRedirect(route('lists.items.index', $this->integerList))
            ->assertSessionHas('success_message');

        $this->assertDatabaseHas('linked_list_items', [
            'linked_list_id' => $this->integerList->id,
            'value' => '42'
        ]);
    }

    public function test_it_creates_string_item(): void
    {
        $response = $this->post(route('lists.items.store', $this->stringList), [
            'linked_list_id' => $this->stringList->id,
            'value' => 'test item'
        ]);

        $response->assertRedirect(route('lists.items.index', $this->stringList))
            ->assertSessionHas('success_message');

        $this->assertDatabaseHas('linked_list_items', [
            'linked_list_id' => $this->stringList->id,
            'value' => 'test item'
        ]);
    }

    public function test_it_validates_required_value(): void
    {
        $response = $this->post(route('lists.items.store', $this->integerList), [
            'linked_list_id' => $this->integerList->id,
            'value' => ''
        ]);

        $response->assertSessionHasErrors(['value']);
    }

    public function test_it_validates_integer_type(): void
    {
        $response = $this->post(route('lists.items.store', $this->integerList), [
            'linked_list_id' => $this->integerList->id,
            'value' => 'not an integer'
        ]);

        $response->assertSessionHasErrors(['value']);

        $response = $this->post(route('lists.items.store', $this->integerList), [
            'linked_list_id' => $this->integerList->id,
            'value' => '3.14'
        ]);

        $response->assertSessionHasErrors(['value']);
    }

    public function test_it_validates_string_type(): void
    {
        $response = $this->post(route('lists.items.store', $this->stringList), [
            'linked_list_id' => $this->stringList->id,
            'value' => str_repeat('a', 256)
        ]);

        $response->assertSessionHasErrors(['value']);

        $response = $this->post(route('lists.items.store', $this->stringList), [
            'linked_list_id' => $this->stringList->id,
            'value' => 5
        ]);

        $response->assertSessionHasErrors(['value']);
    }

    public function test_it_maintains_sort_order_for_integers(): void
    {
        // Create items in unsorted order
        $this->integerList->items()->create(['value' => '5']);
        $this->integerList->items()->create(['value' => '3']);

        // Add new item
        $this->post(route('lists.items.store', $this->integerList), [
            'linked_list_id' => $this->integerList->id,
            'value' => '4'
        ]);

        $items = $this->integerList->items()->pluck('value')->toArray();
        $this->assertEquals(['3', '4', '5'], $items);
    }

    public function test_it_maintains_sort_order_for_strings(): void
    {
        // Create items in unsorted order
        $this->stringList->items()->create(['value' => 'banana']);
        $this->stringList->items()->create(['value' => 'cherry']);

        // Add new item
        $this->post(route('lists.items.store', $this->stringList), [
            'linked_list_id' => $this->stringList->id,
            'value' => 'apple']);

        $items = $this->stringList->items()->pluck('value')->toArray();
        $this->assertEquals(['apple', 'banana', 'cherry'], $items);
    }

    public function test_it_prevents_duplicate_values(): void
    {
        $this->integerList->items()->create(['value' => '42']);

        $response = $this->post(route('lists.items.store', $this->integerList), [
            'linked_list_id' => $this->integerList->id,
            'value' => '42'
        ]);

        $response->assertSessionHasErrors(['value']);

        $this->stringList->items()->create(['value' => 'test']);

        $response = $this->post(route('lists.items.store', $this->stringList), [
            'linked_list_id' => $this->stringList->id,
            'value' => 'test'
        ]);

        $response->assertSessionHasErrors(['value']);
    }

    public function test_it_handles_zero_integer(): void
    {
        $response = $this->post(route('lists.items.store', $this->integerList), [
            'linked_list_id' => $this->integerList->id,
            'value' => '0'
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success_message');

        $this->assertDatabaseHas('linked_list_items', [
            'linked_list_id' => $this->integerList->id,
            'value' => '0'
        ]);
    }

    public function test_it_handles_negative_integers(): void
    {
        $response = $this->post(route('lists.items.store', $this->integerList), [
            'linked_list_id' => $this->integerList->id,
            'value' => '-42'
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success_message');

        $this->assertDatabaseHas('linked_list_items', [
            'linked_list_id' => $this->integerList->id,
            'value' => '-42'
        ]);
    }
}
