<?php

use Illuminate\Support\Facades\Auth;
use function Livewire\Volt\state;
use function Laravel\Folio\{middleware, name, render};

name('projects.show');
middleware(['auth', 'verified']);

render(function (\Illuminate\View\View $view, \App\Models\Movie $movie) {
    if (Auth::user()->id !== $movie->user_id) {
        return response('Unauthorized', 403);
    }

    if($movie->status !== \App\MovieStatus::SUCCESSFUL){

        return redirect('/dashboard');
    }

    return $view;
});

state([
    "movie" => fn() => $movie
])

?>
<div>
    <x-layouts.app>
        <x-slot name="header">
            <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ $movie->title }}
            </h2>

        </x-slot>
        @volt('projects.view')
        <div class="flex flex-col flex-1 items-stretch h-100">
            <div class="flex flex-col items-stretch flex-1 pb-5 mx-auto h-100 min-h-[500px] w-full">
                <div class="relative flex-1 w-full h-100">
                    <h1 class="text-2xl">Screenplay</h1>

                    <div
                        class="flex justify-between items-center w-full h-100 bg-pink- border border-dashed bg-gradient-to-br from-white to-zinc-50 rounded-lg border-zinc-200 dark:border-gray-700 dark:from-gray-950 dark:via-gray-900 dark:to-gray-800">

                        <div class="bg-white rounded-md p-3 max-h-[500px] overflow-scroll">
                            <x-markdown class="max-h-full markdown" style="overflow: scroll;  max-height: 450px">
                                {{ $movie->answers()->first()->scenario}}
                            </x-markdown>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <h1 class="text-2xl">Storyboards</h1>
                <div class="grid grid-cols-3 gap-4 p-4">
                    @foreach($movie->storyBoards()->orderBy("order")->get() as $storyboard)
                        @php $url = $storyboard->getSignedUrl() @endphp
                        <a href="{{$url}}" target="_blank" class="group relative cursor-pointer" >
                            <!-- Image -->
                            <img src="{{$url}}" alt="{{$storyboard->description}}" class="w-full h-full object-cover rounded-md transition-opacity duration-300 group-hover:opacity-30">

                            <!-- Text Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center text-center p-2">
                                <span class="text-grey-600 text-lg font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300">{{$storyboard->description}}</span>
                            </div>
                        </a>



                @endforeach
                </div>

            </div>
        </div>
        @endvolt
    </x-layouts.app>
</div>
