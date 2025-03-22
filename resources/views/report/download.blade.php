@extends('layouts.app')

@section('content')
<!-- Подключаем Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="min-h-screen bg-gray-100 p-6">
    <!-- Шапка -->
    <div class="sticky top-0 z-10 bg-white shadow-lg rounded-lg mb-6">
        <div class="flex justify-between items-center p-4">
            <h1 class="text-2xl font-bold text-gray-800">Выгрузка отчета</h1>
            <a href="{{ route('home') }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-300">На главную</a>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl mx-auto">
        <!-- Форма -->
        <form action="{{ route('export.main') }}" method="GET">
            @csrf

            <!-- Начальная и конечная дата -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="start_date" class="block text-gray-600 mb-2">Начальная дата:</label>
                    <input type="date" name="start_date" id="start_date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="end_date" class="block text-gray-600 mb-2">Конечная дата:</label>
                    <input type="date" name="end_date" id="end_date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <!-- Выбор полей отчета -->
            <div class="mb-6">
                <label class="block text-gray-600 mb-2">Выберите поля отчета:</label>
                <div class="space-y-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="fields[]" value="delayed_reason" class="form-checkbox h-5 w-5 text-blue-600" checked>
                        <span class="ml-2 text-gray-700">Причина отложки</span>
                    </label>
                </div>
                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="fields[]" value="vulnerability_code" id="vulnerability_code" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Код уязвимости / задачи</span> <span class="ml-2 text-red-700">[Дополнительные поля]</span>
                    </label>
                    <div class="additional-fields ml-6" style="display: none;">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="fields[]" value="vulnerability_name" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">Название уязвимости / задачи</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="fields[]" value="real_solutions" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">Реальные решения</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="fields[]" value="compensating_solutions" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">Компенсирующие решения</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Дополнительные параметры -->
            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="only_incomplete" class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Вывести только незавершенные</span>
                </label>
            </div>

            <!-- Статус документа -->
            <div class="mb-6">
                <label for="filter_status" class="block text-gray-600 mb-2">Статус документа:</label>
                <select name="filter_status" id="filter_status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Все</option>
                    <option value="Completed">Завершенный</option>
                    <option value="Delayed">Отложен</option>
                    <option value="In work">В работе</option>
                </select>
            </div>

            <!-- Кнопки -->
            <div class="flex justify-between mt-6">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Выгрузить отчет</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle additional fields based on vulnerability_code checkbox
        document.getElementById('vulnerability_code').addEventListener('change', function() {
            var additionalFields = document.querySelector('.additional-fields');
            if (this.checked) {
                additionalFields.style.display = 'block';
            } else {
                additionalFields.style.display = 'none';
                // Uncheck additional fields when hiding them
                additionalFields.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        });
    });
</script>
@endsection