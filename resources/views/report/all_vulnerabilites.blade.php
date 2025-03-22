@extends('layouts.app')

@section('content')
<!-- Подключаем Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="min-h-screen bg-gray-100 p-6">
    <!-- Шапка -->
    <div class="sticky top-0 z-10 bg-white shadow-lg rounded-lg mb-6">
        <div class="flex justify-between items-center p-4">
            <h1 class="text-2xl font-bold text-gray-800">Все уязвимости / задачи - {{ $document->name }}</h1>
            <a href="{{ route('home') }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-300">На главную</a>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Таблица -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Код</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Программа</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус выполнения</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($document->vulnerabilities as $vulnerability)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vulnerability->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vulnerability->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vulnerability->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vulnerability->software }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vulnerability->status->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($vulnerability->complete_status === "In work")
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">В работе</span>
                            @else
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">Выполнено</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('report.change_vulnerability', $vulnerability->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Добавить решение</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Кнопка "Назад" -->
        <div class="mt-6">
            <a href="{{ route('home') }}" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-300">Назад</a>
        </div>
    </div>
</div>
@endsection