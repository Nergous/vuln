@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Создание пользователя</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="login" class="col-md-4 col-form-label text-md-right">Логин</label>

                            <div class="col-md-6">

                                <input id="login" type="text" class="form-control @error('login') is-invalid @enderror"
                                    name="login" value="{{ old('login') }}" required autocomplete="login" autofocus>

                                @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>

                            <div class="col-md-6">

                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" name="password"
                                    required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        

                        <div class="form-group row">
                            <label for="type" class="col-md-4 col-form-label text-md-right">Тип</label>

                            <div class="col-md-6">

                                <select name="type" id="type" class="form-control">
                                    <option value="Admin">Администратор</option>
                                    <option value="Operator">Оператор</option>
                                </select>

                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">

                                <button type="submit" class="btn btn-primary">
                                    Сохранить
                                </button>

                                <a href="{{ route('users.index') }}" class="btn btn-secondary">Назад</a>

                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>