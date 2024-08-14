@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Главная страница</div>

                <div class="card-body">
                    @if($user->type == 'Admin')
                        <h1>Добро пожаловать, Администратор!</h1>
                        <a href="{{ route('users.index') }}" class="btn btn-primary">Все пользователи</a>
                    @elseif($user->type == 'Operator')
                        <h1>Добро пожаловать, Оператор!</h1>
                    @else
                        <h1>Добро пожаловать!</h1>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning">Выйти</button>
                    </form>
                    <div class="float-right">
                        <a href="{{ route('report.create') }}" class="btn btn-primary">Добавить отчет</a>
                    </div>
                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th>
                                    <a
                                        href="{{ route('home', ['sort' => 'name', 'order' => request('order') === 'asc' && request('sort') === 'name' ? 'desc' : 'asc']) }}">
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
                                    <a
                                        href="{{ route('home', ['sort' => 'date', 'order' => request('order') === 'asc' && request('sort') === 'date' ? 'desc' : 'asc']) }}">
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
                                    <a
                                        href="{{ route('home', ['sort' => 'status', 'order' => request('order') === 'asc' && request('sort') === 'status' ? 'desc' : 'asc']) }}">
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
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                <tr>
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
                                        <div class="btn-group d-flex">
                                            @if($user->type == 'Admin')
                                                <a href="{{ route('report.edit', $document->id) }}"
                                                    class="btn mr-2 btn-sm btn-info">Редактировать</a>
                                                <a href="{{ route('report.all_vulnerabilites', $document->id) }}"
                                                    class="btn mr-2 btn-sm btn-warning">Просмотреть уязвимости</a>
                                                <form action="{{ route('report.destroy', $document->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn mr-2 btn-sm btn-danger">Удалить</button>
                                                </form>
                                                <a href="{{ route('report.delay', $document->id) }}"
                                                    class="btn btn-sm btn-secondary {{ in_array($document->status, ['Delayed', 'Completed']) ? 'disabled' : '' }}">Отложить</a>
                                            @endif
                                            <a href="{{ route('download.file', basename($document->path_to_file)) }}"
                                                class="btn btn-sm btn-success">Скачать отчет</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <form action="{{ route('home') }}" method="GET" class="form-inline mb-3">
                        <div class="form-group mr-3">
                            <label for="filter_year" class="mr-2">Год:</label>
                            <select name="filter_year" id="filter_year" class="form-control">
                                @for ($year = date('Y'); $year >= 1970; $year--)
                                    <option value="{{ $year }}" {{ request('filter_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group mr-3">
                            <label for="filter_month" class="mr-2">Месяц:</label>
                            <select name="filter_month" id="filter_month" class="form-control">
                                <option value="all" {{ request('filter_month') == 'all' ? 'selected' : '' }}>Весь год
                                </option>
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
                                <option value="In work" {{ request('filter_status') == 'In work' ? 'selected' : '' }}>
                                    В работе</option>
                                <option value="Completed" {{ request('filter_status') == 'Completed' ? 'selected' : '' }}>
                                    Завершен</option>
                                <option value="Delayed" {{ request('filter_status') == 'Delayed' ? 'selected' : '' }}>
                                    Отложен</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Применить фильтры</button>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Сбросить фильтры</a>
                    </form>
                </div>


                <div class="card-footer">


                    <div class="form-group mr-3">
                        <form action="{{ route('export.yearly') }}" method="GET" class="mb-3">
                            <div class="form-group mr-3">
                                <label for="start_date" class="mr-2">Начальная дата:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>
                            <div class="form-group mr-3">
                                <label for="end_date" class="mr-2">Конечная дата:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required>
                            </div>
                            <div class="form-group m-3 d-flex flex-column align-items-start">
                                <label class="m-3 justify-content-start text-left">Выберите поля отчета:</label>
                                <div class="form-check m-3 w-100 justify-content-start">
                                    <label class="mr-2"><input type="checkbox" name="fields[]" value="delayed_reason"
                                            checked>
                                        Причина отложки</label>
                                </div>
                                <div class="form-check m-3 w-100 justify-content-start">
                                    <label class="mr-2"><input type="checkbox" name="fields[]"
                                            value="vulnerability_code" id="vulnerability_code">
                                        Код уязвимости</label>
                                </div>
                                <div class="additional-fields" style="display: none;">
                                    <div class="form-check m-3 w-100 justify-content-start">
                                        <label class="mr-2"><input type="checkbox" name="fields[]"
                                                value="vulnerability_name">
                                            Название уязвимости</label>
                                    </div>
                                    <div class="form-check m-3 w-100 justify-content-start">
                                        <label class="mr-2"><input type="checkbox" name="fields[]" value="solution">
                                            Решение</label>
                                    </div>
                                    <div class="form-check m-3 w-100 justify-content-start">
                                        <label class="mr-2"><input type="checkbox" name="fields[]"
                                                value="solution_type">
                                            Тип решения</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Выгрузить отчет</button>
                            <a href="{{ route('home') }}" class="btn btn-secondary">Сбросить фильтры</a>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-danger').forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Вы уверены?',
                    text: "Вы не сможете восстановить этот элемент!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Да, удалить!',
                    cancelButtonText: 'Отмена'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.form.submit();
                    }
                });
            });
        });

        // Initialize Tooltipster
        $('.delayed-reason').each(function () {
            var reason = $(this).data('reason');
            if (reason) {
                $(this).tooltipster({
                    content: $('<span>').text(reason),
                    theme: 'tooltipster-default',
                    maxWidth: 300,
                });
            }
        });

        // Toggle additional fields based on vulnerability_code checkbox
        document.getElementById('vulnerability_code').addEventListener('change', function () {
            var additionalFields = document.querySelector('.additional-fields');
            if (this.checked) {
                additionalFields.style.display = 'block';
            } else {
                additionalFields.style.display = 'none';
                // Uncheck additional fields when hiding them
                additionalFields.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
                    checkbox.checked = false;
                });
            }
        });
    });
</script>
@endsection