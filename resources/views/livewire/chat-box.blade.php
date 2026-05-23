@php

use Illuminate\Support\Str;

@endphp


<div

    id="chat-box-component"

    class="flex flex-col h-full"
    x-data="{ showDate: false, minimized: false }"
    x-on:message-received.window="$wire.$refresh()">

    <!-- MENSAJES -->
<div
    wire:key="chat-{{ $refreshKey }}"
    id="chat-container"
class="flex-1 overflow-y-auto overflow-x-hidden
space-y-4 px-1 py-2 md:p-6">

        @foreach($messages as $message)

        <div wire:key="message-{{ $message->id }}">

            <div class="flex gap-3 items-start">

                <!-- AVATAR -->
                <img
                    src="{{ $message->user->photo
        ? asset('storage/'.$message->user->photo)
        : 'https://ui-avatars.com/api/?name='.$message->user->name }}"
                    class="w-12 h-12 rounded-full">

                <!-- MENSAJE -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow w-full max-w-2xl">
                    <!-- HEADER -->
                    <div class="flex items-center gap-3 mb-2">

                        <h3 class="font-bold text-gray-800 dark:text-white">
                            {{ $message->user->name }}
                        </h3>

                        <span class="text-xs text-gray-400">
                            {{ $message->created_at->format('d/m/Y h:i A') }}
                        </span>

                    </div>

                    <!-- TEXTO -->
                    @if($message->content)

                    <p class="text-gray-700 dark:text-gray-300">
                        {{ $message->content }}
                    </p>

                    @endif

                    <!-- FECHA REFERENCIA -->
                    @if($message->reference_date)

                    <div class="mt-3 text-xs text-blue-500">

                        📅 Fecha referencia:
                        {{ \Carbon\Carbon::parse($message->reference_date)->format('d/m/Y h:i A') }}

                    </div>

                    @endif

                    <!-- ADJUNTOS -->
                    <!-- ADJUNTOS -->
                    @if($message->attachments->count())

                    <div class="mt-4 space-y-3">

                        @foreach($message->attachments as $attachment)

                        @php

                        $type = $attachment->file_type;

                        @endphp

                        <!-- IMAGEN -->
                        @if(Str::startsWith($type, 'image/'))

                        <a
                            href="{{ asset('storage/' . $attachment->file_path) }}"
                            target="_blank">

                            <img
                                src="{{ asset('storage/' . $attachment->file_path) }}"
                                class="rounded-xl border dark:border-gray-700 hover:opacity-90 transition max-w-full md:max-w-xs max-h-64 object-cover">

                        </a>

                        <!-- VIDEO -->
                        @elseif(Str::startsWith($type, 'video/'))

                        <video
                            controls
                            class="rounded-xl max-w-sm">

                            <source
                                src="{{ asset('storage/' . $attachment->file_path) }}"
                                type="{{ $type }}">

                        </video>

                        <!-- PDF -->
                        @elseif($type === 'application/pdf')

                        <iframe
                            src="{{ asset('storage/' . $attachment->file_path) }}"
                            class="w-full h-96 rounded-xl border dark:border-gray-700"></iframe>

                        <!-- OTROS -->
                        @else

                        <a
                            href="{{ asset('storage/' . $attachment->file_path) }}"
                            target="_blank"
                            class="block bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded-lg text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition">

                            📎 {{ $attachment->file_name }}

                        </a>

                        @endif

                        @endforeach

                    </div>

                    @endif

                </div>

            </div>

        </div>

        @endforeach

    </div>

    <!-- INPUT -->
<div
    class="bg-white dark:bg-gray-800 border-t dark:border-gray-700
           px-0 py-2 md:px-4 md:py-4"
>

    <!-- BOTON SIEMPRE VISIBLE -->
    <div class="flex justify-between items-center mb-2 px-2">

        <button
            type="button"
            @click="minimized = !minimized"
            class="text-gray-400 text-sm">

            <span x-show="!minimized">🔽 Minimizar</span>

            <span x-show="minimized">🔼 Expandir</span>

        </button>

    </div>

    <!-- FORM OCULTABLE -->
    <div x-show="!minimized" x-transition>

        <form
            wire:submit.prevent="sendMessage"
            class="space-y-3 px-1 md:px-0">

            <!-- TODO TU FORM -->

        </form>

    </div>

</div>
<form
    wire:submit.prevent="sendMessage"
    class="space-y-3">

            <!-- MENSAJE -->
            <input
                type="text"
                wire:model.live="content"
                placeholder="Escribe un mensaje..."
                class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">

            <!-- FECHA REFERENCIA -->
            <div class="flex items-center gap-2">

                <!-- BOTON FECHA -->
                <button
                    type="button"
                    @click="showDate = !showDate"
                    class="bg-gray-700 text-white px-3 py-2 rounded-xl">
                    📅
                </button>

                <!-- DATEPICKER -->
                <div
                    x-show="showDate"
                    x-transition
                    wire:ignore
                    class="flex-1">

                    <input
                        type="text"
                        id="reference_date"
                        placeholder="Seleccionar fecha referencia"
                        class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">

                </div>

            </div>

            <!-- FOOTER INPUT -->
            <div class="flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
                <!-- FILE -->
                <input
                    type="file"
                    wire:model="file"
                    accept="image/*,video/*,.pdf"
                    class="text-sm text-gray-500 dark:text-gray-300">

                <!-- LOADING -->
                <div
                    wire:loading
                    wire:target="file"
                    class="text-sm text-blue-500">

                    Subiendo archivo...

                </div>

                <!-- BOTON -->
                <button

                    type="submit"

                    wire:loading.attr="disabled"

                    wire:target="file,sendMessage"

                    class="bg-blue-600 hover:bg-blue-700
           disabled:opacity-50
           disabled:cursor-not-allowed
           text-white px-5 py-3 rounded-xl">

                    <span wire:loading.remove wire:target="file,sendMessage">

                        Enviar

                    </span>

                    <span wire:loading wire:target="file">

                        Subiendo archivo...

                    </span>

                    <span wire:loading wire:target="sendMessage">

                        Enviando...

                    </span>

                </button>

            </div>

        </form>

    </div>

</div>

<script>
    function initDatePicker() {

        flatpickr("#reference_date", {
            appendTo: document.body,

            position: "top",
            enableTime: true,
            disableMobile: true,
            noCalendar: false,

            time_24hr: true,

            enableSeconds: false,

            minuteIncrement: 1,

            dateFormat: "Y-m-d H:i:S",

            onClose: function(selectedDates, dateStr) {
                Livewire.find(
                    document.querySelector('[wire\\:id]').getAttribute('wire:id')
                ).set('reference_date', dateStr);

            }

        });

    }

    document.addEventListener('livewire:init', () => {

        initDatePicker();

        Livewire.hook('morph.updated', () => {

            initDatePicker();

        });

    });
</script>

<script>
    function scrollToBottom() {

        let container = document.getElementById('chat-container');

        if (container) {

            container.scrollTop = container.scrollHeight;

        }

    }

    document.addEventListener('livewire:init', () => {

        scrollToBottom();

        Livewire.hook('morph.updated', () => {

            scrollToBottom();

        });

    });
</script>

<script>
    document.addEventListener('livewire:init', () => {

        let channelId = @json($channel -> id);

        window.Echo.channel('chat.' + channelId)
            .listen('.MessageSent', (e) => {

                console.log('Realtime recibido');

                Livewire.dispatch('refresh-chat');

            });

    });
document.addEventListener('clear-date', () => {

    let input = document.querySelector('#reference_date');

    if (input) {

        input._flatpickr.clear();

    }

});
    
</script>