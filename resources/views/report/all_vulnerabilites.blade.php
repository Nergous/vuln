@extends('layouts.app')

@section('content')
<div class="col">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: rgba(255, 255, 255, 1); display:flex; justify-content: space-between; align-items: center; position: sticky; top: 0;">Все уязвимости / задачи

                    <a href="{{ route('home') }}" class="btn btn-warning mb-3">На главную</a>
                </div>


                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Название</th>
                                <th scope="col">Код</th>
                                <th scope="col">Программа</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Статус выполнения</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($document->vulnerabilities as $vulnerability)
                            <tr>
                                <th scope="row">{{ $vulnerability->id }}</th>
                                <td>{{ $vulnerability->name }}</td>
                                <td>{{ $vulnerability->code }}</td>
                                <td>{{ $vulnerability->software }}</td>
                                <td>{{ $vulnerability->status->name }}</td> <!-- Используем связь с моделью Status -->
                                <td>@if($vulnerability->complete_status === "In work") В работе
                                    @else Выполнено
                                    @endif</td>
                                <td><a href="{{ route('report.change_vulnerability', $vulnerability->id) }}"
                                        class="btn btn-sm btn-primary">Добавить решение</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('home') }}" class="btn btn-warning">Назад</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection