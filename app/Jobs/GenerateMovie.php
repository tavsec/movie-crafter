<?php

namespace App\Jobs;

use App\Models\Movie;
use App\Models\MovieAnswer;
use App\Models\MovieStoryBoard;
use App\MovieStatus;
use App\Services\AI;
use App\Services\Pinata;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Livewire\Volt\title;

class GenerateMovie implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Movie $movie)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(Pinata $pinataService, AI $aiService): void
    {
        try {
            DB::beginTransaction();
            $result = $aiService->prompt(
                title: $this->movie->title,
                genre: $this->movie->genre,
                description: $this->movie->description
            );
            $answer = $result->choices[0]->message->content;
            $parsed = json_decode($answer, true);
            MovieAnswer::query()->create([
                "answer_raw" => $answer,
                "scenario" => $parsed["scenario"],
                "story_boards" => $parsed["storyboards"],
                "title" => $this->movie->title,
                "short_description" => $parsed["short_description"],
                "error" => "",
                "metadata" => [
                    "prompt_tokens" => $result->usage->promptTokens,
                    "completion_tokens" => $result->usage->completionTokens,
                    "total_tokens" => $result->usage->totalTokens
                ],
                "is_successful" => true,
                "movie_id" => $this->movie->id
            ]);

            foreach ($parsed["storyboards"] as $i => $storyboard){
                $result = $aiService->generateStoryboardImage(
                    shortDescription: $parsed["short_description"],
                    storyboardDescription: $storyboard
                );

                $upload = $pinataService->uploadFile($this->movie->uuid . "-" . $i . ".png", null, file_get_contents($result->data[0]->url) );
                MovieStoryBoard::query()->create([
                    "movie_id" => $this->movie->id,
                    "description" => $storyboard,
                    "order" => $i,
                    "pinata_id" => $upload["data"]["id"],
                    "pinata_cid" => $upload["data"]["cid"]
                ]);
            }

            $this->movie->update([
                "status" => MovieStatus::SUCCESSFUL
            ]);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            Log::error($exception, ["movieId" => $this->movie->id]);

            $this->movie->update([
                "status" => MovieStatus::FAILED
            ]);
        }

    }
}
