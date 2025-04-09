<?php

namespace Database\Seeders;

use App\Models\LinkedList;
use Illuminate\Database\Seeder;

class LinkedListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LinkedList::factory(50)->create();
    }
}
