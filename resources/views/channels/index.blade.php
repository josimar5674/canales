@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="flex justify-between items-center mb-6">

        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Canales
        </h1>

        <a
            href="{{ route('channels.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl"
        >
            Nuevo Canal
        </a>

    </div>

    <!-- DESKTOP -->
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">

                <tr>

                    <th class="p-4 text-left">
                        Nombre
                    </th>

                    <th class="p-4 text-left">
                        Estado
                    </th>

                    <th class="p-4 text-left">
                        Acciones
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($channels as $channel)

                <tr class="border-t dark:border-gray-700">

                    <td class="p-4 text-gray-800 dark:text-gray-200">
                        # {{ $channel->name }}
                    </td>

                    <td class="p-4 text-gray-800 dark:text-gray-200">

                        @if($channel->active)

                            <span class="text-green-500">
                                Activo
                            </span>

                        @else

                            <span class="text-red-500">
                                Inactivo
                            </span>

                        @endif

                    </td>

                    <td class="p-4 flex gap-3 text-gray-800 dark:text-gray-200">

                        <a
                            href="{{ route('channels.edit', $channel) }}"
                            class="text-blue-500"
                        >
                            Editar
                        </a>

                        <form
                            method="POST"
                            action="{{ route('channels.destroy', $channel) }}"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                onclick="return confirm('Eliminar canal?')"
                                class="text-red-500"
                            >
                                Eliminar
                            </button>

                        </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <!-- MOBILE -->
    <div class="md:hidden space-y-4 mt-4">

        @foreach($channels as $channel)

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4">

                <!-- TOP -->
                <div class="flex justify-between items-center">

                    <h2 class="font-bold text-lg text-gray-800 dark:text-white">
                        # {{ $channel->name }}
                    </h2>

                    @if($channel->active)

                        <span class="text-green-500 text-sm font-medium">
                            Activo
                        </span>

                    @else

                        <span class="text-red-500 text-sm font-medium">
                            Inactivo
                        </span>

                    @endif

                </div>

                <!-- ACTIONS -->
                <div class="mt-4 flex gap-3">

                    <a
                        href="{{ route('channels.edit', $channel) }}"
                        class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-xl transition"
                    >
                        Editar
                    </a>

                    <form
                        method="POST"
                        action="{{ route('channels.destroy', $channel) }}"
                        class="flex-1"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            onclick="return confirm('Eliminar canal?')"
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-xl transition"
                        >
                            Eliminar
                        </button>

                    </form>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection