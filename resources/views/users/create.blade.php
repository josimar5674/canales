@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">
            Crear Usuario
        </h1>



    @if ($errors->any())

        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-4">

            <ul class="list-disc pl-5">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

 
        <form
            method="POST"
            action="{{ route('users.store') }}"
            enctype="multipart/form-data"
            class="space-y-5"
        >

            @csrf

            <!-- FOTO -->
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Foto perfil
                </label>

                <input
                    type="file"
                    name="photo"
                    class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                >

            </div>

            <!-- NOMBRE -->
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nombre
                </label>

                <input
                    type="text"
                    name="name"
                    required
                    class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                >

            </div>

            <!-- USERNAME -->
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    required
                    class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                >

            </div>

            <!-- EMAIL -->
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    required
                    class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                >

            </div>

            <!-- PASSWORD -->
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Contraseña
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                >

            </div>

            <!-- ROL -->
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Rol
                </label>

                

                <select
                    name="role"
                    class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                >

                    <option value="user">
                        Usuario
                    </option>

                    <option value="admin">
                        Administrador
                    </option>

                </select>

            </div>

            <!-- CANALES -->
<div>

    <label class="block mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
        Canales permitidos
    </label>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        @foreach($channels as $channel)

            <label class="flex items-center gap-3 bg-gray-100 dark:bg-gray-700 p-3 rounded-xl">

                <input
                    type="checkbox"
                    name="channels[]"
                    value="{{ $channel->id }}"
                    class="rounded border-gray-300"
                >

                <span class="text-gray-800 dark:text-white">
                    # {{ $channel->name }}
                </span>

            </label>

        @endforeach

    </div>

</div>

            <!-- ACTIVO -->
            <div class="flex items-center gap-3">

                <input
                    type="checkbox"
                    name="active"
                    checked
                    class="rounded border-gray-300"
                >

                <label class="text-gray-700 dark:text-gray-300">
                    Usuario activo
                </label>

            </div>

            <!-- BOTONES -->
            <div class="flex justify-end gap-3 pt-4">

                <a
                    href="{{ route('users.index') }}"
                    class="px-5 py-3 rounded-xl bg-gray-300 hover:bg-gray-400 transition"
                >
                    Cancelar
                </a>

                <button
                    class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition"
                >
                    Guardar Usuario
                </button>

            </div>

        </form>

    </div>

</div>

@endsection