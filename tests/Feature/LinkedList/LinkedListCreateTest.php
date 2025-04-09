<?php

namespace Tests\Feature\LinkedList;

use App\Models\LinkedList;
use App\Enums\LinkedListType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkedListCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        LinkedList::query()->delete();
    }

    public function test_it_can_show_create_list_form(): void
    {
        $response = $this->get(route('lists.create'));

        $response->assertStatus(200)
            ->assertViewIs('lists.create')
            ->assertSee('Create New List')
            ->assertSee(LinkedListType::STRINGS_LINKED_LIST->label())
            ->assertSee(LinkedListType::INTEGERS_LINKED_LIST->label());
    }

    public function test_it_can_create_integer_list(): void
    {
        $listData = [
            'type' => LinkedListType::INTEGERS_LINKED_LIST->value,
            'name' => 'Test Integer List',
            'description' => 'This is a test integer list description'
        ];

        $response = $this->post(route('lists.store'), $listData);

        $response->assertRedirect(route('lists.index'))
            ->assertSessionHas('success_message');

        $this->assertDatabaseHas('linked_lists', [
            'name' => 'Test Integer List',
            'type' => LinkedListType::INTEGERS_LINKED_LIST->value
        ]);
    }

    public function test_it_can_create_string_list(): void
    {
        $listData = [
            'type' => LinkedListType::STRINGS_LINKED_LIST->value,
            'name' => 'Test String List',
            'description' => 'This is a test string list description'
        ];

        $response = $this->post(route('lists.store'), $listData);

        $response->assertRedirect(route('lists.index'))
            ->assertSessionHas('success_message');

        $this->assertDatabaseHas('linked_lists', [
            'name' => 'Test String List',
            'type' => LinkedListType::STRINGS_LINKED_LIST->value
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->post(route('lists.store'), []);

        $response->assertSessionHasErrors(['name', 'type']);
    }

    public function test_it_validates_unique_name(): void
    {
        LinkedList::factory()->create([
            'type' => LinkedListType::STRINGS_LINKED_LIST->value,
            'name' => 'Test List',
        ]);

        // Try to create another list with the same name
        $listData = [
            'type' => LinkedListType::STRINGS_LINKED_LIST->value,
            'name' => 'Test List',
            'description' => 'Test description'
        ];

        $response = $this->post(route('lists.store'), $listData);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_it_validates_valid_type_enum(): void
    {
        $listData = [
            'type' => 999,
            'name' => 'Test List',
            'description' => 'Test description'
        ];

        $response = $this->post(route('lists.store'), $listData);

        $response->assertSessionHasErrors(['type']);
    }

    public function test_create_button_exists_on_index_page(): void
    {
        $response = $this->get(route('lists.index'));

        $response->assertStatus(200)
            ->assertSee('Create List')
            ->assertSee(route('lists.create'));
    }
}
