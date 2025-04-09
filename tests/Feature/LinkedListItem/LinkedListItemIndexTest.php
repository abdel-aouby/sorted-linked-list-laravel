<?php

namespace Tests\Feature\LinkedListItem;

use App\Models\LinkedList;
use App\Models\LinkedListItem;
use App\Enums\LinkedListType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkedListItemIndexTest extends TestCase
{
    use RefreshDatabase;

    private LinkedList $integerList;
    private LinkedList $stringList;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test lists
        $this->integerList = LinkedList::factory()->create([
            'type' => LinkedListType::INTEGERS_LINKED_LIST,
            'name' => 'Test Integer List'
        ]);

        $this->stringList = LinkedList::factory()->create([
            'type' => LinkedListType::STRINGS_LINKED_LIST,
            'name' => 'Test String List'
        ]);
    }

    public function test_it_shows_items_index_page(): void
    {
        $response = $this->get(route('lists.items.index', $this->integerList));

        $response->assertStatus(200)
            ->assertViewIs('lists.items.index')
            ->assertSee($this->integerList->name)
            ->assertSee(__('No items yet. Add your first item!'));
    }

    public function test_it_displays_list_information(): void
    {
        $response = $this->get(route('lists.items.index', $this->integerList));

        $response->assertStatus(200)
            ->assertSee($this->integerList->name)
            ->assertSee($this->integerList->description)
            ->assertSee($this->integerList->type->label());
    }

    public function test_it_shows_items_in_correct_order(): void
    {
        // For integer list
        $this->integerList->items()->create(['value' => '5']);
        $this->integerList->items()->create(['value' => '1']);
        $this->integerList->items()->create(['value' => '3']);

        $response = $this->get(route('lists.items.index', $this->integerList));

        $response->assertStatus(200)
            ->assertSeeInOrder(['1', '3', '5']);

        // For string list
        $this->stringList->items()->create(['value' => 'banana']);
        $this->stringList->items()->create(['value' => 'az']);
        $this->stringList->items()->create(['value' => 'apple']);
        $this->stringList->items()->create(['value' => 'azdddd']);
        $this->stringList->items()->create(['value' => 'cherry']);

        $response = $this->get(route('lists.items.index', $this->stringList));

        $response->assertStatus(200)
            ->assertSeeInOrder(['apple', 'az', 'azdddd', 'banana', 'cherry']);
    }


    public function test_it_searches_integer_item(): void
    {
        $this->integerList->items()->createMany([
            ['value' => '123'],
            ['value' => '456'],
            ['value' => '789'],
            ['value' => '321']
        ]);

        $response = $this->get(route('lists.items.index', [
            'linkedList' => $this->integerList,
            'search' => '456'
        ]));

        $response->assertStatus(200)
            ->assertSee('456')
            ->assertDontSee('321')
            ->assertDontSee('789');
    }

    public function test_it_searches_string_item(): void
    {
        $this->stringList->items()->createMany([
            ['value' => 'apple'],
            ['value' => 'banana'],
            ['value' => 'application'],
            ['value' => 'cherry']
        ]);

        $response = $this->get(route('lists.items.index', [
            'linkedList' => $this->stringList,
            'search' => 'app'
        ]));

        $response->assertStatus(200)
            ->assertSee('app')
            ->assertDontSee('application')
            ->assertDontSee('cherry');
    }

    public function test_it_shows_add_item_button(): void
    {
        $response = $this->get(route('lists.items.index', $this->stringList));

        $response->assertStatus(200)
            ->assertSee(__('Add item'))
            ->assertSee(route('lists.items.create', $this->stringList));
    }

    public function test_it_shows_item_actions(): void
    {
        $item = $this->stringList->items()->create(['value' => 'test item']);

        $response = $this->get(route('lists.items.index', $this->stringList));

        $response->assertStatus(200)
            ->assertSee('Edit')
            ->assertSee('Delete')
            ->assertSee(route('lists.items.edit', ['linkedList' => $this->stringList, 'item' => $item]));
    }
}
