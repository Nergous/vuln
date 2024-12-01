@extends('layouts.app')

@section('content')
<div class="col">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: rgba(255, 255, 255, 1); display:flex; justify-content: space-between; align-items: center; position: sticky; top: 0;">
                    <div style="display: flex; justify-content: space-between; gap: 10px;">
                        @if($user->type == 'Admin')
                        <a href="{{ route('users.index') }}" class="btn btn-warning">Все пользователи</a>
                        @endif
                        @if($user->type != 'Viewer')
                        <a href="{{ route('report.create') }}" class="btn btn-info">Добавить отчет</a>
                        @endif
                        <a href="{{ route('report.download') }}" class="btn btn-primary">Выгрузить отчет</a>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="margin-bottom: 0px;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Выйти</button>
                    </form>
                </div>

                <div class="card-body">
                    @if($user->type == 'Admin')
                    <h1>Добро пожаловать, Администратор!</h1>
                    @elseif($user->type == 'Operator')
                    <h1>Добро пожаловать, Оператор!</h1>
                    @elseif($user->type == 'Viewer')
                    <h1>Добро пожаловать, Смотрящий😎!</h1>
                    @endif

                    <div class="float-right">
                    </div>
                    <br />
                    <div style="display: flex; justify-content: space-around;">
                        <form action="{{ route('home') }}" method="GET" class="form-inline mb-3" style="display: flex; justify-content: flex-end; width: 100%;" id="filter-form">
                            <div class="form-group mr-3">
                                <label for="filter_year" class="mr-2">Год:</label>
                                <select name="filter_year" id="filter_year" class="form-control">
                                    <option value="" {{ request('filter_year') == 'all' ? 'selected' : '' }}>Выберите год</option>
                                    @foreach ($uniqueYears as $year)
                                    <option value="{{ $year }}" {{ request('filter_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mr-3">
                                <label for="filter_month" class="mr-2">Месяц:</label>
                                <select name="filter_month" id="filter_month" class="form-control">
                                    <option value="all" {{ request('filter_month') == 'all' ? 'selected' : '' }}>Выберите месяц</option>
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
                            <div class="form-group mr-3">
                                <label for="filter_status" class="mr-2">Фильтр по статусу:</label>
                                <select name="filter_status" id="filter_status" class="form-control">
                                    <option value="">Все</option>
                                    <option value="In work" {{ request('filter_status') == 'In work' ? 'selected' : '' }}>В работе</option>
                                    <option value="Completed" {{ request('filter_status') == 'Completed' ? 'selected' : '' }}>Завершен</option>
                                    <option value="Delayed" {{ request('filter_status') == 'Delayed' ? 'selected' : '' }}>Отложен</option>
                                </select>
                            </div>
                            <!-- <button type="submit" class="btn btn-primary mr-2">Применить фильтры</button> -->
                            <a href="{{ route('home') }}" class="btn btn-secondary">Сбросить фильтры</a>
                        </form>
                    </div>
                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('home', ['sort' => 'name', 'order' => request('order') === 'asc' && request('sort') === 'name' ? 'desc' : 'asc']) }}">
                                        Название
                                        @if(request('sort') === 'name')
                                        @if(request('order') === 'asc')
                                        <i class="fas fa-sort-up"></i>
                                        @else
                                        <i class="fas fa-sort-down"></i>
                                        @endif
                                        @else
                                        <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('home', ['sort' => 'date', 'order' => request('order') === 'asc' && request('sort') === 'date' ? 'desc' : 'asc']) }}">
                                        Дата
                                        @if(request('sort') === 'date')
                                        @if(request('order') === 'asc')
                                        <i class="fas fa-sort-up"></i>
                                        @else
                                        <i class="fas fa-sort-down"></i>
                                        @endif
                                        @else
                                        <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('home', ['sort' => 'status', 'order' => request('order') === 'asc' && request('sort') === 'status' ? 'desc' : 'asc']) }}">
                                        Статус
                                        @if(request('sort') === 'status')
                                        @if(request('order') === 'asc')
                                        <i class="fas fa-sort-up"></i>
                                        @else
                                        <i class="fas fa-sort-down"></i>
                                        @endif
                                        @else
                                        <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Тэги</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                            <tr data-tags="{{ $document->tags->pluck('id')->implode(',') }}">
                                <td>{{ $document->name }}</td>
                                <td>{{ date('d.m.Y', strtotime($document->date)) }}</td>
                                <td>
                                    @if($document->status == "In work") В работе
                                    @elseif($document->status == "Completed") Завершен
                                    @elseif($document->status == "Delayed")
                                    Отложен
                                    @if(isset($document->delayedReason))
                                    <span class="delayed-reason" data-reason="{{ $document->delayedReason }}">?</span>
                                    @endif
                                    @endif
                                </td>
                                <td>
                                    @foreach($document->tags as $tag)
                                    <button type="button" class="btn btn-sm btn-secondary tag-filter" data-tag="{{ $tag->id }}">{{ $tag->name }}</button>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="btn-group d-flex flex-wrap">
                                        @if($user->type == 'Admin')
                                        <a href="{{ route('report.edit', $document->id) }}" class="btn btn-sm btn-info mb-1">Редактировать</a>
                                        <a href="{{ route('report.all_vulnerabilites', $document->id) }}" class="btn btn-sm btn-warning mb-1">Просмотреть уязвимости / задачи</a>
                                        <form action="{{ route('report.destroy', $document->id) }}" method="POST" style="display: inline;" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger mb-1 delete-btn">Удалить</button>
                                        </form>
                                        <a href="{{ route('report.delay', $document->id) }}" class="btn btn-sm btn-secondary mb-1 {{ in_array($document->status, ['Delayed', 'Completed']) ? 'disabled' : '' }}">Отложить</a>
                                        @endif
                                        <a href="{{ route('download.file', basename($document->path_to_file)) }}" class="btn btn-sm btn-success mb-1">Скачать отчет</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Tooltipster
        $('.delayed-reason').each(function() {
            var reason = $(this).data('reason');
            if (reason) {
                $(this).tooltipster({
                    content: $('<div>').html(reason),
                    theme: 'tooltipster-default',
                    maxWidth: 300,
                });
            }
        });

        // Confirmation dialog for delete button
        document.querySelectorAll('.delete-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                if (!confirm('Вы уверены, что хотите удалить этот документ?')) {
                    event.preventDefault();
                } else {
                    this.closest('form').submit();
                }
            });
        });

        // Submit form on select change
        $('#filter_year, #filter_month, #filter_status').change(function() {
            $('#filter-form').submit();
        });

        // Filter by tag
        var selectedTags = [];

        document.querySelectorAll('.tag-filter').forEach(function(button) {
            button.addEventListener('click', function(event) {
                var tagId = this.getAttribute('data-tag');
                var index = selectedTags.indexOf(tagId);

                if (index === -1) {
                    selectedTags.push(tagId);
                    this.classList.add('active');
                } else {
                    selectedTags.splice(index, 1);
                    this.classList.remove('active');
                }

                filterRowsByTags();
            });
        });

        function filterRowsByTags() {
            document.querySelectorAll('tr[data-tags]').forEach(function(row) {
                var tags = row.getAttribute('data-tags').split(',');
                var shouldDisplay = selectedTags.length === 0 || selectedTags.every(function(tag) {
                    return tags.includes(tag);
                });

                row.style.display = shouldDisplay ? '' : 'none';
            });
        }
    });
</script>
@endsection

<style>
    .btn-group .btn {
        flex: 1 1 auto;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 36px;
        /* Установите фиксированную высоту кнопок */
        line-height: 34px;
        /* Выравнивание текста по вертикали */
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        /* Пространство между кнопками */
    }

    .tag-filter.active {
        background-color: #007bff !important;
        color: white;
    }
</style>