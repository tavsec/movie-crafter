<?php

use function Laravel\Folio\{name};

name('genesis.about');

?>

<x-layouts.marketing>

    <div class="w-full">

        <x-ui.marketing.breadcrumbs :crumbs="[ ['text' => 'About'] ]" />

        <div class="flex items-center justify-center w-full pt-24">
            <div class="w-full max-w-xl lg:shrink-0 xl:max-w-2xl">
                <h1 class="text-center text-4xl font-bold tracking-tight text-slate-900 dark:text-slate-100 sm:text-6xl">Built by <a class="text-blue-600" href="https://www.timotejavsec.com" target="_blank">Timotej Avsec</a> for <a class="text-blue-600" href="https://dev.to/devteam/join-us-for-the-the-pinata-challenge-3000-in-prizes-59cb" target="_blank">DEV.to & Pinata hackathon</a></h1>
                <p class="relative mt-6 text-lg leading-8 text-slate-600 dark:text-slate-400 sm:max-w-md lg:max-w-none">Developed using: <strong>Laravel</strong> (TALL stack), <strong>Pinata</strong> and <strong>OpenAI</strong> (gpt-40 and DALL-E 3)</p
            </div>
        </div>

    </div>
</x-layouts.marketing>
