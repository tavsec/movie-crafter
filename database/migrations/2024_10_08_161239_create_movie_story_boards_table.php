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
        Schema::create('movie_story_boards', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Movie::class)->constrained();
            $table->unsignedInteger("order");
            $table->text("description");
            $table->string("pinata_id");
            $table->string("pinata_cid");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_story_boards');
    }
};
