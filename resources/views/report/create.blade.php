@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: rgba(255, 255, 255, 1); display:flex; justify-content: space-between; align-items: center; position: sticky; top: 0;">Добавить отчет
                    <a href="{{ route('home') }}" class="btn btn-warning">На главную</a>
                </div>

                <div class="card-body">

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Название</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Введите название" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date">Дата</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" placeholder="Введите дату" required>
                            @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="number">Номер</label>
                            <input type="text" name="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}" placeholder="Введите номер" required>
                            @error('number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_high_date">Печать ФИЦ дата</label>
                            <input type="date" name="stamp_high_date" class="form-control @error('stamp_high_date') is-invalid @enderror" value="{{ old('stamp_high_date') }}" placeholder="Введите дату печати ФИЦ">
                            @error('stamp_high_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_high_number">Печать ФИЦ номер</label>
                            <input type="text" name="stamp_high_number" class="form-control @error('stamp_high_number') is-invalid @enderror" value="{{ old('stamp_high_number') }}" placeholder="Введите номер печати ФИЦ">
                            @error('stamp_high_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_low_date">Печать Обособка дата</label>
                            <input type="date" name="stamp_low_date" class="form-control @error('stamp_low_date') is-invalid @enderror" value="{{ old('stamp_low_date') }}" placeholder="Введите дату печати Обособка">
                            @error('stamp_low_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_low_number">Печать Обособка номер</label>
                            <input type="text" name="stamp_low_number" class="form-control @error('stamp_low_number') is-invalid @enderror" value="{{ old('stamp_low_number') }}" placeholder="Введите номер печати Обособка">
                            @error('stamp_low_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="file">Файл (doc, docx, pdf)</label>
                            <input type="file" name="file" class="form-control-file @error('file') is-invalid @enderror" accept=".doc,.docx,.pdf">
                            @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tags">Теги</label>
                            <select name="tags[]" id="tags" class="form-control select2" multiple="multiple">
                                @foreach($tags as $tag)
                                <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="vulnerabilities-container">
                            <!-- Здесь будут добавляться новые поля для уязвимостей -->
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="submit" class="btn btn-success">Сохранить</button>
                            <button type="button" class="btn btn-info" id="add-vulnerability">Добавить уязвимость / задачу</button>
                            <a href="{{ route('home') }}" class="btn btn-warning">Назад</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let vulnerabilityIndex = 0;

    document.getElementById('add-vulnerability').addEventListener('click', function() {
        const container = document.getElementById('vulnerabilities-container');
        const vulnerabilityField = document.createElement('div');
        vulnerabilityField.className = 'vulnerability-field';
        vulnerabilityField.innerHTML = `
            <div class="form-group">
                <label for="vulnerabilities[${vulnerabilityIndex}][name]">Название уязвимости / задачи</label>
                <input type="text" name="vulnerabilities[${vulnerabilityIndex}][name]" class="form-control" placeholder="Введите название уязвимости / задачи" required>
            </div>
            <div class="form-group">
                <label for="vulnerabilities[${vulnerabilityIndex}][code]">Код уязвимости / задачи</label>
                <input type="text" name="vulnerabilities[${vulnerabilityIndex}][code]" class="form-control" placeholder="Введите код уязвимости / задачи">
            </div>
            <div class="form-group">
                <label for="vulnerabilities[${vulnerabilityIndex}][software]">Программное обеспечение</label>
                <input type="text" name="vulnerabilities[${vulnerabilityIndex}][software]" class="form-control" placeholder="Введите программное обеспечение">
            </div>
            <div class="form-group">
                <label for="vulnerabilities[${vulnerabilityIndex}][status]">Статус</label>
                <select name="vulnerabilities[${vulnerabilityIndex}][status]" class="form-control status-select" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-primary mt-2 btn-sm add-new-status">Добавить новый статус</button>
                <div class="new-status-input" style="display: none;">
                    <input type="text" class="form-control" placeholder="Введите новый статус">
                    <button type="button" class="btn btn-primary btn-sm save-new-status">Сохранить</button>
                    <button type="button" class="btn btn-secondary btn-sm cancel-new-status">Отмена</button>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end;">
            <button type="button" class="btn btn-danger mb-2 remove-vulnerability">Удалить уязвимость / задачу</button>
            </div>
        `;
        container.appendChild(vulnerabilityField);
        vulnerabilityIndex++;

        // Добавляем обработчик для кнопки удаления уязвимости
        vulnerabilityField.querySelector('.remove-vulnerability').addEventListener('click', function() {
            container.removeChild(vulnerabilityField);
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
                        _token: "{{ csrf_token() }}", // Передача CSRF-токена
                        status: newStatus
                    },
                    success: function(response) {
                        // Если запрос успешен, добавляем новый статус в select
                        const option = document.createElement('option');
                        option.value = response.id; // Используем ID статуса из ответа сервера
                        option.text = response.name;
                        statusSelect.appendChild(option);

                        // Выбираем новый статус в select
                        statusSelect.value = response.id;

                        // Скрываем поле ввода и показываем select
                        statusSelect.style.display = 'block';
                        addNewStatusButton.style.display = 'block';
                        newStatusInput.style.display = 'none';

                        // Очищаем поле ввода
                        newStatusInput.querySelector('input').value = '';
                    },
                    error: function(xhr) {
                        alert("Ошибка при добавлении статуса: " + xhr.responseText);
                    }
                });
            }
        });

    });

    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация select2 для поля тегов
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
        })
    });

    // Обработка клика по кнопке "Добавить новый тег" внутри select2
    $(document).on('click', '#addNewTagInSelect', function() {
        const newTagText = document.querySelector('.select2-search__field').value; // Получаем текст введенного тега

        if (newTagText) {
            // Отправляем запрос на создание нового тега
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
                        // Добавляем новый тег в выпадающий список
                        const option = document.createElement('option');
                        option.value = data.id;
                        option.text = newTagText;
                        option.selected = true;

                        // Обновляем select2 с новым тегом
                        $('#tags').append(option).trigger('change');

                        // Закрываем окно поиска в select2
                        $('.select2-search__field').val(''); // очищаем поле поиска
                        $('#tags').select2('close'); // закрываем select2
                    }
                });
        }
    });
</script>
@endsection