<?php

return [
    'accepted'             => 'Поле :attribute должно быть принято.',
    'active_url'           => 'Поле :attribute не является действительным URL.',
    'after'                => 'Поле :attribute должно быть датой после :date.',
    'after_or_equal'       => 'Поле :attribute должно быть датой после или равной :date.',
    'alpha'                => 'Поле :attribute может содержать только буквы.',
    'alpha_dash'           => 'Поле :attribute может содержать только буквы, цифры, дефисы и подчеркивания.',
    'alpha_num'            => 'Поле :attribute может содержать только буквы и цифры.',
    'array'                => 'Поле :attribute должно быть массивом.',
    'before'               => 'Поле :attribute должно быть датой до :date.',
    'before_or_equal'      => 'Поле :attribute должно быть датой до или равной :date.',
    'between'              => [
        'numeric' => 'Поле :attribute должно быть между :min и :max.',
        'file'    => 'Поле :attribute должно быть между :min и :max килобайт.',
        'string'  => 'Поле :attribute должно быть между :min и :max символами.',
        'array'   => 'Поле :attribute должно содержать между :min и :max элементами.',
    ],
    'boolean'              => 'Поле :attribute должно быть истинным или ложным.',
    'confirmed'            => 'Подтверждение поля :attribute не совпадает.',
    'date'                 => 'Поле :attribute не является действительной датой.',
    'date_equals'          => 'Поле :attribute должно быть датой, равной :date.',
    'date_format'          => 'Поле :attribute не соответствует формату :format.',
    'different'            => 'Поля :attribute и :other должны быть разными.',
    'digits'               => 'Поле :attribute должно быть :digits цифрами.',
    'digits_between'       => 'Поле :attribute должно быть между :min и :max цифрами.',
    'dimensions'           => 'Поле :attribute имеет недопустимые размеры изображения.',
    'distinct'             => 'Поле :attribute имеет повторяющееся значение.',
    'email'                => 'Поле :attribute должно быть действительным электронным адресом.',
    'ends_with'            => 'Поле :attribute должно заканчиваться одним из следующих значений: :values.',
    'exists'               => 'Выбранное значение для :attribute недействительно.',
    'file'                 => 'Поле :attribute должно быть файлом.',
    'filled'               => 'Поле :attribute должно иметь значение.',
    'gt'                   => [
        'numeric' => 'Поле :attribute должно быть больше :value.',
        'file'    => 'Поле :attribute должно быть больше :value килобайт.',
        'string'  => 'Поле :attribute должно быть больше :value символов.',
        'array'   => 'Поле :attribute должно содержать более :value элементов.',
    ],
    'gte'                  => [
        'numeric' => 'Поле :attribute должно быть больше или равно :value.',
        'file'    => 'Поле :attribute должно быть больше или равно :value килобайт.',
        'string'  => 'Поле :attribute должно быть больше или равно :value символов.',
        'array'   => 'Поле :attribute должно содержать :value элементов или более.',
    ],
    'image'                => 'Поле :attribute должно быть изображением.',
    'in'                   => 'Выбранное значение для :attribute недействительно.',
    'in_array'             => 'Поле :attribute не существует в :other.',
    'integer'              => 'Поле :attribute должно быть целым числом.',
    'ip'                   => 'Поле :attribute должно быть действительным IP-адресом.',
    'ipv4'                 => 'Поле :attribute должно быть действительным IPv4-адресом.',
    'ipv6'                 => 'Поле :attribute должно быть действительным IPv6-адресом.',
    'json'                 => 'Поле :attribute должно быть действительным JSON-строкой.',
    'lt'                   => [
        'numeric' => 'Поле :attribute должно быть меньше :value.',
        'file'    => 'Поле :attribute должно быть меньше :value килобайт.',
        'string'  => 'Поле :attribute должно быть меньше :value символов.',
        'array'   => 'Поле :attribute должно содержать менее :value элементов.',
    ],
    'lte'                  => [
        'numeric' => 'Поле :attribute должно быть меньше или равно :value.',
        'file'    => 'Поле :attribute должно быть меньше или равно :value килобайт.',
        'string'  => 'Поле :attribute должно быть меньше или равно :value символов.',
        'array'   => 'Поле :attribute не должно содержать более :value элементов.',
    ],
    'max'                  => [
        'numeric' => 'Поле :attribute не может быть больше :max.',
        'file'    => 'Поле :attribute не может быть больше :max килобайт.',
        'string'  => 'Поле :attribute не может быть больше :max символов.',
        'array'   => 'Поле :attribute не может содержать более :max элементов.',
    ],
    'mimes'                => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'mimetypes'            => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'min'                  => [
        'numeric' => 'Поле :attribute должно быть не меньше :min.',
        'file'    => 'Поле :attribute должно быть не меньше :min килобайт.',
        'string'  => 'Поле :attribute должно быть не меньше :min символов.',
        'array'   => 'Поле :attribute должно содержать не менее :min элементов.',
    ],
    'not_in'               => 'Выбранное значение для :attribute недействительно.',
    'not_regex'            => 'Формат поля :attribute недействителен.',
    'numeric'              => 'Поле :attribute должно быть числом.',
    'present'              => 'Поле :attribute должно присутствовать.',
    'regex'                => 'Формат поля :attribute недействителен.',
    'required'             => 'Поле :attribute обязательно для заполнения.',
    'required_if'          => 'Поле :attribute обязательно для заполнения, когда :other равно :value.',
    'required_unless'      => 'Поле :attribute обязательно для заполнения, если :other не равно :values.',
    'required_with'        => 'Поле :attribute обязательно для заполнения, когда :values присутствует.',
    'required_with_all'    => 'Поле :attribute обязательно для заполнения, когда :values присутствуют.',
    'required_without'     => 'Поле :attribute обязательно для заполнения, когда :values отсутствует.',
    'required_without_all' => 'Поле :attribute обязательно для заполнения, когда ни одно из :values не присутствует.',
    'same'                 => 'Поля :attribute и :other должны совпадать.',
    'size'                 => [
        'numeric' => 'Поле :attribute должно быть :size.',
        'file'    => 'Поле :attribute должно быть :size килобайт.',
        'string'  => 'Поле :attribute должно быть :size символов.',
        'array'   => 'Поле :attribute должно содержать :size элементов.',
    ],
    'starts_with'          => 'Поле :attribute должно начинаться с одного из следующих значений: :values.',
    'string'               => 'Поле :attribute должно быть строкой.',
    'timezone'             => 'Поле :attribute должно быть действительной временной зоной.',
    'unique'               => 'Такое значение поля :attribute уже существует.',
    'uploaded'             => 'Не удалось загрузить поле :attribute.',
    'url'                  => 'Формат поля :attribute недействителен.',
    'uuid'                 => 'Поле :attribute должно быть действительным UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'stamp_high_number' => [
            'unique' => 'Отчет с такими печатями уже загружен'
        ],
        'stamp_low_number' => [
            'unique' => 'Отчет с такими печатями уже загружен'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];