<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Chats') }}
        </h2>
    </x-slot>
    <div id="chat" x-data="chat()" x-init="init()">
        <!-- This example requires Tailwind CSS v2.0+ -->
        <div class="flex h-screen antialiased text-gray-800">
            <div class="flex flex-row h-full w-full overflow-x-hidden pt-32">
                <div class="flex flex-col flex-auto h-full">
                    <div class="flex flex-col flex-auto flex-shrink-0 rounded-2xl bg-gray-100 h-full">
                        <div id="chatlog" class="flex flex-col h-full overflow-x-auto">
                            <div class="flex flex-col h-full">
                                <div class="grid grid-cols-12 gap-y-2">
                                    <!--                                TODO: if message from same user don't type name again.-->
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
                        <div class="flex flex-row items-center h-16 rounded-xl bg-white px-4 py-8 m-8 mt-0">
                            <div class="flex-grow ml-4">
                                <div class="relative w-full">
                                    <form x-on:submit.prevent="send()">
                                        <input type="text" x-model="input"
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
                user: {
                    id: {{ $user->id }},
                    name: '{{ $user->name }}',
                    nameInput: '{{ $user->name }}'
                },
                log: [],
                send() {
                    if (this.input !== '') {
                        axios.post('/chat/{{ $id }}', {msg: this.input})

                        this.input = '';
                    }
                },
                init() {
                    window.Echo.channel('.public.chat').listen('UserMessageSent', e => this.pushMsg({
                        sender: e.sender,
                        msg: e.msg
                    }));
                },
                pushMsg(msg) {
                    let chatlog = document.getElementById('chatlog');

                    this.log.push(msg);
                    setTimeout(() => chatlog.scrollTop = chatlog.scrollHeight, 100)
                    ;
                }
            }
        }
    </script>
</x-app-layout>
