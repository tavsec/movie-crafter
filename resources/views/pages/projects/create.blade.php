<?php

use App\Models\Movie;
use Illuminate\Validation\Rule;
use function Laravel\Folio\{middleware, name};

use function Livewire\Volt\{with, state, rules, mount};

name('projects.create');
middleware(['auth', 'verified']);

$genreOptions = [
    'action' => 'Action',
    'comedy' => 'Comedy',
    'drama' => 'Drama',
    'fantasy' => 'Fantasy',
    'horror' => 'Horror',
    'mystery' => 'Mystery',
    'romance' => 'Romance',
    'thriller' => 'Thriller',
    'western' => 'Western',
];

state([
    "description" => "",
    "genre" => "action",
    "title" => "",
    "genreOptions" => $genreOptions
]);


$generate = function () use ($genreOptions) {
    $validated = $this->validate([
        'description' => ['required', 'min:50', 'max:512'],
        'title' => ['required', 'min:2', 'max:50'],
        'genre' => ['required', Rule::in(array_keys($genreOptions))]
    ]);

    $movie = Movie::query()->create([
        "uuid" => \Ramsey\Uuid\Uuid::uuid7()->toString(),
        "user_id" => auth()->user()->id,
        "genre" => $validated["genre"],
        "description" => $validated["description"],
        "status" => \App\MovieStatus::IN_PROGRESS,
        "title" => $validated["title"]
    ]);

    \App\Jobs\GenerateMovie::dispatch($movie);

    return redirect()->route("dashboard");
};

?>

<x-layouts.app>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Create new movie') }}
        </h2>

    </x-slot>


    @volt('projects.create')
    <div class="flex flex-col flex-1 items-stretch h-100">
        <div class="flex flex-col items-stretch flex-1 pb-5 mx-auto h-100 min-h-[500px] w-full">
            <div class="relative flex-1 w-full">
                <div
                    class="flex justify-between items-center w-full h-100 bg-pink- overflow-hidden border border-dashed bg-gradient-to-br from-white to-zinc-50 rounded-lg border-zinc-200 dark:border-gray-700 dark:from-gray-950 dark:via-gray-900 dark:to-gray-800 max-h-[500px]">
                    <form wire:submit="generate" class="flex relative flex-col p-10 w-full">
                        <x-ui.input wire:model="title" id="title" label="Title" />
                        <div class="mt-3"></div>
                        <x-ui.textarea wire:model="description"
                                       placeholder="Movie about a developer, who joined Dev.to and Pinata hackathon"
                                       id="description" name="description" class="w-full"
                                       label="Movie description"></x-ui.textarea>
                        @error('form.description') <span class="error">{{ $message }}</span> @enderror

                        <div class="mt-3"></div>
                        <x-ui.select wire:model="genre" id="genre" name="genre" label="Genre"
                                     :options="$genreOptions"></x-ui.select>
                        @error('form.genre') <span class="error">{{ $message }}</span> @enderror


                        <div class="mt-3">
                            <x-ui.button rounded="md" submit>Generate</x-ui.button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.app>
