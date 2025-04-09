<?php

namespace Tests\Feature\LinkedList;

use App\Models\LinkedList;
use App\Enums\LinkedListType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkedListEditTest extends TestCase
{
    use RefreshDatabase;

    private LinkedList $list;

    protected function setUp(): void
    {
        parent::setUp();
        $this->list = LinkedList::factory()->create([
            'type' => LinkedListType::STRINGS_LINKED_LIST,
            'name' => 'Original List',
            'description' => 'Original Description'
        ]);
    }

    public function test_it_shows_edit_form(): void
    {
        $response = $this->get(route('lists.edit', $this->list));

        $response->assertStatus(200)
            ->assertViewIs('lists.edit')
            ->assertSee(__('Edit Linked Lists'))
            ->assertSee('Original List')
            ->assertSee('Original Description')
            ->assertSee(LinkedListType::STRINGS_LINKED_LIST->label());
    }

    public function test_it_updates_list(): void
    {
        $response = $this->patch(route('lists.update', $this->list), [
            'type' => LinkedListType::STRINGS_LINKED_LIST->value,
            'name' => 'Updated List',
            'description' => 'Updated Description'
        ]);

        $response->assertRedirect(route('lists.index'))
            ->assertSessionHas('success_message');

        $this->assertDatabaseHas('linked_lists', [
            'id' => $this->list->id,
            'name' => 'Updated List',
            'description' => 'Updated Description'
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->patch(route('lists.update', $this->list), [
            'name' => '',
            'type' => ''
        ]);

        $response->assertSessionHasErrors(['name', 'type']);
    }

    public function test_it_prevents_type_change_with_existing_items(): void
    {
        $this->patch(route('lists.update', $this->list), [
            'type' => LinkedListType::INTEGERS_LINKED_LIST->value,
            'name' => 'Updated List'
        ]);

        $this->assertDatabaseHas('linked_lists', [
            'id' => $this->list->id,
            'name' => 'Updated List',
            'type' => LinkedListType::STRINGS_LINKED_LIST->value
        ]);

        $this->assertDatabaseMissing('linked_lists', [
            'id' => $this->list->id,
            'name' => 'Updated List',
            'type' => LinkedListType::INTEGERS_LINKED_LIST->value
        ]);
    }

    public function test_it_allows_same_name_for_same_list(): void
    {
        $response = $this->patch(route('lists.update', $this->list), [
            'type' => $this->list->type->value,
            'name' => $this->list->name,
            'description' => 'Updated Description'
        ]);

        $response->assertRedirect(route('lists.index'))
            ->assertSessionDoesntHaveErrors();
    }

    public function test_it_validates_unique_name_within_type(): void
    {
        LinkedList::factory()->create([
            'type' => LinkedListType::STRINGS_LINKED_LIST,
            'name' => 'Another List'
        ]);

        $response = $this->patch(route('lists.update', $this->list), [
            'type' => LinkedListType::STRINGS_LINKED_LIST->value,
            'name' => 'Another List'
        ]);

        $response->assertSessionHasErrors(['name']);
    }
}
