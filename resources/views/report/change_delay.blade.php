@extends('layouts.app')

@section('content')
<!-- Подключаем Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="min-h-screen bg-gray-100 p-6">
    <!-- Шапка -->
    <div class="sticky top-0 z-10 bg-white shadow-lg rounded-lg mb-6">
        <div class="flex justify-between items-center p-4">
            <h1 class="text-2xl font-bold text-gray-800">Отложить письмо</h1>
            <a href="{{ route('home') }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-300">На главную</a>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl mx-auto">
        <!-- Ошибки валидации -->
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Форма -->
        <form action="{{ route('report.create_delay', $id) }}" method="POST">
            @csrf

            <!-- Дата -->
            <div class="mb-6">
                <label for="date" class="block text-gray-600 mb-2">Дата:</label>
                <input type="date" name="date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date') border-red-500 @enderror"
                    required>
                @error('date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Причина -->
            <div class="mb-6">
                <label for="reason" class="block text-gray-600 mb-2">Причина:</label>
                <div id="reasonEditor" class="w-full h-48 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reason') border-red-500 @enderror"></div>
                <input type="hidden" name="reason" id="reasonInput">
                @error('reason')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Кнопки -->
            <div class="flex justify-between mt-6">
                <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-300">Отложить</button>
                <a href="{{ route('home') }}" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-300">Назад</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var quill = new Quill('#reasonEditor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        document.querySelector('form').addEventListener('submit', function() {
            var reasonInput = document.getElementById('reasonInput');
            reasonInput.value = quill.root.innerHTML;
        });
    });
</script>
@endsection