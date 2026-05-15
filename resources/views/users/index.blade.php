@extends('layouts.app')

@section('content')

<div class="p-6">

    <div class="flex justify-between items-center mb-6">

        <h1 class="text-2xl font-bold">
            Usuarios
        </h1>

        <a
            href="{{ route('users.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-xl"
        >
            Nuevo Usuario
        </a>

    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-gray-100 dark:bg-gray-700">

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

                        <td class="p-4">

                            <img
                                src="{{ $user->photo
                                    ? asset('storage/'.$user->photo)
                                    : 'https://ui-avatars.com/api/?name='.$user->name }}"
                                class="w-12 h-12 rounded-full"
                            >

                        </td>

                        <td class="p-4">
                            {{ $user->name }}
                        </td>

                        <td class="p-4">
                            {{ $user->username }}
                        </td>

                        <td class="p-4">
                            {{ $user->role }}
                        </td>

                        <td class="p-4">

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

                        <td class="p-4">

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

</div>

@endsection