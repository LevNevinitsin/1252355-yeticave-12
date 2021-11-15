<?php
/**
 * Валидирует параметр на наличие значения
 *
 * @param   mixed        $value    Параметр
 * @param   string       $message  Текст сообщения об ошибке
 * @return  string|null            Сообщение об ошибке или null, если ошибки нет
 */
function validateRequired($value, string $message = 'Обязательное поле'): ?string
{
    if ($value === null || strlen($value) === 0) {
        return $message;
    }

    return null;
}

/**
 * Валидирует параметр на наличие значения файлового аттрибута tmp_name
 * @param   mixed        $value    Параметр
 * @param   string       $message  Текст сообщения об ошибке
 * @return  string|null            Сообщение об ошибке или null, если ошибки нет
 */
function validateRequiredFile($value, string $message = 'Добавьте файл'): ?string
{
    $tmpName = $value['tmp_name'] ?? null;

    if ($tmpName === null || $tmpName === '') {
        return $message;
    }

    return null;
}

/**
 * Валидирует значение на принадлежность к скалярному типу
 * @param   mixed        $value    Значение
 * @param   string       $message  Текст сообщения об ошибке
 * @return  string|null            Сообщение об ошибке или null, если ошибки нет
 */
function validateScalar($value, string $message = 'Введите число или строку'): ?string
{
    if (!is_scalar($value)) {
        return $message;
    }

    return null;
}

/**
 * Валидирует значение на вхождение в массив
 * @param   mixed        $value        Значение
 * @param   array        $allowedList  Допустимые значения
 * @param   string       $message      Текст сообщения об ошибке
 * @return  string|null                Сообщение об ошибке или null, если ошибки нет
 */
function validateInArray($value, array $allowedList, string $message = "Выберите допустимое значение"): ?string
{
    if (!in_array($value, $allowedList)) {
        return $message;
    }

    return null;
}

/**
 * Валидирует строку на соответствие диапазону длины
 * @param   string        $value  Зачение
 * @param   integer|null  $min    Минимальная длина
 * @param   integer|null  $max    Максимальная длина
 * @return  string|null           Сообщение об ошибке или null, если ошибки нет
 */
function validateLength(string $value, ?int $min, ?int $max = null): ?string
{
    $length = mb_strlen($value);

    if ($min !== null && $length < $min) {
        return "Количество символов должно быть не меньше $min";
    }

    if ($max !== null && $length > $max) {
        return "Количество символов должно быть не больше $max";
    }

    return null;
}

/**
 * Валидирует значение на целое число
 * @param   mixed        $value    Значение
 * @param   string       $message  Текст сообщения об ошибке
 * @return  string|null            Сообщение об ошибке или null, если ошибки нет
 */
function validateInt($value, string $message = "Введите целое число"): ?string
{
    if (filter_var($value, FILTER_VALIDATE_INT) === false) {
        return $message;
    }

    return null;
}

/**
 * Валидирует значение на число
 * @param   mixed        $value    Значение
 * @param   string       $message  Текст сообщения об ошибке
 * @return  string|null            Сообщение об ошибке или null, если ошибки нет
 */
function validateFloat($value, string $message = "Введите число"): ?string
{
    $value = str_replace(',', '.', $value);

    if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
        return $message;
    }

    return null;
}

/**
 * Валидирует значение на максимум и минимум
 * @param   mixed        $value  Значение
 * @param   float|null   $min    Минимальное значение
 * @param   float|null   $max    Максимальное значение
 * @return  string|null          Сообщение об ошибке или null, если ошибки нет
 */
function validateNumberRange($value, ?float $min, ?float $max = null): ?string
{
    if ($min !== null && $value < $min) {
        return "Зачение должно быть больше или равно $min";
    }

    if ($max !== null && $value > $max) {
        return "Зачение должно быть меньше или равно $max";
    }

    return null;
}

/**
 * Валидирует значение на максимальные длины частей числа
 * @param   mixed        $value       Значение
 * @param   integer      $wholeMax    Максимальная длина целой части
 * @param   integer      $decimalMax  Максимальная длина дробной части
 * @return  string|null               Сообщение об ошибке или null, если ошибки нет
 */
function validateDecimalLengths($value, int $wholeMax, int $decimalMax = 0): ?string
{
    $digitWord = getNounPluralForm($wholeMax, 'знака', 'знаков', 'знаков');
    $message = "Максимум {$wholeMax} {$digitWord} перед запятой";
    $pattern = "/^\d{1,$wholeMax}$/";

    if ($decimalMax > 0) {
        $message .= " и {$decimalMax} — после";
        $pattern = "/^\d{1,$wholeMax}$|^\d{1,$wholeMax}[\.\,]\d{1,$decimalMax}$/";
    }

    if (!preg_match($pattern, $value)) {
        return $message;
    }

    return null;
}

/**
 * Валидирует значение на корректный email адрес
 * @param   mixed        $value    Значение
 * @param   string       $message  Текст сообщения об ошибке
 * @return  string|null            Сообщение об ошибке или null, если ошибки нет
 */
