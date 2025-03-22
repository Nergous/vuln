@extends('layouts.app')

@section('content')
<!-- Подключаем Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="min-h-screen bg-gray-100 p-6">
    <!-- Шапка -->
    <div class="sticky top-0 z-10 bg-white shadow-lg rounded-lg mb-6">
        <div class="flex justify-between items-center p-4">
            <div class="flex space-x-4">
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-300 text-gray-600 rounded-lg hover:bg-gray-400 transition duration-300">Назад</a>
            </div>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Редактирование пользователя</h1>

        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <!-- Логин -->
            <div class="mb-6">
                <label for="login" class="block text-gray-600 mb-2">Логин:</label>
                <input id="login" type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('login') border-red-500 @enderror"
                    name="login" value="{{ $user->login }}" required autocomplete="login" autofocus>
                @error('login')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Пароль -->
            <div class="mb-6">
                <label for="password" class="block text-gray-600 mb-2">Пароль:</label>
                <input id="password" type="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                    name="password" autocomplete="new-password">
                @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Тип пользователя -->
            <div class="mb-6">
                <label for="type" class="block text-gray-600 mb-2">Тип:</label>
                <select name="type" id="type" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Admin" @if($user->type == 'Admin') selected @endif>Администратор</option>
                    <option value="Operator" @if($user->type == 'Operator') selected @endif>Оператор</option>
                    <option value="Viewer" @if($user->type == 'Viewer') selected @endif>Смотрящий😎</option>
                </select>
            </div>

            <!-- Кнопки -->
            <div class="flex space-x-4">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Сохранить</button>
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-300 text-gray-600 rounded-lg hover:bg-gray-400 transition duration-300">Назад</a>
            </div>
        </form>
    </div>
</div>
@endsection