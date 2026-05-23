@extends('layouts.app')

@section('content')

<div class="p-3 md:p-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

        <h1 class="text-2xl font-bold">
            Usuarios
        </h1>

        <a
            href="{{ route('users.create') }}"
            class="bg-blue-600 text-white px-4 py-3 rounded-xl text-center"
        >
            Nuevo Usuario
        </a>

    </div>

    <!-- DESKTOP TABLE -->
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">

                <tr>

                    <th class="p-4 text-left">Foto</th>
                    <th class="p-4 text-left">Nombre</th>
                    <th class="p-4 text-left">Username</th>
                    <th class="p-4 text-left">Rol</th>
                    <th class="p-4 text-left">Estado</th>
                    <th class="p-4 text-left">Acciones</th>

                </tr>

            </thead>

            <tbody>

                @foreach($users as $user)

                    <tr class="border-t dark:border-gray-700">

<td class="p-4 text-gray-800 dark:text-gray-200">
                            <img
                                src="{{ $user->photo
                                    ? asset('storage/'.$user->photo)
                                    : 'https://ui-avatars.com/api/?name='.$user->name }}"
                                class="w-12 h-12 rounded-full"
                            >

                        </td>

<td class="p-4 text-gray-800 dark:text-gray-200">                            {{ $user->name }}
                        </td>

<td class="p-4 text-gray-800 dark:text-gray-200">                            {{ $user->username }}
                        </td>

                        <td class="p-4 text-gray-800 dark:text-gray-200 ">
                            {{ $user->role }}
                        </td>

<td class="p-4 text-gray-800 dark:text-gray-200">
                            @if($user->active)

                                <span class="text-green-500">
                                    Activo
                                </span>

                            @else

                                <span class="text-red-500">
                                    Inactivo
                                </span>

                            @endif

                        </td>

<td class="p-4 text-gray-800 dark:text-gray-200">
                            <a
                                href="{{ route('users.edit', $user) }}"
                                class="text-blue-500"
                            >
                                Editar
                            </a>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <!-- MOBILE CARDS -->
    <div class="md:hidden space-y-4">

        @foreach($users as $user)

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4">

                <!-- TOP -->
                <div class="flex items-center gap-3">

                    <img
                        src="{{ $user->photo
                            ? asset('storage/'.$user->photo)
                            : 'https://ui-avatars.com/api/?name='.$user->name }}"
                        class="w-14 h-14 rounded-full"
                    >

                    <div>

                        <h2 class="font-bold text-lg text-gray-800 dark:text-white">
                            {{ $user->name }}
                        </h2>

                        <p class="text-sm text-gray-500">
                            @{{ $user->username }}
                        </p>

                    </div>

                </div>

                <!-- INFO -->
                <div class="mt-4 space-y-2 text-sm">

                    <div class="flex justify-between">

                        <span class="text-gray-500">
                            Rol
                        </span>

                        <span class="font-medium text-gray-800 dark:text-white">
                            {{ $user->role }}
                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-gray-500">
                            Estado
                        </span>

                        @if($user->active)

                            <span class="text-green-500 font-medium">
                                Activo
                            </span>

                        @else

                            <span class="text-red-500 font-medium">
                                Inactivo
                            </span>

                        @endif

                    </div>

                </div>

                <!-- ACTION -->
                <div class="mt-4">

                    <a
                        href="{{ route('users.edit', $user) }}"
                        class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-xl transition"
                    >
                        Editar
                    </a>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection