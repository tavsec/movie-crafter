<?php

use App\Models\Movie;
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
        Schema::create('movie_answers', function (Blueprint $table) {
            $table->id();
            $table->longText("answer_raw");
            $table->text("scenario");
            $table->json("story_boards");
            $table->text("title");
            $table->text("short_description");
            $table->json("metadata");
            $table->text("error")->nullable();
            $table->boolean("is_successful");
            $table->foreignIdFor(Movie::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_answers');
    }
};
