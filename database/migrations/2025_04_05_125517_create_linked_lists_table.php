<?php

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
        Schema::create('linked_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('type')->default(1)->index();
            $table->string('name')->fulltext();
            $table->string('description')->nullable();

            $table->unique(['type', 'name']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linked_lists');
    }
};
