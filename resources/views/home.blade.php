@extends('layouts.app')

@section('content')
<!-- Подключаем Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="min-h-screen bg-gray-100 p-6">
    <!-- Шапка -->
    <div class="sticky top-0 z-10 bg-white shadow-lg rounded-lg mb-6">
        <div class="flex justify-between items-center p-4">
            <div class="flex space-x-4">
                @if($user->type == 'Admin')
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-300">Все пользователи</a>
                @endif
                @if($user->type != 'Viewer')
                <a href="{{ route('report.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Добавить руководящий документ</a>
                @endif
                <a href="#"
                    class="tag-export-btn px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition duration-300 hidden"
                    id="tag-export-btn">
                    Выгрузить по тэгам
                </a>
                <a href="{{ route('report.download') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">Выгрузить отчет</a>
                <a href="{{ route('report.count') }}" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-300">Количество отчетов</a>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300">Выйти</button>
            </form>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Приветствие -->
        @if($user->type == 'Admin')
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Добро пожаловать, Администратор!</h1>
        @elseif($user->type == 'Operator')
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Добро пожаловать, Оператор!</h1>
        @elseif($user->type == 'Viewer')
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Добро пожаловать, Смотрящий 😎!</h1>
        @endif

        <!-- Фильтры -->
        <div class="mb-8">
            <form action="{{ route('home') }}" method="GET" id="filter-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-600 mb-2">Год:</label>
                    <select name="filter_year" id="filter_year" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Выберите год</option>
                        @foreach ($uniqueYears as $year)
                        <option value="{{ $year }}" {{ request('filter_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-600 mb-2">Месяц:</label>
                    <select name="filter_month" id="filter_month" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Выберите месяц</option>
                        @php
                        $months = [
                        1 => 'Январь',
                        2 => 'Февраль',
                        3 => 'Март',
                        4 => 'Апрель',
                        5 => 'Май',
                        6 => 'Июнь',
                        7 => 'Июль',
                        8 => 'Август',
                        9 => 'Сентябрь',
                        10 => 'Октябрь',
                        11 => 'Ноябрь',
                        12 => 'Декабрь'
                        ];
                        @endphp
                        @foreach ($months as $monthNumber => $monthName)
                        <option value="{{ $monthNumber }}" {{ request('filter_month') == $monthNumber ? 'selected' : '' }}>{{ $monthName }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-600 mb-2">Статус:</label>
                    <select name="filter_status" id="filter_status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Все</option>
                        <option value="In work" {{ request('filter_status') == 'In work' ? 'selected' : '' }}>В работе</option>
                        <option value="Completed" {{ request('filter_status') == 'Completed' ? 'selected' : '' }}>Завершен</option>
                        <option value="Delayed" {{ request('filter_status') == 'Delayed' ? 'selected' : '' }}>Отложен</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-600 mb-2">На странице:</label>
                    <select name="per_page" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page', 5) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page', 5) == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
                <div class="flex items-end space-x-4">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Применить</button>
                    <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-300 text-gray-600 rounded-lg hover:bg-gray-400 transition duration-300">Сбросить</a>
                </div>

            </form>
        </div>

        <!-- Таблица -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-64 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('home', ['sort' => 'name', 'order' => request('order') === 'asc' && request('sort') === 'name' ? 'desc' : 'asc']) }}">
                                Название
                                @if(request('sort') === 'name')
                                @if(request('order') === 'asc')
                                <i class="fas fa-sort-up ml-2"></i>
                                @else
                                <i class="fas fa-sort-down ml-2"></i>
                                @endif
                                @else
                                <i class="fas fa-sort ml-2"></i>
                                @endif
                            </a>
                        </th>
                        <th class="w-20 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('home', ['sort' => 'date', 'order' => request('order') === 'asc' && request('sort') === 'date' ? 'desc' : 'asc']) }}">
                                Дата
                                @if(request('sort') === 'date')
                                @if(request('order') === 'asc')
                                <i class="fas fa-sort-up ml-2"></i>
                                @else
                                <i class="fas fa-sort-down ml-2"></i>
                                @endif
                                @else
                                <i class="fas fa-sort ml-2"></i>
                                @endif
                            </a>
                        </th>
                        <th class="w-32 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('home', ['sort' => 'status', 'order' => request('order') === 'asc' && request('sort') === 'status' ? 'desc' : 'asc']) }}">
                                Статус
                                @if(request('sort') === 'status')
                                @if(request('order') === 'asc')
                                <i class="fas fa-sort-up ml-2"></i>
                                @else
                                <i class="fas fa-sort-down ml-2"></i>
                                @endif
                                @else
                                <i class="fas fa-sort ml-2"></i>
                                @endif
                            </a>
                        </th>
                        <th class="w-64 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тэги</th>
                        <th class="w-32 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Уязвимости</th>
                        <th class="w-24 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($documents as $document)
                    <tr data-tags="{{ $document->tags->pluck('id')->implode(',') }}">
                        <td class="w-64 px-6 py-4 whitespace-wrap">{{ $document->name }}</td>
                        <td class="w-20 px-6 py-4 whitespace-nowrap">{{ date('d.m.Y', strtotime($document->date)) }}</td>
                        <td class="w-32 px-6 py-4 whitespace-nowrap">
                            @if($document->status == "In work")
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">В работе</span>
                            @elseif($document->status == "Completed")
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">Завершен</span>
                            @elseif($document->status == "Delayed")
                            <div class="tooltip-container" data-html="true">
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Отложен
                                </span>
                                @if(isset($document->delayedReason))
                                <div class="tooltip">
                                    {!! $document->delayedReason !!}
                                </div>

                                @endif
                            </div>
                            @endif
                        </td>
                        <td class="w-64 px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-2">
                                @foreach($document->tags as $tag)
                                <button type="button" class="px-3 py-1 text-sm leading-4 font-medium inline-flex items-center tag-filter" data-tag="{{ $tag->id }}">
                                    <span class="bg-gray-200 text-gray-800 rounded-full px-2 py-1">{{ $tag->name }}</span>
                                </button>
                                @endforeach
                            </div>
                        </td>
                        <td class="w-32 px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('report.all_vulnerabilites', $document->id) }}" class="px-3 py-1.5 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg transition duration-300">⚠️ Уязвимости</a>
                        </td>
                        <td class="w-24 px-6 py-4 whitespace-nowrap">
                            <div class="relative">
                                <!-- Кнопка для открытия выпадающего списка -->
                                <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="toggleDropdown('dropdown-{{ $document->id }}')">
                                    ⋯
                                </button>
                                <!-- Выпадающий список -->
                                <div id="dropdown-{{ $document->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                                    <div class="py-1">
                                        @if($user->type == 'Admin')
                                        <a href="{{ route('report.edit', $document->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">✏️ Редактировать</a>
                                        <form action="{{ route('report.destroy', $document->id) }}" method="POST" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 delete-btn">🗑️ Удалить</button>
                                        </form>
                                        <a href="{{ route('report.delay', $document->id) }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ in_array($document->status, ['Delayed', 'Completed']) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            onclick="return handleDelayClick(event, `{{ in_array($document->status, ['Delayed', 'Completed'])}}`);">
                                            ⏸️ Отложить
                                        </a>
                                        @endif
                                        @if($document->path_to_file)
                                        <a href="{{ route('download.report', basename($document->path_to_file)) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📥 Скачать</a>
                                        @else
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-400 cursor-not-allowed hover:bg-gray-100">📥 Скачать</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $documents->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmation dialog for delete button
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Вы уверены, что хотите удалить этот документ?')) {
                    e.preventDefault();
                } else {
                    this.closest('form').submit();
                }
            });
        });

        // Submit form on select change
        $('#filter_year, #filter_month, #filter_status').change(function() {
            $('#filter-form').submit();
        });

        // Filter by tags
        let selectedTags = [];
        document.querySelectorAll('.tag-filter').forEach(button => {
            button.addEventListener('click', function() {
                const tagId = this.getAttribute('data-tag');
                const index = selectedTags.indexOf(tagId);

                // Добавляем или удаляем тег из выбранных
                if (index === -1) {
                    selectedTags.push(tagId);
                } else {
                    selectedTags.splice(index, 1);
                }

                // Обновляем выделение для всех тегов с одинаковым data-tag
                document.querySelectorAll(`.tag-filter[data-tag="${tagId}"]`).forEach(tag => {
                    tag.classList.toggle('active', selectedTags.includes(tagId));
                });

                // Фильтруем строки таблицы
                filterRowsByTags();
                updateExportButton();
            });
        });

        function filterRowsByTags() {
            document.querySelectorAll('tr[data-tags]').forEach(row => {
                const tags = row.getAttribute('data-tags').split(',');
                const shouldDisplay = selectedTags.length === 0 || selectedTags.every(tag => tags.includes(tag));
                row.style.display = shouldDisplay ? '' : 'none';
            });
        }

        function updateExportButton() {
        const exportButton = document.getElementById('tag-export-btn');
        const baseExportUrl = "{{ route('export.tags') }}"; // Замените на вашу роут
        const tags = selectedTags.join(',');

        if (selectedTags.length > 0) {
            exportButton.href = `${baseExportUrl}?tags=${tags}`;
            exportButton.classList.remove('hidden');
        } else {
            exportButton.href = '#';
            exportButton.classList.add('hidden');
        }
    }
    });

    // Функция для открытия/закрытия выпадающего списка
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }

    // Закрытие выпадающего списка при клике вне его
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('.relative');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(event.target)) {
                const menu = dropdown.querySelector('div');
                if (menu && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                }
            }
        });
    });

    // Функция для обработки клика на кнопку "Отложить"
    function handleDelayClick(event, isDisabled) {
        if (isDisabled) {
            event.preventDefault(); // Отменяем действие
            return false; // Останавливаем дальнейшее выполнение
        }
        return true; // Продолжаем выполнение, если кнопка активна
    }
</script>

<style>
    .tooltip-container {
        position: relative;
        display: inline-block;
    }

    .tooltip {
        visibility: hidden;
        width: 200px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 4px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        /* Позиция тултипа над элементом */
        left: 50%;
        margin-left: -100px;
        /* Смещение тултипа влево на половину его ширины */
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tooltip-container:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }

    .tooltip::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }

    .tag-filter.active span {
        background-color: #007bff !important;
        color: white !important;
    }

    .table th {
        font-weight: 500;
    }

    .table td {
        vertical-align: top;
    }

    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
    }

    .pagination li {
        margin: 0 4px;
    }

    .pagination a,
    .pagination span {
        display: inline-block;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        color: #4b5563;
        text-decoration: none;
    }

    .pagination a:hover {
        background-color: #f3f4f6;
    }

    .pagination .active span {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .pagination .disabled span {
        color: #9ca3af;
        cursor: not-allowed;
    }
</style>
@endsection