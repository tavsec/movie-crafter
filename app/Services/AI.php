<?php

namespace App\Services;

use OpenAI;
use OpenAI\Client;

class AI
{
    protected Client $client;

    public function __construct()
    {
        $this->client = OpenAI::client(config("openai.key"));
    }

    public function prompt($title, $genre, $description)
    {
        return $this->client->chat()->create([
            "model" => "gpt-4o-mini",
            "messages" => [
                [
                    "role" => "system",
                    "content" => config("openai.system_prompt")
                ],
                [
                    "role" => "user",
                    "content" => "
                        Title: `$title` \n
                        Genre: `$genre` \n
                        Description: `$description`
                    "
                ]
            ],
            "response_format" => [
                "type" => "json_schema",
                "json_schema" => [
                    "name" => "movie-structure-schema",
                    "strict" => true,
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "scenario" => [
                                "type" => "string"
                            ],
                            "short_description" => [
                                "type" => "string"
                            ],
                            "storyboards" => [
                                "type" => "array",
                                "items" => [
                                    "type" => "string"
                                ],
                            ]
                        ],
                        "required" => [
                            "scenario",
                            "storyboards",
                            "short_description"
                        ],
                        "additionalProperties" => false
                    ]
                ]
            ]
        ]);
    }

    public function generateStoryboardImage($shortDescription, $storyboardDescription){
        return $this->client->images()->create([
            'model' => 'dall-e-3',
            'prompt' => "You are tasked to draw the storyboard images for a movie. Below, you will find the movie description, and an instruction for a storyboard image. Be sure to use the storyboard image style (black and white) for the sketch image. \n
                        ----- \n
                        \n
                        # Movie short description \n
                        $shortDescription \n
                        # Storyboard description\n
                        $storyboardDescription
                        ",
            'n' => 1,
            'size' => '1024x1024',
            'response_format' => 'url',
        ]);
    }
}
