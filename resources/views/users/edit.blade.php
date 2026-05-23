@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">
            Editar Usuario
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
            action="{{ route('users.update', $user) }}"
            enctype="multipart/form-data"
            class="space-y-5"
        >

            @csrf
            @method('PUT')

            <!-- FOTO -->
            <div class="flex items-center gap-4">

                <img
                    src="{{ $user->photo
                        ? asset('storage/'.$user->photo)
                        : 'https://ui-avatars.com/api/?name='.$user->name }}"
                    class="w-20 h-20 rounded-full"
                >

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
                    value="{{ $user->name }}"
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
                    value="{{ $user->username }}"
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
                    value="{{ $user->email }}"
                    required
                    class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                >

            </div>

            <!-- PASSWORD -->
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nueva contraseña
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Opcional"
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

                    <option
                        value="user"
                        {{ $user->role == 'user' ? 'selected' : '' }}
                    >
                        Usuario
                    </option>

                    <option
                        value="admin"
                        {{ $user->role == 'admin' ? 'selected' : '' }}
                    >
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
                    @checked($user->channels->contains($channel->id))
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
                    {{ $user->active ? 'checked' : '' }}
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
                    Actualizar Usuario
                </button>

            </div>

        </form>

    </div>

</div>

@endsection