<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Chats') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @foreach(\Illuminate\Support\Facades\Auth::user()->chats as $chat)
                    <div class="p-6 bg-white border-b border-gray-200 flex">
                        <div class="w-1/2">
                            <h3 class="font-bold text-xl"><a class="text-red-400"
                                                             href="/chat/{{ $chat->uuid }}">{{ $chat->name }}</a></h3>
                        </div>
                        <div class="w-1/2 text-right">
                            <ul class="list-none">
                                @foreach($chat->users as $u)
                                    <li class="inline-block text-gray-400">
                                        @if($loop->last)
                                            {{$u->name}}
                                        @else
                                            {{$u->name}},
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
