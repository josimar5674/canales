<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CANALES</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
          rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles



</head>

<script>

document.addEventListener("DOMContentLoaded", () => {

    if ("Notification" in window) {

        if (Notification.permission === "default") {

            Notification.requestPermission();

        }

    }

});

</script>

<body


    x-data="{ sidebarOpen: false }"
    class="font-sans antialiased bg-gray-100 dark:bg-gray-900"
>
<div class="flex h-screen overflow-hidden">

<aside



    class="fixed md:relative z-50 w-72 bg-gray-900 text-white flex flex-col h-full transition-transform duration-300"

    :class="sidebarOpen

        ? 'translate-x-0'

        : '-translate-x-full md:translate-x-0'"

>
<div class="p-4 border-b border-gray-800 flex items-center gap-20">

  

           <img
               src="{{ auth()->user()->photo
    ? asset('storage/'.auth()->user()->photo)
    : 'https://ui-avatars.com/api/?name='.auth()->user()->name }}"
                class="w-12 h-12 rounded-full"
            >

            <div>

                <p class="font-semibold">
                    {{ auth()->user()->name }}
                </p>

                <p class="text-xs text-gray-400">
                    En línea
                </p>

            </div>

         

     <div class="flex-1 flex justify-end">       
    <button
        @click="sidebarOpen = false"
        class="text-2xl"
    >
        ✖
    </button>

</div>
</div>

  

     


        <!-- CHANNELS -->
        <div class="flex-1 overflow-y-auto p-3">

            <p class="text-gray-400 text-xs uppercase mb-3 px-2">
                Canales
            </p>

            <div class="mb-3">

    <input
        type="text"
        id="searchChannels"
        placeholder="🔍 Buscar canal..."
        class="w-full rounded-lg bg-gray-800 border border-gray-700
               text-white text-sm px-3 py-2
               placeholder-gray-400
               focus:outline-none focus:border-blue-500">

</div>

<div class="mb-4">

    <select
        id="sortChannels"
        class="w-full rounded-lg bg-gray-800 border border-gray-700
               text-white text-sm px-3 py-2">

        <option value="updated_desc">
            Más recientes
        </option>

        <option value="updated_asc">
            Más antiguos
        </option>

        <option value="az">
            A → Z
        </option>

        <option value="za">
            Z → A
        </option>

    </select>

</div>

<nav id="channelsList" class="space-y-1">
    
@foreach($channels ?? [] as $item)

@if(auth()->user()->role === 'admin' || $item->active)

<a
    href="{{ route('dashboard', $item->id) }}"
    class="channel-item flex items-center gap-2 px-3 py-2 rounded-lg transition
    {{ isset($channel) && $channel->id == $item->id
        ? 'bg-gray-800'
        : 'hover:bg-gray-700' }}"
    data-name="{{ strtolower($item->name) }}"
    data-updated="{{ $item->updated_at->timestamp }}"
>

   

    <span>
        {{ $item->name }}

        @if(auth()->user()->role === 'admin' && !$item->active)

            <span class="text-red-400 text-xs">
                (Inactivo)
            </span>

        @endif

    </span>

</a>

@endif

@endforeach
            </nav>
            
            @if(auth()->user()->role === 'admin')


    <div class="mt-6 space-y-3">

        <a

            href="{{ route('channels.index') }}"

            class="block w-full text-center bg-blue-600 hover:bg-blue-700 py-2 rounded-xl transition"

        >

            Administrar Canales

        </a>

        <a

            href="{{ route('users.index') }}"

            class="block w-full text-center bg-gray-700 hover:bg-gray-600 py-2 rounded-xl transition"

        >

            Administrar Usuarios

        </a>

    </div>

@endif
        </div>

        <!-- FOOTER -->
        <div class="p-4 border-t border-gray-800">

            <form method="POST" action="{{ route('logout') }}">

                @csrf

                <button
                    class="w-full bg-red-500 hover:bg-red-600 py-2 rounded-lg transition"
                >
                    Cerrar sesión
                </button>

            </form>

        </div>

    </aside>

<div
    x-show="sidebarOpen"
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-black/50 z-40 md:hidden"
></div>

    <!-- MAIN -->
    <main class="flex-1 flex flex-col">

        <!-- HEADER -->
<header class="h-16 bg-white dark:bg-gray-800 shadow flex items-center justify-between px-3 md:px-6">
      <div class="flex items-center gap-4">

    <button
        @click="sidebarOpen = true"
        class="md:hidden text-2xl text-gray-700 dark:text-white"
    >
        ☰
    </button>

    <h2 class="text-xl font-bold text-gray-800 dark:text-white">
        # {{ $channel->name ?? 'Canal' }}
    </h2>

</div>

            <div class="flex items-center gap-4">

                <button class="text-gray-600 dark:text-gray-300">
                    🔔
                </button>

                <button class="text-gray-600 dark:text-gray-300">
                    ⚙️
                </button>

            </div>

        </header>

        <!-- CONTENT -->
<section class="flex-1 overflow-y-auto px-0 py-2 md:p-6 space-y-6">
            @yield('content')

        </section>

   
    </main>

</div>
</div>

@livewireScripts

<script>

document.addEventListener('DOMContentLoaded', () => {

    const search = document.getElementById('searchChannels');
    const sort = document.getElementById('sortChannels');
    const list = document.getElementById('channelsList');

    function applyFilters() {

        let items = Array.from(list.querySelectorAll('.channel-item'));

        // BUSQUEDA
        const text = search.value.toLowerCase();

        items.forEach(item => {

            item.style.display =
                item.dataset.name.includes(text)
                    ? 'flex'
                    : 'none';

        });

        // SOLO LOS VISIBLES
        let visibles = items.filter(i => i.style.display !== 'none');

        switch(sort.value){

            case 'az':

                visibles.sort((a,b)=>
                    a.dataset.name.localeCompare(b.dataset.name)
                );

            break;

            case 'za':

                visibles.sort((a,b)=>
                    b.dataset.name.localeCompare(a.dataset.name)
                );

            break;

            case 'updated_asc':

                visibles.sort((a,b)=>
                    Number(a.dataset.updated)-Number(b.dataset.updated)
                );

            break;

            default:

                visibles.sort((a,b)=>
                    Number(b.dataset.updated)-Number(a.dataset.updated)
                );

        }

        visibles.forEach(item => list.appendChild(item));

    }

    search.addEventListener('input', applyFilters);

    sort.addEventListener('change', applyFilters);

});

</script>

</body>
</html>