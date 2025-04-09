<?php

namespace Database\Factories;

use App\Enums\LinkedListType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LinkedList>
 */
class LinkedListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement([LinkedListType::STRINGS_LINKED_LIST, LinkedListType::INTEGERS_LINKED_LIST]),
            'name' => fake()->unique()->word(),
            'description' => fake()->text(),
        ];
    }
}
