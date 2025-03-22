@extends('layouts.app')

@section('content')
<!-- Подключаем Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="min-h-screen bg-gray-100 p-6">
    <!-- Основной контент -->
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-gray-800 text-center">Вход</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Логин -->
            <div class="mb-6">
                <label for="login" class="block text-gray-600 mb-2">Логин:</label>
                <input id="login" type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('login') border-red-500 @enderror"
                    name="login" value="{{ old('login') }}" required autofocus>
                @error('login')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Пароль -->
            <div class="mb-6">
                <label for="password" class="block text-gray-600 mb-2">Пароль:</label>
                <input id="password" type="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                    name="password" required>
                @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Кнопка входа -->
            <div class="flex justify-center">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                    Войти
                </button>
            </div>
        </form>
    </div>
</div>
@endsection