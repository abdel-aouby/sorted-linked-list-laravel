<?php

use App\Models\LinkedList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('linked_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LinkedList::class)->constrained()->cascadeOnDelete();
            $table->string('value')->fulltext();
            $table->foreignId('next_id')->nullable()->constrained('linked_list_items');
            $table->timestamps();

            $table->unique(['linked_list_id', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linked_list_items');
    }
};
