<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Chats') }}
        </h2>
    </x-slot>

    <div class="h-screen py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full pt-32">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @foreach($chats as $chat)
                    <div class="p-6 bg-white border-b border-gray-200 flex">
                        <div class="w-1/2">
                            <h3 class="font-bold text-xl">
                                <a class="text-red-400" href="/chats/{{ $chat->uuid }}">{{ $chat->name }}</a>
                            </h3>
                        </div>
                        <div class="w-1/2 text-right">
                            <p class="list-none text-gray-400">
                                {{ $chat->users->pluck('name')->implode(', ') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
