@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Пользователи</div>

                <div class="card-body">

                    <a href="{{ route('users.create') }}" class="btn btn-primary">Создать пользователя</a>
                    <a href="{{ route('home')}}" class="btn btn-primary">Назад</a>

                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th>Логин</th>
                                <th>Тип</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->login }}</td>
                                    <td>{{ $user->type }}</td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="btn btn-sm btn-warning">Редактировать</a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                        </form>
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
@endSection