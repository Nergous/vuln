@extends('layouts.app')
@section('content')
<!-- Подключаем Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Подключаем Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="min-h-screen bg-gray-100 p-6">
    <!-- Шапка -->
    <div class="sticky top-0 z-10 bg-white shadow-lg rounded-lg mb-6">
        <div class="flex justify-between items-center p-4">
            <h1 class="text-2xl font-bold text-gray-800">Добавить руководящий документ</h1>
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
        <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Название -->
            <div class="mb-6">
                <label for="name" class="block text-gray-600 mb-2">Название:</label>
                <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    value="{{ old('name') }}" placeholder="Введите название" required>
                @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Дата -->
            <div class="mb-6">
                <label for="date" class="block text-gray-600 mb-2">Дата:</label>
                <input type="date" name="date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date') border-red-500 @enderror"
                    value="{{ old('date') }}" required>
                @error('date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Номер -->
            <div class="mb-6">
                <label for="number" class="block text-gray-600 mb-2">Номер:</label>
                <input type="text" name="number" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('number') border-red-500 @enderror"
                    value="{{ old('number') }}" placeholder="Введите номер" required>
                @error('number')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Печать ФИЦ -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="stamp_high_date" class="block text-gray-600 mb-2">Печать ФИЦ дата:</label>
                    <input type="date" name="stamp_high_date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stamp_high_date') border-red-500 @enderror"
                        value="{{ old('stamp_high_date') }}">
                    @error('stamp_high_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="stamp_high_number" class="block text-gray-600 mb-2">Печать ФИЦ номер:</label>
                    <input type="text" name="stamp_high_number" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stamp_high_number') border-red-500 @enderror"
                        value="{{ old('stamp_high_number') }}" placeholder="Введите номер печати ФИЦ">
                    @error('stamp_high_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <!-- Печать Обособка -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="stamp_low_date" class="block text-gray-600 mb-2">Печать Обособка дата:</label>
                    <input type="date" name="stamp_low_date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stamp_low_date') border-red-500 @enderror"
                        value="{{ old('stamp_low_date') }}">
                    @error('stamp_low_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="stamp_low_number" class="block text-gray-600 mb-2">Печать Обособка номер:</label>
                    <input type="text" name="stamp_low_number" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stamp_low_number') border-red-500 @enderror"
                        value="{{ old('stamp_low_number') }}" placeholder="Введите номер печати Обособка">
                    @error('stamp_low_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <!-- Файл -->
            <div class="mb-6">
                <label for="file" class="block text-gray-600 mb-2">Файл (doc, docx, pdf):</label>
                <input type="file" name="file" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('file') border-red-500 @enderror"
                    accept=".doc,.docx,.pdf">
                @error('file')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Теги -->
            <div class="mb-6">
                <label for="tags" class="block text-gray-600 mb-2">Теги:</label>
                <select name="tags[]" id="tags" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 select2" multiple="multiple">
                    @foreach($tags as $tag)
                    <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Уязвимости -->
            <div id="vulnerabilities-container" class="mb-6">
                <!-- Здесь будут добавляться новые поля для уязвимостей -->
            </div>
            <!-- Кнопки -->
            <div class="flex justify-between mt-6">
                <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-300">Сохранить</button>
                <button type="button" id="add-vulnerability" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Добавить уязвимость / задачу</button>
                <a href="{{ route('home') }}" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-300">Назад</a>
            </div>
        </form>
    </div>
</div>
<!-- Подключаем jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Подключаем Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let vulnerabilityIndex = 0;
    let allCodes = new Set(); // Используем Set для хранения уникальных значений
    // Загружаем все коды уязвимостей при загрузке страницы
    fetch("{{ route('vulnerabilities.all.codes') }}")
        .then(response => response.json())
        .then(data => {
            data.forEach(code => allCodes.add(code)); // Добавляем коды в Set
        })
        .catch(error => {
            console.error('Ошибка при загрузке кодов уязвимостей:', error);
        });
    // Функция для поиска кодов по запросу
    function searchCodes(query) {
        const results = [];
        allCodes.forEach(code => {
            if (code.toLowerCase().includes(query.toLowerCase())) {
                results.push(code); // Добавляем подходящие коды в массив
            }
        });
        return results;
    }
    // Функция для добавления новой уязвимости
    document.getElementById('add-vulnerability').addEventListener('click', function() {
        const container = document.getElementById('vulnerabilities-container');
        const vulnerabilityField = document.createElement('div');
        vulnerabilityField.className = 'vulnerability-field bg-gray-50 p-4 rounded-lg shadow mb-4';
        vulnerabilityField.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Уязвимость / задача #${vulnerabilityIndex + 1}</h3>
                <div class="flex space-x-2">
                    <button type="button" class="text-gray-500 hover:text-gray-700 toggle-vulnerability">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <button type="button" class="text-red-500 hover:text-red-700 remove-vulnerability">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="vulnerability-content">
                <div class="mb-4">
                    <label for="vulnerabilities[${vulnerabilityIndex}][name]" class="block text-gray-600 mb-2">Название уязвимости / задачи:</label>
                    <input type="text" name="vulnerabilities[${vulnerabilityIndex}][name]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Введите название уязвимости / задачи" required>
                </div>
                <div class="mb-4">
                    <label for="vulnerabilities[${vulnerabilityIndex}][code]" class="block text-gray-600 mb-2">Код уязвимости / задачи:</label>
                    <select name="vulnerabilities[${vulnerabilityIndex}][code]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 vulnerability-code-select" placeholder="Введите код уязвимости / задачи"></select>
                </div>
                <div class="mb-4">
                    <label for="vulnerabilities[${vulnerabilityIndex}][software]" class="block text-gray-600 mb-2">Программное обеспечение:</label>
                    <input type="text" name="vulnerabilities[${vulnerabilityIndex}][software]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Введите программное обеспечение">
                </div>
                <div class="mb-4">
                    <label for="vulnerabilities[${vulnerabilityIndex}][status]" class="block text-gray-600 mb-2">Статус:</label>
                    <select name="vulnerabilities[${vulnerabilityIndex}][status]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 status-select" required>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300 add-new-status">Добавить новый статус</button>
                    <div class="new-status-input mt-2" style="display: none;">
                        <input type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Введите новый статус">
                        <button type="button" class="mt-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-300 save-new-status">Сохранить</button>
                        <button type="button" class="mt-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-300 cancel-new-status">Отмена</button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(vulnerabilityField);
        vulnerabilityIndex++;
        // Инициализация Select2 для нового поля кода уязвимости
        $(vulnerabilityField).find('.vulnerability-code-select').select2({
            tags: true,
            tokenSeparators: [','],
            minimumInputLength: 1,
            data: Array.from(allCodes).map(code => ({
                id: code,
                text: code
            })),
            placeholder: "Введите код уязвимости / задачи",
            createTag: function(params) {
                const term = params.term;
                if ($.trim(term) === '') return null;
                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            },
            language: {
                inputTooShort: function() {
                    return "Пожалуйста, введите 1 или более символов";
                },
                noResults: function() {
                    return 'Нажмите Enter, чтобы добавить новый код';
                }
            }
        });
        // Добавляем обработчик для кнопки удаления уязвимости
        vulnerabilityField.querySelector('.remove-vulnerability').addEventListener('click', function() {
            container.removeChild(vulnerabilityField);
        });
        // Добавляем обработчик для кнопки сворачивания/разворачивания
        const toggleButton = vulnerabilityField.querySelector('.toggle-vulnerability');
        const content = vulnerabilityField.querySelector('.vulnerability-content');
        toggleButton.addEventListener('click', function() {
            content.classList.toggle('hidden');
            toggleButton.querySelector('svg').classList.toggle('rotate-180');
        });
        // Добавляем обработчик для кнопки добавления нового статуса
        const addNewStatusButton = vulnerabilityField.querySelector('.add-new-status');
        const newStatusInput = vulnerabilityField.querySelector('.new-status-input');
        const statusSelect = vulnerabilityField.querySelector('.status-select');
        addNewStatusButton.addEventListener('click', function() {
            statusSelect.style.display = 'none';
            addNewStatusButton.style.display = 'none';
            newStatusInput.style.display = 'block';
        });
        newStatusInput.querySelector('.cancel-new-status').addEventListener('click', function() {
            statusSelect.style.display = 'block';
            addNewStatusButton.style.display = 'block';
            newStatusInput.style.display = 'none';
        });
        newStatusInput.querySelector('.save-new-status').addEventListener('click', function() {
            const newStatus = newStatusInput.querySelector('input').value.trim();
            if (newStatus) {
                // AJAX-запрос для добавления нового статуса
                $.ajax({
                    url: "{{ route('add.new.status') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: newStatus
                    },
                    success: function(response) {
                        const option = document.createElement('option');
                        option.value = response.id;
                        option.text = response.name;
                        statusSelect.appendChild(option);
                        statusSelect.value = response.id;
                        statusSelect.style.display = 'block';
                        addNewStatusButton.style.display = 'block';
                        newStatusInput.style.display = 'none';
                        newStatusInput.querySelector('input').value = '';
                    },
                    error: function(xhr) {
                        alert("Ошибка при добавлении статуса: " + xhr.responseText);
                    }
                });
            }
        });
    });
    // Инициализация Select2 для поля тегов
    $('#tags').select2({
        tags: true,
        tokenSeparators: [','],
        language: {
            noResults: function() {
                return `<button type="button" class="btn btn-secondary btn-sm" id="addNewTagInSelect">Добавить новый тег</button>`;
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }
    });
    // Обработка клика по кнопке "Добавить новый тег" внутри select2
    $(document).on('click', '#addNewTagInSelect', function() {
        const newTagText = document.querySelector('.select2-search__field').value;
        if (newTagText) {
            fetch("{{ route('save.tag') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: newTagText
                    })
                }).then(response => response.json())
                .then(data => {
                    if (data.id) {
                        const option = document.createElement('option');
                        option.value = data.id;
                        option.text = newTagText;
                        option.selected = true;
                        $('#tags').append(option).trigger('change');
                        $('.select2-search__field').val('');
                        $('#tags').select2('close');
                    }
                });
        }
    });
</script>
@endsection