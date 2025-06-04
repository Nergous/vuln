@extends('layouts.app')
@section('content')
<!-- Подключаем Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Подключаем Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Подключаем jQuery и Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="min-h-screen bg-gray-100 p-6">
    <!-- Шапка -->
    <div class="sticky top-0 z-10 bg-white shadow-lg rounded-lg mb-6">
        <div class="flex justify-between items-center p-4">
            <h1 class="text-2xl font-bold text-gray-800">Редактировать руководяще письмо</h1>
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
        <form action="{{ route('report.update', $document->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Название -->
            <div class="mb-6">
                <label for="name" class="block text-gray-600 mb-2">Наименование:</label>
                <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    value="{{ $document->name }}" placeholder="Введите наименование" required>
                @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Дата -->
            <div class="mb-6">
                <label for="date" class="block text-gray-600 mb-2">Дата:</label>
                <input type="date" name="date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date') border-red-500 @enderror"
                    value="{{ $document->date->format('Y-m-d') }}" required>
                @error('date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Номер -->
            <div class="mb-6">
                <label for="number" class="block text-gray-600 mb-2">Номер:</label>
                <input type="text" name="number" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('number') border-red-500 @enderror"
                    value="{{ $document->number }}" placeholder="Введите номер" required>
                @error('number')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Печать ФИЦ -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="stamp_high_date" class="block text-gray-600 mb-2">Печать ФИЦ дата:</label>
                    <input type="date" name="stamp_high_date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stamp_high_date') border-red-500 @enderror"
                        value="{{ $document->stamp_high_date ? $document->stamp_high_date->format('Y-m-d') : '' }}">
                    @error('stamp_high_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="stamp_high_number" class="block text-gray-600 mb-2">Печать ФИЦ номер:</label>
                    <input type="text" name="stamp_high_number" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stamp_high_number') border-red-500 @enderror"
                        value="{{ $document->stamp_high_number }}" placeholder="Введите номер печати ФИЦ">
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
                        value="{{ $document->stamp_low_date ? $document->stamp_low_date->format('Y-m-d') : '' }}">
                    @error('stamp_low_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="stamp_low_number" class="block text-gray-600 mb-2">Печать Обособка номер:</label>
                    <input type="text" name="stamp_low_number" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stamp_low_number') border-red-500 @enderror"
                        value="{{ $document->stamp_low_number }}" placeholder="Введите номер печати Обособка">
                    @error('stamp_low_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <!-- Файл -->
            <div class="mb-6">
                <label for="file" class="block text-gray-600 mb-2">Файл (doc, docx, pdf):</label>
                @if ($document->path_to_file)
                <div>
                    <a href="{{ route('download.file', basename($document->path_to_file)) }}" target="_blank" class="text-blue-500 hover:text-blue-600">Просмотреть текущий файл</a>
                </div>
                @else
                <p class="text-gray-500">Файл не загружен</p>
                @endif
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
                    <option value="{{ $tag->name }}" {{ in_array($tag->id, $document->tags->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Уязвимости -->
            <div id="vulnerabilities-container" class="mb-6">
                @foreach($document->vulnerabilities as $index => $vulnerability)
                <div class="vulnerability-field bg-gray-50 p-4 rounded-lg shadow mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Уязвимость / задача #{{ $index + 1 }}</h3>
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
                            <label for="vulnerabilities[{{ $index }}][name]" class="block text-gray-600 mb-2">Наименование уязвимости / задачи:</label>
                            <input type="text" name="vulnerabilities[{{ $index }}][name]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vulnerabilities.' . $index . '.name') border-red-500 @enderror"
                                value="{{ $vulnerability->name }}" placeholder="Введите наименование уязвимости / задачи" required>
                            @error('vulnerabilities.' . $index . '.name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="vulnerabilities[{{ $index }}][code]" class="block text-gray-600 mb-2">Код уязвимости / задачи:</label>
                            <select name="vulnerabilities[{{ $index }}][code]"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 vulnerability-code-select"
                                placeholder="Введите код уязвимости">
                                <option value="{{ $vulnerability->code }}" selected>{{ $vulnerability->code }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="vulnerabilities[{{ $index }}][software]" class="block text-gray-600 mb-2">Программное обеспечение:</label>
                            <input type="text" name="vulnerabilities[{{ $index }}][software]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vulnerabilities.' . $index . '.software') border-red-500 @enderror"
                                value="{{ $vulnerability->software }}" placeholder="Введите программное обеспечение">
                            @error('vulnerabilities.' . $index . '.software')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="vulnerabilities[{{ $index }}][status]" class="block text-gray-600 mb-2">Статус:</label>
                            <select name="vulnerabilities[{{ $index }}][status]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 status-select @error('vulnerabilities.' . $index . '.status') border-red-500 @enderror" required>
                                @foreach ($statuses as $status)
                                <option value="{{ $status->id }}" {{ $vulnerability->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
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
                </div>
                @endforeach
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация select2 для тегов
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

        // Загрузка всех кодов уязвимостей
        let allCodes = new Set();
        fetch("{{ route('vulnerabilities.all.codes') }}")
            .then(response => response.json())
            .then(data => {
                data.forEach(code => {
                    // Преобразуем код в строку, если это не строка
                    if (typeof code !== 'string') {
                        code = String(code);
                    }
                    allCodes.add(code);
                });
                initializeVulnerabilityCodeSelects(allCodes);
            })
            .catch(error => console.error('Ошибка:', error));

        // Функция для инициализации Select2 для всех полей кода
        function initializeVulnerabilityCodeSelects(allCodes) {
            $('.vulnerability-code-select').each(function() {
                const $select = $(this);
                $select.empty();

                // Добавляем текущее значение, если есть
                const currentCode = $select.data('current-value');
                if (currentCode) {
                    allCodes.add(currentCode);
                    $select.append($('<option>', {
                        value: currentCode,
                        text: currentCode,
                        selected: true
                    }));
                }

                $select.select2({
                    tags: true,
                    tokenSeparators: [',', ' '],
                    minimumInputLength: 1,
                    data: Array.from(allCodes).map(code => ({
                        id: code,
                        text: code
                    })),
                    placeholder: "Введите код уязвимости",
                    createTag: function(params) {
                        return {
                            id: params.term,
                            text: params.term,
                            newTag: true
                        };
                    },
                    language: {
                        inputTooShort: function() {
                            return "Пожалуйста, введите 1 или более символов";
                        }
                    }
                });
            });
        }

        // Обработчик для удаления уязвимости
        document.querySelectorAll('.remove-vulnerability').forEach(button => {
            button.addEventListener('click', function() {
                const vulnerabilityField = button.closest('.vulnerability-field');
                if (vulnerabilityField) {
                    vulnerabilityField.remove();
                }
            });
        });

        // Обработчик для добавления новой уязвимости
        document.getElementById('add-vulnerability').addEventListener('click', function() {
            const container = document.getElementById('vulnerabilities-container');
            const index = container.children.length;
            const newField = document.createElement('div');
            newField.className = 'vulnerability-field bg-gray-50 p-4 rounded-lg shadow mb-4';
            newField.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Уязвимость / задача #${index + 1}</h3>
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
                        <label for="vulnerabilities[${index}][name]" class="block text-gray-600 mb-2">Наименование уязвимости / задачи:</label>
                        <input type="text" name="vulnerabilities[${index}][name]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Введите наименование уязвимости / задачи" required>
                    </div>
                    <div class="mb-4">
                        <label for="vulnerabilities[${index}][code]" class="block text-gray-600 mb-2">Код уязвимости / задачи:</label>
                        <select name="vulnerabilities[${index}][code]" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 vulnerability-code-select"
                            placeholder="Введите код уязвимости"></select>
                    </div>
                    <div class="mb-4">
                        <label for="vulnerabilities[${index}][software]" class="block text-gray-600 mb-2">Программное обеспечение:</label>
                        <input type="text" name="vulnerabilities[${index}][software]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Введите программное обеспечение">
                    </div>
                    <div class="mb-4">
                        <label for="vulnerabilities[${index}][status]" class="block text-gray-600 mb-2">Статус:</label>
                        <select name="vulnerabilities[${index}][status]" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 status-select" required>
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

            container.appendChild(newField);

            // Инициализация Select2 для нового поля
            $(newField.querySelector('.vulnerability-code-select')).select2({
                tags: true,
                tokenSeparators: [',', ' '],
                minimumInputLength: 1,
                data: [{
                    id: '',
                    text: ''
                }, ...Array.from(allCodes).map(code => ({
                    id: code,
                    text: code
                }))],
                placeholder: "Введите код уязвимости",
                createTag: function(params) {
                    return {
                        id: params.term,
                        text: params.term,
                        newTag: true
                    };
                },
                language: {
                    inputTooShort: function() {
                        return "Пожалуйста, введите 1 или более символов";
                    }
                }
            });

            // Обработчик для кнопки удаления уязвимости
            newField.querySelector('.remove-vulnerability').addEventListener('click', function() {
                container.removeChild(newField);
            });

            // Обработчик для сворачивания/разворачивания уязвимости
            const toggleButton = newField.querySelector('.toggle-vulnerability');
            const content = newField.querySelector('.vulnerability-content');
            toggleButton.addEventListener('click', function() {
                content.classList.toggle('hidden');
                toggleButton.querySelector('svg').classList.toggle('rotate-180');
            });

            // Обработка статусов для нового поля
            const addNewStatusButton = newField.querySelector('.add-new-status');
            const newStatusInput = newField.querySelector('.new-status-input');
            const statusSelect = newField.querySelector('.status-select');
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

        // Инициализация существующих полей
        document.querySelectorAll('.vulnerability-field').forEach(field => {
            const codeField = field.querySelector('select[name$="[code]"]');
            const currentCode = codeField.value;
            codeField.dataset.currentValue = currentCode;
        });

        // Обработчик поиска для всех полей кода
        $(document).on('input', '.vulnerability-code-select', function() {
            const query = $(this).val().toLowerCase(); // Преобразуем запрос в нижний регистр
            const results = Array.from(allCodes).filter(code => {
                // Преобразуем code в строку, если это не строка
                if (typeof code !== 'string') {
                    code = String(code);
                }
                return code.toLowerCase().includes(query);
            });

            $(this).select2('data', results.map(code => ({
                id: code,
                text: code
            })));
        });
    });
</script>
@endsection