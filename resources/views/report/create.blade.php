@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Добавить отчет</div>

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
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date">Дата</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="number">Номер</label>
                            <input type="text" name="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}" required>
                            @error('number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_high_date">Печать ФИЦ дата</label>
                            <input type="date" name="stamp_high_date" class="form-control @error('stamp_high_date') is-invalid @enderror" value="{{ old('stamp_high_date') }}">
                            @error('stamp_high_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_high_number">Печать ФИЦ номер</label>
                            <input type="text" name="stamp_high_number" class="form-control @error('stamp_high_number') is-invalid @enderror" value="{{ old('stamp_high_number') }}">
                            @error('stamp_high_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_low_date">Печать Обособка дата</label>
                            <input type="date" name="stamp_low_date" class="form-control @error('stamp_low_date') is-invalid @enderror" value="{{ old('stamp_low_date') }}">
                            @error('stamp_low_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stamp_low_number">Печать Обособка номер</label>
                            <input type="text" name="stamp_low_number" class="form-control @error('stamp_low_number') is-invalid @enderror" value="{{ old('stamp_low_number') }}">
                            @error('stamp_low_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="file">Файл (doc, docx, pdf)</label>
                            <input type="file" name="file" class="form-control-file @error('file') is-invalid @enderror" accept=".doc,.docx,.pdf" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="vulnerabilities-container">
                            <!-- Здесь будут добавляться новые поля для уязвимостей -->
                        </div>

                        <button type="button" class="btn btn-secondary" id="add-vulnerability">Добавить
                            уязвимость</button>

                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Назад</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let vulnerabilityIndex = 0;

    document.getElementById('add-vulnerability').addEventListener('click', function () {
        const container = document.getElementById('vulnerabilities-container');
        const vulnerabilityField = document.createElement('div');
        vulnerabilityField.className = 'vulnerability-field';
        vulnerabilityField.innerHTML = `
            <div class="form-group">
                <label for="vulnerabilities[${vulnerabilityIndex}][name]">Название уязвимости</label>
                <input type="text" name="vulnerabilities[${vulnerabilityIndex}][name]" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="vulnerabilities[${vulnerabilityIndex}][code]">Код уязвимости</label>
                <input type="text" name="vulnerabilities[${vulnerabilityIndex}][code]" class="form-control" >
            </div>
            <div class="form-group">
                <label for="vulnerabilities[${vulnerabilityIndex}][software]">Программное обеспечение</label>
                <input type="text" name="vulnerabilities[${vulnerabilityIndex}][software]" class="form-control" >
            </div>
            <div class="form-group">
                <label for="vulnerabilities[${vulnerabilityIndex}][status]">Статус</label>
                <select name="vulnerabilities[${vulnerabilityIndex}][status]" class="form-control" required>
                    <option value="High">Опасный</option>
                    <option value="Middle">Средний</option>
                    <option value="Low">Низкий</option>
                </select>
            </div>
            <button type="button" class="btn btn-danger mb-2 remove-vulnerability">Удалить уязвимость</button>
        `;
        container.appendChild(vulnerabilityField);
        vulnerabilityIndex++;

        // Добавляем обработчик для кнопки удаления уязвимости
        vulnerabilityField.querySelector('.remove-vulnerability').addEventListener('click', function () {
            container.removeChild(vulnerabilityField);
        });
    });
</script>
@endsection