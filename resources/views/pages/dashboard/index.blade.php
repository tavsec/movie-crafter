<?php

use App\MovieStatus;
use Illuminate\Support\Str;
use function Livewire\Volt\state;
use function Laravel\Folio\{middleware, name};

name('dashboard');
middleware(['auth', 'verified']);

state([
    "movies" => fn() => auth()->user()->movies()->orderByDesc("id")->get()
])

?>

<x-layouts.app>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Projects') }}
        </h2>

    </x-slot>

    <div class="mb-3">
        <x-ui.button tag="a" size="md" href="{{ route('projects.create') }}" class="ml-auto mb-3">
            {{ __('Generate New Movie') }}
        </x-ui.button>
    </div>
    @volt('dashboard')
    <div class="flex flex-col flex-1 items-stretch h-100">
        <div class="flex flex-col items-stretch flex-1 pb-5 mx-auto h-100 min-h-[500px] w-full">
            <div class="relative flex-1 w-full h-100">
                <div
                    class="flex justify-between items-center w-full h-100 bg-pink- border border-dashed bg-gradient-to-br from-white to-zinc-50 rounded-lg border-zinc-200 dark:border-gray-700 dark:from-gray-950 dark:via-gray-900 dark:to-gray-800">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-3 w-full">
                        @foreach($movies as $movie)
                            <div class="relative w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">

                                <!-- Background Image with Dimmed Overlay -->
                                @if($movie->storyBoards()->first())
                                    <div
                                        class="absolute inset-0 bg-cover bg-center before:absolute before:inset-0 before:bg-white/80 before:dark:bg-black/80"
                                        style="background-image: url('{{ $movie->storyBoards()->first()?->getSignedUrl() }}');">
                                    </div>
                                @endif

                                <!-- Content (kept above background) -->
                                <div class="relative z-10 p-4 h-full flex flex-col justify-between text-white">
                                    <!-- Movie Title -->
                                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $movie->title }}</h2>

                                    <!-- Movie Genre -->
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        Genre: <span class="font-medium">{{ $movie->genre }}</span>
                                    </p>

                                    <!-- Movie Description -->
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                        {{ $movie->answers()->first()?->short_description }}
                                    </p>

                                    <!-- Processing Status -->
                                    <div class="mt-4">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Status:</span>
                                        <span @class([
                                "px-2", "py-1", "rounded-lg", "text-white",
                                "bg-yellow-300" => $movie->status == MovieStatus::IN_PROGRESS,
                                "bg-green-300" => $movie->status == MovieStatus::SUCCESSFUL,
                                "bg-red-300" => $movie->status == MovieStatus::FAILED,
                            ])>
                                {{ Str::title(Str::replace("_", " ", $movie->status->value)) }}
                            </span>
                                    </div>

                                    <!-- Creation Date -->
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        Created: <span>{{ $movie->created_at->diffForHumans() }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>  </div>
    </div>
    @endvolt
</x-layouts.app>
