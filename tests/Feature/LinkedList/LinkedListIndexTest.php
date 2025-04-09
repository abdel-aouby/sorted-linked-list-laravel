<?php

namespace Tests\Feature\LinkedList;

use App\Models\LinkedList;
use App\Enums\LinkedListType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkedListIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        LinkedList::query()->delete();
    }

    public function test_it_can_display_empty_lists_page(): void
    {
        $response = $this->get(route('lists.index'));

        $response->assertStatus(200);
        $response->assertViewIs('lists.index');
        $response->assertViewHas('linkedLists');
        $response->assertSee(__('No lists yet. Add your first list!'));
    }

    public function test_it_displays_lists_in_correct_order(): void
    {
        $oldList = LinkedList::factory()->create(['created_at' => now()->subDays(1)]);
        $newList = LinkedList::factory()->create(['created_at' => now()]);

        $response = $this->get(route('lists.index'));

        $response->assertStatus(200);
        $response->assertViewIs('lists.index');
        $response->assertSeeInOrder([
            $newList->name,
            $oldList->name
        ]);
    }

    public function test_it_can_filter_lists_by_type(): void
    {
        LinkedList::factory()->create([
            'type' => LinkedListType::INTEGERS_LINKED_LIST,
            'name' => 'Integer List'
        ]);

        LinkedList::factory()->create([
            'type' => LinkedListType::STRINGS_LINKED_LIST,
            'name' => 'String List'
        ]);

        $response = $this->get(route('lists.index', [
            'filter' => LinkedListType::INTEGERS_LINKED_LIST->value
        ]));

        $response->assertStatus(200)
            ->assertSee('Integer List')
            ->assertDontSee('String List');

        $response = $this->get(route('lists.index', [
            'filter' => LinkedListType::STRINGS_LINKED_LIST->value
        ]));

        $response->assertStatus(200)
            ->assertSee('String List')
            ->assertDontSee('Integer List');
    }

    public function test_it_can_search_lists_by_name(): void
    {
        LinkedList::factory()->create(['name' => 'First List']);
        LinkedList::factory()->create(['name' => 'Second List']);
        LinkedList::factory()->create(['name' => 'Different Name']);

        $response = $this->get(route('lists.index', ['search' => 'List']));

        $response->assertStatus(200)
            ->assertSee('First List')
            ->assertSee('Second List')
            ->assertDontSee('Different Name');
    }

    public function test_it_can_combine_type_filter_and_search(): void
    {
        LinkedList::factory()->create([
            'type' => LinkedListType::INTEGERS_LINKED_LIST,
            'name' => 'Integer List One'
        ]);

        LinkedList::factory()->create([
            'type' => LinkedListType::STRINGS_LINKED_LIST,
            'name' => 'String List One'
        ]);

        LinkedList::factory()->create([
            'type' => LinkedListType::INTEGERS_LINKED_LIST,
            'name' => 'Different Integer'
        ]);

        $response = $this->get(route('lists.index', [
            'filter' => LinkedListType::INTEGERS_LINKED_LIST->value,
            'search' => 'List'
        ]));

        $response->assertStatus(200)
            ->assertSee('Integer List One')
            ->assertDontSee('String List One')
            ->assertDontSee('Different Integer');
    }


    public function test_it_shows_correct_empty_grid_for_filtered_results(): void
    {
        LinkedList::factory()->create(['name' => 'Test List']);

        $response = $this->get(route('lists.index', ['search' => 'NonExistent']));

        $response->assertStatus(200)
            ->assertSee(__('No lists yet. Add your first list!'))
            ->assertDontSee('Test List');
    }
}
