@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: rgba(255, 255, 255, 1); display:flex; justify-content: space-between; align-items: center; position: sticky; top: 0;">
                    <div style="display: flex; justify-content: space-between; gap: 10px;">
                        <a href="{{ route('home') }}" class="btn btn-warning">На главную</a>
                    </div>
                </div>

                <div class="card-body">
                    <h1>Выгрузка отчета</h1>

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
                                <label class="mr-2"><input type="checkbox" name="fields[]" value="delayed_reason" checked> Причина отложки</label>
                            </div>
                            <div class="form-check m-3 w-100 justify-content-start">
                                <label class="mr-2"><input type="checkbox" name="fields[]" value="vulnerability_code" id="vulnerability_code"> Код уязвимости / задачи</label>
                            </div>
                            <div class="additional-fields" style="display: none;">
                                <div class="form-check m-3 w-100 justify-content-start">
                                    <label class="mr-2"><input type="checkbox" name="fields[]" value="vulnerability_name"> Название уязвимости / задачи</label>
                                </div>
                                <div class="form-check m-3 w-100 justify-content-start">
                                    <label class="mr-2"><input type="checkbox" name="fields[]" value="real_solutions"> Реальные решения</label> <!-- Переименованный чекбокс -->
                                </div>
                                <div class="form-check m-3 w-100 justify-content-start">
                                    <label class="mr-2"><input type="checkbox" name="fields[]" value="compensating_solutions"> Компенсирующие решения</label> <!-- Новый чекбокс -->
                                </div>
                            </div>
                        </div>

                        <div class="form-check m-3 w-100 justify-content-start">
                            <label class="mr-2"><input type="checkbox" name="only_incomplete"> Вывести только незавершенные</label>
                        </div>
                        <div class="form-group mr-3">
                            <label for="filter_status" class="mr-2">Статус документа:</label>
                            <select name="filter_status" id="filter_status" class="form-control">
                                <option value="">Все</option>
                                <option value="Completed">Завершенный</option>
                                <option value="Delayed">Отложен</option>
                                <option value="In work">В работе</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Выгрузить отчет</button>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Сбросить фильтры</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle additional fields based on vulnerability_code checkbox
        document.getElementById('vulnerability_code').addEventListener('change', function() {
            var additionalFields = document.querySelector('.additional-fields');
            if (this.checked) {
                additionalFields.style.display = 'block';
            } else {
                additionalFields.style.display = 'none';
                // Uncheck additional fields when hiding them
                additionalFields.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        });
    });
</script>
@endsection