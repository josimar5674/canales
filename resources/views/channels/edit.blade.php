@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">
            Editar Canal
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
            action="{{ route('channels.update', $channel) }}"
            class="space-y-5"
        >

            @csrf
            @method('PUT')

            <!-- NOMBRE -->
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nombre Canal
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ $channel->name }}"
                    required
                    class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                >

            </div>

            <!-- ACTIVO -->
            <div class="flex items-center gap-3">

                <input
                    type="checkbox"
                    name="active"
                    {{ $channel->active ? 'checked' : '' }}
                    class="rounded border-gray-300"
                >

                <label class="text-gray-700 dark:text-gray-300">
                    Canal activo
                </label>

            </div>

            <!-- BOTONES -->
            <div class="flex justify-end gap-3 pt-4">

                <a
                    href="{{ route('channels.index') }}"
                    class="px-5 py-3 rounded-xl bg-gray-300 hover:bg-gray-400 transition"
                >
                    Cancelar
                </a>

                <button
                    class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition"
                >
                    Actualizar Canal
                </button>

            </div>

        </form>

    </div>

</div>

@endsection