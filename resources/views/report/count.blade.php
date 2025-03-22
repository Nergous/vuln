@extends('layouts.app')

@section('content')
<!-- Подключаем Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="min-h-screen bg-gray-100 p-6">
    <!-- Шапка -->
    <div class="sticky top-0 z-10 bg-white shadow-lg rounded-lg mb-6">
        <div class="flex justify-between items-center p-4">
            <h1 class="text-2xl font-bold text-gray-800">Количество отчетов</h1>
            <a href="{{ route('home') }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-300">На главную</a>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl mx-auto">
        <!-- Форма -->
        <form id="reportForm" action="#" method="GET">
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

            <!-- Выбор типа отчета -->
            <div class="mb-6">
                <label class="block text-gray-600 mb-2">Тип отчета:</label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="report_type" value="yearly" class="form-radio h-5 w-5 text-blue-600" checked>
                        <span class="ml-2 text-gray-700">Выгрузить по годам</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="report_type" value="monthly" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Выгрузить по месяцам</span>
                    </label>
                </div>
            </div>

            <!-- Кнопки -->
            <div class="flex justify-between mt-6">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Скачать отчет</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('reportForm');
        const reportTypeRadios = document.querySelectorAll('input[name="report_type"]');

        reportTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'yearly') {
                    form.action = "{{ route('export.yearly') }}";
                } else if (this.value === 'monthly') {
                    form.action = "{{ route('export.monthly') }}";
                }
            });
        });

        // Устанавливаем начальное значение action формы
        form.action = "{{ route('export.yearly') }}";
    });
</script>
@endsection