function validateEmail($value, string $message = 'Введите корректный e-mail'): ?string
{
    if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
        return $message;
    }

    return null;
}

/**
 * Валидирует email на отсутствие в таблице пользователей в базе данных
 * @param   string       $email Адрес email
 * @param   mysqli       $db Объект с базой данных
 * @param   string       $message Текст сообщения об ошибке
 * @return  string|null  Сообщение об ошибке или null, если ошибки нет
 */
function validateUniqueEmail(string $email, mysqli $db, string $message = 'Такой email уже существует'): ?string
{
    if (getUserByEmail($db, $email)) {
        return $message;
    }

    return null;
}

/**
 * Валидирует значение на соответствие формату даты
 * @param   string       $date     Значение
 * @param   string       $message  Текст сообщения об ошибке
 * @return  string|null            Сообщение об ошибке или null, если ошибки нет
 */
function validateDateFormat(string $date, string $message = "Укажите дату в формате ГГГГ-ММ-ДД"): ?string
{
    if (!isDateValid($date)) {
        return $message;
    }

    return null;
}

/**
 * Валидирует значение даты на принадлежность к диапазону
 *
 * @param   string       $date  Значение даты
 * @param   string|null  $min   Минимальная дата
 * @param   string|null  $max   Максимальная дата
 * @return  string|null         Сообщение об ошибке или null, если ошибки нет
 */
function validateDateInterval(string $date, ?string $min, ?string $max = null): ?string
{
    $selectedDate = new DateTime($date);

    if ($min !== null) {
        $minDate = new DateTime($min);

        if ($selectedDate < $minDate) {
            return "Дата должна быть не раньше {$minDate->format("Y-m-d H:i:s")}";
        }
    }

    if ($max !== null) {
        $maxDate = new DateTime($min);

        if ($selectedDate > $maxDate) {
            return "Дата должна быть не позже {$maxDate->format("Y-m-d H:i:s")}";
        }
    }

    return null;
}

/**
 * Валидирует файл на принадлежность к одному из указанных MIME типов и
 * наличие у него нужного расширения.
 *
 * @param   string       $path        Путь к файлу
 * @param   string       $name        Имя файла
 * @param   array        $mimeTypes   Допустимые MIME типы
 * @param   array        $extensions  Допустимые расширения
 * @return  string|null               Сообщение об ошибке или null, если ошибки нет
 */
function validateFileFormat(string $path, string $name, array $mimeTypes, array $extensions): ?string
{
    $typesText = implode(', ', $extensions);
    $message = "Допустимые форматы: $typesText";
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $fileType = $finfo->file($path);
    $fileExtension = pathinfo($name, PATHINFO_EXTENSION);

    if (!in_array($fileType, $mimeTypes, true) || !in_array(strtolower($fileExtension), $extensions, true)) {
        return $message;
    }

    return null;
}

/**
 * Валидирует файл на максимальный размер
 * @param   string       $path     Путь к файлу
 * @param   integer      $maxSize  Максимальный размер
 * @return  string|null            Сообщение об ошибке или null, если ошибки нет
 */
function validateFileSize(string $path, int $maxSize): ?string
{
    if (filesize($path) > $maxSize) {
        return "Размер файла не должен превышать $maxSize байт";
    }

    return null;
}

// TODO: ренейм? а то непонятно, что мы завязаны на $_FILES
/**
 * Валидирует файл на MIME тип, расширение и разме
 * @param   array        $fileAtrributes  Атрибуты файла
 * @param   array        $mimeTypes       Допустимые MIME типы
 * @param   array        $extensions      Допустимые расширения
 * @param   integer      $maxSize         Максимальный размер в байтах
 * @return  string|null                   Сообщение об ошибке или null, если ошибки нет
 */
function validateFile(array $fileAtrributes, array $mimeTypes, array $extensions, int $maxSize): ?string
{
    $path = $fileAtrributes['tmp_name'];
    $name = $fileAtrributes['name'];
    return validateFileFormat($path, $name, $mimeTypes, $extensions) ?? validateFileSize($path, $maxSize);
}

/**
 * Получает ошибку валидации поля, используя настройки из конфига
 * @param   mixed        $value       Значение поля
 * @param   array        $validators  Массив с функциями-валидаторами
 * @return  string|null               Текст ошибки
 */
function getFieldError($value, array $validators): ?string
{
    foreach ($validators as $validator) {
        $functionName = $validator[0];
        $params = $validator[1] ?? [];
        $error = $functionName($value, ...$params);

        if ($error) {
            return $error;
        }
    }

    return null;
}

/**
 * Получает ошибки валидации данных из формы, используя настройки из конфига
 * @param   array   $formData          Данные формы
 * @param   array   $fieldsRules       Правила валидации и дальнейшей обработки значений полей формы
 * @return  array                      Массив с информацией об ошибках
 */
function getFormErrors(array $formData, array $fieldsRules): array
{
    $errors = [];

    foreach ($fieldsRules as $fieldName => $validators) {
        $value = $formData[$fieldName] ?? null; // TODO: А если придет null из json'a?
        $errors[$fieldName] = getFieldError($value, $validators);
    }

    return array_filter($errors);
}
