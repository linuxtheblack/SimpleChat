<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex-grow">
                {{ __('My Chats') }}
            </h2>
        </div>
    </x-slot>
    <div id="chat" x-data="chat()" x-init="init()">
        <template x-if="isTyping">
            <div class="absolute bottom-3 left-12">
                <style>
                    .person-typing ~ .person-typing:before{
                        content: ', ';
                        margin-left: -0.25rem;
                    }
                </style>
                <p class="flex-none text-sm text-gray-500">
                    <template x-for="typist in peersTyping">
                        <span class="person-typing" x-text="typist.name"></span>
                    </template>
                    <span> is typing</span>
                </p>
            </div>
        </template>


        <!-- This example requires Tailwind CSS v2.0+ -->
        <div class="flex h-screen antialiased text-gray-800">
            <div class="flex flex-row h-full w-full overflow-x-hidden pt-32">
                <div class="flex flex-col flex-auto h-full">
                    <div class="flex flex-col flex-auto flex-shrink-0 rounded-2xl bg-gray-100 h-full">
                        <div id="chatlog" class="flex flex-col h-full overflow-x-auto">
                            <div class="flex flex-col h-full">
                                <div class="grid grid-cols-12 gap-y-2">
                                    <template x-for="(msg, index) in log" :key="index">
                                        <div class="col-start-1 col-end-13 p-3 rounded-lg">
                                            <div class="text-xs text-gray-600"
                                                 :class="{ 'text-right': msg.sender == user.name }"
                                                 x-text="msg.sender"></div>
                                            <div class="flex flex-row items-center"
                                                 :class="{ 'flex-row-reverse': msg.sender == user.name }">
                                                <div class="relative ml-3 text-sm bg-white py-2 px-4 shadow rounded-xl"
                                                     :class="{ 'bg-indigo-100': msg.sender == user.name }">
                                                    <div x-text="msg.msg"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row items-center h-20 rounded-xl bg-white shadow px-4 py-8 m-8 mt-0">
                            <div class="flex-grow ml-4">
                                <div class="relative w-full">
                                    <form x-on:submit.prevent="send()">
                                        <input type="text" x-model="input" @keydown="pingPeersTyping()"
                                               class="flex w-full border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10"/>
                                    </form>
                                </div>
                            </div>
                            <div class="ml-4">
                                <button
                                    class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 rounded-xl text-white px-4 py-1 flex-shrink-0"
                                    @click="send()">
                                    <span>Send</span>
                                    <span class="ml-2">
                                  <svg
                                      class="w-4 h-4 transform rotate-45 -mt-px"
                                      fill="none"
                                      stroke="currentColor"
                                      viewBox="0 0 24 24"
                                      xmlns="http://www.w3.org/2000/svg"
                                  >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                    ></path>
                                  </svg>
                                </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function chat() {
            return {
                input: '',
                isTyping: false,
                peersTyping: [],
                channel: '',
                user: {
                    id: {{ $user->id }},
                    name: '{{ $user->name }}',
                },
                log: {!! $messages !!},
                send() {
                    if (this.input !== '') {
                        axios.post('/chats/{{ $id }}', {msg: this.input})

                        this.input = '';
                    }
                },
                init() {
                    this.channel = window.Echo.private('.chat.{{ $id }}');
                    this.channel
                        .listen('UserMessageSent', e => this.pushMsg({
                            sender: e.sender,
                            msg: e.msg
                        }))
                        .listenForWhisper('typing', (e) => {
                            if(this.peersTyping.some(el => el.name === e.name)){
                                let peer = this.peersTyping.find(peer => peer.name === e.name);
                                clearTimeout(peer.callback);
                                peer.callback = this.removePeerTyping(e.name);
                            }
                            if (!this.peersTyping.some(el => el.name === e.name)){
                                this.peersTyping.push({
                                    name: e.name,
                                    callback: this.removePeerTyping(e.name)
                                });
                            }
                            this.isTyping = true;
                            //TODO: Remove people after 2000ms

                        });

                    this.chatScrollToBottom();
                },
                pushMsg(msg) {
                    this.log.push(msg);
                    this.chatScrollToBottom();
                },
                chatScrollToBottom() {
                    let chatlog = document.getElementById('chatlog');

                    setTimeout(() => chatlog.scrollTop = chatlog.scrollHeight, 100);
                },
                pingPeersTyping() {
                    this.channel.whisper('typing', {
                        name: this.user.name,
                    });
                },
                removePeerTyping(name){
                    return setTimeout(()=>{
                        this.peersTyping = this.peersTyping.filter((e) => e.name !== name);

                        if (this.peersTyping.length < 1){
                            this.isTyping = false
                        }
                    }, 2000);
                }
            }
        }
    </script>
</x-app-layout>
