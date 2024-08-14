@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Все уязвимости
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
                                    <td>@if ($vulnerability->status == "High") Опасный
                                    @elseif ($vulnerability->status == "Middle") Средний 
                                    @elseif ($vulnerability->status == "Low") Низкий
                                    @endif
                                    </td>
                                    <td>@if($vulnerability->complete_status === "In work") В работе 
                                        @else Выполнено 
                                        @endif</td>
                                    <td><a href="{{ route('report.change_vulnerability', $vulnerability->id) }}"
                                            class="btn btn-sm btn-warning">Добавить решение</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('home') }}" class="btn btn-secondary">Назад</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection