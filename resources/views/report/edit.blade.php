@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Редактировать отчет</div>

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
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $document->name }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date">Дата</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ $document->date->format('Y-m-d') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="number">Номер</label>
                            <input type="text" name="number" class="form-control @error('number') is-invalid @enderror" value="{{ $document->number }}" required>
                            @error('number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_high_date">Печать ФИЦ дата</label>
                            <input type="date" name="stamp_high_date" class="form-control @error('stamp_high_date') is-invalid @enderror" value="{{ $document->stamp_high_date ? $document->stamp_high_date->format('Y-m-d') : '' }}">
                            @error('stamp_high_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_high_number">Печать ФИЦ номер</label>
                            <input type="text" name="stamp_high_number" class="form-control @error('stamp_high_number') is-invalid @enderror" value="{{ $document->stamp_high_number }}">
                            @error('stamp_high_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_low_date">Печать Обособка дата</label>
                            <input type="date" name="stamp_low_date" class="form-control @error('stamp_low_date') is-invalid @enderror" value="{{ $document->stamp_low_date ? $document->stamp_low_date->format('Y-m-d') : '' }}">
                            @error('stamp_low_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_low_number">Печать Обособка номер</label>
                            <input type="text" name="stamp_low_number" class="form-control @error('stamp_low_number') is-invalid @enderror" value="{{ $document->stamp_low_number }}">
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

                        <div id="vulnerabilities-container">
                            @foreach($document->vulnerabilities as $index => $vulnerability)
                                <div class="vulnerability-field">
                                    <div class="form-group">
                                        <label for="vulnerabilities[{{ $index }}][name]">Название уязвимости</label>
                                        <input type="text" name="vulnerabilities[{{ $index }}][name]" class="form-control @error('vulnerabilities.' . $index . '.name') is-invalid @enderror" value="{{ $vulnerability->name }}">
                                        @error('vulnerabilities.' . $index . '.name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="vulnerabilities[{{ $index }}][code]">Код уязвимости</label>
                                        <input type="text" name="vulnerabilities[{{ $index }}][code]" class="form-control @error('vulnerabilities.' . $index . '.code') is-invalid @enderror" value="{{ $vulnerability->code }}">
                                        @error('vulnerabilities.' . $index . '.code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="vulnerabilities[{{ $index }}][software]">Программное обеспечение</label>
                                        <input type="text" name="vulnerabilities[{{ $index }}][software]" class="form-control @error('vulnerabilities.' . $index . '.software') is-invalid @enderror" value="{{ $vulnerability->software }}">
                                        @error('vulnerabilities.' . $index . '.software')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="vulnerabilities[{{ $index }}][status]">Статус</label>
                                        <select name="vulnerabilities[{{ $index }}][status]" class="form-control @error('vulnerabilities.' . $index . '.status') is-invalid @enderror">
                                            <option value="High" {{ $vulnerability->status == 'High' ? 'selected' : '' }}>Опасный</option>
                                            <option value="Middle" {{ $vulnerability->status == 'Middle' ? 'selected' : '' }}>Средний</option>
                                            <option value="Low" {{ $vulnerability->status == 'Low' ? 'selected' : '' }}>Низкий</option>
                                        </select>
                                        @error('vulnerabilities.' . $index . '.status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="button" class="btn mb-2 btn-danger remove-vulnerability">Удалить уязвимость</button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-secondary" id="add-vulnerability">Добавить уязвимость</button>

                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Назад</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-vulnerability').addEventListener('click', function () {
        const container = document.getElementById('vulnerabilities-container');
        const vulnerabilityField = document.createElement('div');
        vulnerabilityField.className = 'vulnerability-field';
        const index = container.children.length;
        vulnerabilityField.innerHTML = `
            <div class="form-group">
                <label for="vulnerabilities[${index}][name]">Название уязвимости</label>
                <input type="text" name="vulnerabilities[${index}][name]" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="vulnerabilities[${index}][code]">Код уязвимости</label>
                <input type="text" name="vulnerabilities[${index}][code]" class="form-control">
            </div>
            <div class="form-group">
                <label for="vulnerabilities[${index}][software]">Программное обеспечение</label>
                <input type="text" name="vulnerabilities[${index}][software]" class="form-control">
            </div>
            <div class="form-group">
                <label for="vulnerabilities[${index}][status]">Статус</label>
                <select name="vulnerabilities[${index}][status]" class="form-control" required>
                    <option value="High">Опасный</option>
                    <option value="Middle">Средний</option>
                    <option value="Low">Низкий</option>
                </select>
            </div>
            <button type="button" class="btn mb-2 btn-danger remove-vulnerability">Удалить уязвимость</button>
        `;
        container.appendChild(vulnerabilityField);

        // Добавляем обработчик для кнопки удаления уязвимости
        vulnerabilityField.querySelector('.remove-vulnerability').addEventListener('click', function () {
            container.removeChild(vulnerabilityField);
        });
    });

    // Добавляем обработчики для кнопок удаления уже существующих уязвимостей
    document.querySelectorAll('.remove-vulnerability').forEach(button => {
        button.addEventListener('click', function () {
            const vulnerabilityField = button.closest('.vulnerability-field');
            vulnerabilityField.parentNode.removeChild(vulnerabilityField);
        });
    });
</script>
@endsection