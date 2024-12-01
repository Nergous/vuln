@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: rgba(255, 255, 255, 1); display:flex; justify-content: space-between; align-items: center; position: sticky; top: 0;">
                    Редактировать отчет
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

                    <form action="{{ route('report.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Название</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $document->name }}" placeholder="Введите название" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date">Дата</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ $document->date->format('Y-m-d') }}" placeholder="Введите дату" required>
                            @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="number">Номер</label>
                            <input type="text" name="number" class="form-control @error('number') is-invalid @enderror" value="{{ $document->number }}" placeholder="Введите номер" required>
                            @error('number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_high_date">Печать ФИЦ дата</label>
                            <input type="date" name="stamp_high_date" class="form-control @error('stamp_high_date') is-invalid @enderror" value="{{ $document->stamp_high_date ? $document->stamp_high_date->format('Y-m-d') : '' }}" placeholder="Введите дату печати ФИЦ">
                            @error('stamp_high_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_high_number">Печать ФИЦ номер</label>
                            <input type="text" name="stamp_high_number" class="form-control @error('stamp_high_number') is-invalid @enderror" value="{{ $document->stamp_high_number }}" placeholder="Введите номер печати ФИЦ">
                            @error('stamp_high_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_low_date">Печать Обособка дата</label>
                            <input type="date" name="stamp_low_date" class="form-control @error('stamp_low_date') is-invalid @enderror" value="{{ $document->stamp_low_date ? $document->stamp_low_date->format('Y-m-d') : '' }}" placeholder="Введите дату печати Обособка">
                            @error('stamp_low_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_low_number">Печать Обособка номер</label>
                            <input type="text" name="stamp_low_number" class="form-control @error('stamp_low_number') is-invalid @enderror" value="{{ $document->stamp_low_number }}" placeholder="Введите номер печати Обособка">
                            @error('stamp_low_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="file">Файл (doc, docx, pdf)</label>
                            @if ($document->path_to_file)
                            <div>
                                <a href="{{ route('download.file', basename($document->path_to_file)) }}" target="_blank">Просмотреть текущий файл</a>
                            </div>
                            @else
                            <p>Файл не загружен</p>
                            @endif
                            <input type="file" name="file" class="form-control-file @error('file') is-invalid @enderror" accept=".doc,.docx,.pdf">
                            @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tags">Теги</label>
                            <select name="tags[]" id="tags" class="form-control select2" multiple="multiple">
                                @foreach($tags as $tag)
                                <option value="{{ $tag->name }}" {{ in_array($tag->id, $document->tags->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div id="vulnerabilities-container">
                            @foreach($document->vulnerabilities as $index => $vulnerability)
                            <div class="vulnerability-field">
                                <div class="form-group">
                                    <label for="vulnerabilities[{{ $index }}][name]">Название уязвимости / задачи</label>
                                    <input type="text" name="vulnerabilities[{{ $index }}][name]" class="form-control @error('vulnerabilities.' . $index . '.name') is-invalid @enderror" value="{{ $vulnerability->name }}" placeholder="Введите название уязвимости / задачи">
                                    @error('vulnerabilities.' . $index . '.name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="vulnerabilities[{{ $index }}][code]">Код уязвимости / задачи</label>
                                    <input type="text" name="vulnerabilities[{{ $index }}][code]" class="form-control @error('vulnerabilities.' . $index . '.code') is-invalid @enderror" value="{{ $vulnerability->code }}" placeholder="Введите код уязвимости / задачи">
                                    @error('vulnerabilities.' . $index . '.code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="vulnerabilities[{{ $index }}][software]">Программное обеспечение</label>
                                    <input type="text" name="vulnerabilities[{{ $index }}][software]" class="form-control @error('vulnerabilities.' . $index . '.software') is-invalid @enderror" value="{{ $vulnerability->software }}" placeholder="Введите программное обеспечение">
                                    @error('vulnerabilities.' . $index . '.software')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="vulnerabilities[{{ $index }}][status]">Статус</label>
                                    <select name="vulnerabilities[{{ $index }}][status]" class="form-control status-select @error('vulnerabilities.' . $index . '.status') is-invalid @enderror">
                                        @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}" {{ $vulnerability->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn mt-2 btn-primary btn-sm add-new-status">Добавить новый статус</button>
                                    <div class="new-status-input" style="display: none;">
                                        <input type="text" class="form-control" placeholder="Введите новый статус">
                                        <button type="button" class="btn btn-primary btn-sm save-new-status">Сохранить</button>
                                        <button type="button" class="btn btn-secondary btn-sm cancel-new-status">Отмена</button>
                                    </div>
                                </div>
                                <div style="display: flex; justify-content: flex-end;">
                                    <button type="button" class="btn mb-2 btn-danger remove-vulnerability">Удалить уязвимость / задачу</button>
                                </div>
                            </div>
                            @endforeach
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
    document.addEventListener('click', function(event) {
        // Обработка кнопки "Добавить новый статус"
        if (event.target.classList.contains('add-new-status')) {
            const vulnerabilityField = event.target.closest('.vulnerability-field');
            const statusSelect = vulnerabilityField.querySelector('.status-select');
            const newStatusInput = vulnerabilityField.querySelector('.new-status-input');

            statusSelect.style.display = 'none';
            event.target.style.display = 'none';
            newStatusInput.style.display = 'block';

            // Обработка кнопки "Отмена"
            newStatusInput.querySelector('.cancel-new-status').addEventListener('click', function() {
                statusSelect.style.display = 'block';
                event.target.style.display = 'block';
                newStatusInput.style.display = 'none';
            });

            // Обработка кнопки "Сохранить"
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
                            event.target.style.display = 'block';
                            newStatusInput.style.display = 'none';
                            newStatusInput.querySelector('input').value = '';
                        },
                        error: function(xhr) {
                            alert("Ошибка: " + xhr.responseText);
                        }
                    });
                }
            });
        }

        // Обработка кнопки "Удалить уязвимость"
        if (event.target.classList.contains('remove-vulnerability')) {
            const vulnerabilityField = event.target.closest('.vulnerability-field');
            vulnerabilityField.parentNode.removeChild(vulnerabilityField);
        }

        // Обработка кнопки "Добавить уязвимость"
        if (event.target.id === 'add-vulnerability') {
            const container = document.getElementById('vulnerabilities-container');
            const index = container.children.length;
            const newField = document.createElement('div');
            newField.classList.add('vulnerability-field');
            newField.innerHTML = `
                <div class="form-group">
                    <label>Название уязвимости / задачи</label>
                    <input type="text" name="vulnerabilities[${index}][name]" class="form-control" placeholder="Введите название уязвимости / задачи">
                </div>
                <div class="form-group">
                    <label>Код уязвимости / задачи</label>
                    <input type="text" name="vulnerabilities[${index}][code]" class="form-control" placeholder="Введите код уязвимости / задачи">
                </div>
                <div class="form-group">
                    <label>Программное обеспечение</label>
                    <input type="text" name="vulnerabilities[${index}][software]" class="form-control" placeholder="Введите программное обеспечение">
                </div>
                <div class="form-group">
                    <label>Статус</label>
                    <select name="vulnerabilities[${index}][status]" class="form-control status-select">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn mt-2 btn-primary btn-sm add-new-status">Добавить новый статус</button>
                    <div class="new-status-input" style="display: none;">
                        <input type="text" class="form-control" placeholder="Введите новый статус">
                        <button type="button" class="btn btn-primary btn-sm save-new-status">Сохранить</button>
                        <button type="button" class="btn btn-secondary btn-sm cancel-new-status">Отмена</button>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                <button type="button" class="btn mb-2 btn-danger remove-vulnerability">Удалить уязвимость / задачу</button>
                </div>
            `;
            container.appendChild(newField);
        }
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

    });
</script>
@endsection