<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * isDateValid('2019-01-01'); // true
 * isDateValid('2016-02-29'); // true
 * isDateValid('2019-04-31'); // false
 * isDateValid('10.10.2010'); // false
 * isDateValid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function isDateValid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function dbGetPrepareStmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     getNounPluralForm(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param   int      $number  Число, по которому вычисляем форму множественного числа
 * @param   string   $one     Форма единственного числа: яблоко, час, минута
 * @param   string   $two     Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param   string   $many    Форма множественного числа для остальных чисел
 *
 * @return  string            Рассчитанная форма множественнго числа
 */
function getNounPluralForm(int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param   string   $name  Путь к файлу шаблона относительно папки templates
 * @param   array    $data  Ассоциативный массив с данными для шаблона
 * @return  string          Итоговый HTML
 */
function includeTemplate(string $name, array $data = [])
{
    $name = __DIR__ . '/templates/' . $name;

    ob_start();
    extract($data);
    require $name;

    return ob_get_clean();
}

/**
 * Принимает цену и возвращает её в формате '12 000 ₽'
 * @param   float    $price  Цена
 * @return  string           Отформатированная цена
 */
function formatPrice(float $price): string
{
    $price = ceil($price);
    return number_format($price, 0, '', ' ') . ' ₽';
}

function getKilobytesValue(int $bytesValue): int {
    return floor($bytesValue / 1024);
}

/**
 * Принимает строку и возвращает её экранированном для HTML виде
 * @param   string|null   $text  Данные, которые хотим отобразить в HTML
 * @return  string               Экранированные данные
 */
function esc(?string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES);
}

/**
 * Принимает дату истечения лота и возвращает оставшееся до неё время
 * @param   string   $expireDate  Дата истечения лота
 * @return  array                 Оставшееся до истечения лота время в виде массива [ЧЧ, ММ]
 */
function getRemainingTime(string $expireDate): array
{
    $diff = strtotime($expireDate) - time();
    $hours = str_pad(floor($diff / 3600), 2, '0', STR_PAD_LEFT);
    $minutes = str_pad(floor(($diff % 3600) / 60), 2, '0', STR_PAD_LEFT);
    return [$hours, $minutes];
}

/**
 * Принимает шаблон страницы, данные для него и для лейаута и возвращает полный HTML страницы
 * @param   string    $pageTemplate   Путь к файлу шаблона относительно папки templates
 * @param   array     $pageData       Ассоциативный массив с данными для шаблона
 * @param   array     $categories     Ассоциативный массив с категориями товаров
 * @param   integer   $isAuth         Число 1 либо 0, отображающее статус авторизации пользователя
 * @param   string    $userName       Имя пользователя
 * @param   string    $title          Содержимое для тега <title>
 * @param   boolean   $isIndexPage    Является ли страница главной
 * @return  string                    Полный HTML страницы
 */
function getHTML(string $pageTemplate, array $pageData, array $categories, int $isAuth, string $userName, string $title, bool $isIndexPage = false): string
{
    $pageContent = includeTemplate($pageTemplate, $pageData);
    $layoutData = [
        'content' => $pageContent,
        'categories' => $categories,
        'title' => $title,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'isIndexPage' => $isIndexPage,
    ];
    return includeTemplate('layout.php', $layoutData);
}

/**
 * Передает код ошибки 404, выводит HTML для страницы 404 и завершает скрипт.
 * @param  array     $categories  Ассоциативный массив с категориями товаров
 * @param  integer   $isAuth      Число 1 либо 0, отображающее статус авторизации пользователя
 * @param  string    $userName    Имя пользователя
 */
function render404(array $categories, int $isAuth, string $userName)
{
    http_response_code(404);
    echo getHtml('404.php', ['categories' => $categories], $categories, $isAuth, $userName, 'Страница не найдена');
    exit;
}

/**
 * Получает экранированное значение поля
 * @param   string        $fieldname  Имя поля
 * @return  string|null               Экранированное значение поля (если было)
 */
function getPostVal(array $formData, string $fieldname): ?string
{
    return esc($formData[$fieldname] ?? '');
}

/**
 * Получает имя класса-модификатора для поля, если есть ошибка валидации
 * @param   array    $errors     Массив с ошибками
 * @param   string   $fieldname  Имя поля
 * @return  string               Имя класса или пустая строка, если ошибки нет
 */
function getErrorClassname(array $errors, string $fieldname): string
{
    return isset($errors[$fieldname]) ? 'form__item--invalid' : '';
}

/**
 * Получает сообщение об ошибке валидации поля
 * @param   array|null   $errors     Массив с ошибками
 * @param   string       $fieldname  Имя поля
 * @return  string                   Сообщение об ошибке или пустая строка, если ошибки нет
 */
function getErrorMessage(?array $errors, string $fieldname): string
{
    return esc($errors[$fieldname] ?? '');
}

/**
 * Валидирует значение на вхождение в массив
 * @param mixed $value Значение
 * @param array $allowedList Допустимые значения
 * @return string|null Сообщение об ошибке или null, если ошибки нет
 */
function validateInArray($value, array $allowedList): ?string
{
    $defaultMessage = "Выберите допустимое значение";

    if (!in_array($value, $allowedList)) {
        return $defaultMessage;
    }

    return null;
}

/**
 * Валидирует значение на соответствие формату даты
 * @param string $date Значение
 * @return string|null Сообщение об ошибке или null, если ошибки нет
 */
function validateDateFormat(string $date): ?string
{
    $defaultMessage = "Укажите дату в формате ГГГГ-ММ-ДД";

    if (!isDateValid($date)) {
        return $defaultMessage;
    }

    return null;
}

/**
 * Валидирует дату на указанный интервал в днях от сегодня
 * @param string $date Дата
 * @param integer $daysInterval Интервал в днях
 * @return string|null Сообщение об ошибке или null, если ошибки нет
 */
function validateDateInterval(string $date, int $daysInterval): ?string
{
    $dayWordForm = getNounPluralForm($daysInterval, 'день', 'дня', 'дней');
    $defaultMessage = "Минимум $daysInterval $dayWordForm  от сегодня";

    $dateToday = new DateTime(date("Y-m-d"));
    $dateSelected = new DateTime($date);
    $dateDiff = (int) $dateToday->diff($dateSelected)->format("%r%a");

    if ($dateDiff < $daysInterval) {
        return $defaultMessage;
    }

    return null;
}

/**
 * Валидирует строку на соответствие диапазону длины
 * @param string $value Зачение
 * @param integer|null $min Минимальная длина
 * @param integer|null $max Максимальная длина
 * @return string|null Сообщение об ошибке или null, если ошибки нет
 */
function validateLength(string $value, ?int $min, ?int $max = null): ?string
{
    $length = strlen($value);

    if ($min !== null && $length < $min) {
        $msg = "Количество символов должно быть не меньше $min";
    }

    if ($max !== null && $length > $max) {
        $msg = "Количество символов должно быть не больше $max";
    }

    return $msg ?? null;
}

/**
 * Валидирует значение на целое число
 * @param string $value Значение
 * @return string|null Сообщение об ошибке или null, если ошибки нет
 */
function validateInt(string $value): ?string
{
    $defaultMessage = "Введите целое число";

    if (filter_var($value, FILTER_VALIDATE_INT) === false) {
        return $defaultMessage;
    }

    return null;
}

/**
 * Валидирует значение на число
 * @param string $value Значение
 * @return string|null Сообщение об ошибке или null, если ошибки нет
 */
function validateFloat(string $value): ?string
{
    $defaultMessage = "Укажите число";
    $value = str_replace(',', '.', $value);

    if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
        return $defaultMessage;
    }

    return null;
}

/**
 * Валидирует значениt на максимум и минимум
 * @param string $value Значение
 * @param float|null $min Минимальное значение
 * @param float|null $max Максимальное значение
 * @return string|null Сообщение об ошибке или null, если ошибки нет
 */
function validateNumberRange(string $value, ?float $min, ?float $max = null): ?string
{
    if ($min !== null && $value < $min) {
        $msg = "Зачение должно быть больше или равно $min";
    }

    if ($max !== null && $value > $max) {
        $msg = "Зачение должно быть меньше или равно $max";
    }

    return $msg ?? null;
}

/**
 * Валидирует значение на максимальные длины частей числа
 * @param string $value Значение
 * @param integer $wholeMax Максимальная длина целой части
 * @param integer $decimalMax Максимальная длина дробной части
 * @return string|null Сообщение об ошибке или null, если ошибки нет
 */
function validateDecimalLengths(string $value, int $wholeMax, int $decimalMax = 0): ?string
{
    $digitWord = getNounPluralForm($wholeMax, 'знака', 'знаков', 'знаков');
    $defaultMessage = "Максимум {$wholeMax} {$digitWord} перед запятой";
    $pattern = "/^\d{1,$wholeMax}$/";

    if ($decimalMax > 0) {
        $defaultMessage .= " и {$decimalMax} — после";
        $pattern = "/^\d{1,$wholeMax}$|^\d{1,$wholeMax}[\.\,]\d{1,$decimalMax}$/";
    }

    if (!preg_match($pattern, $value)) {
        return $defaultMessage;
    }

    return null;
}

/**
 * Валидирует файл на MIME тип, расширение и разме
 * @param   array         $fileAtrributes   Атрибуты файла
 * @param   array         $mimeTypes        Допустимые MIME типы
 * @param   array         $extensions       Допустимые расширения
 * @param   string        $typesText        Описание допустимых форматов в текстовом виде
 * @param   integer       $maxSize          Максимальный размер в байтах
 * @return  string|null                     Сообщение об ошибке или null, если ошибки нет
 */
function validateFile(array $fileAtrributes, array $mimeTypes, array $extensions, string $typesText, int $maxSize): ?string
{
    $maxSizeInKb = getKilobytesValue($maxSize);
    $defaultMessage = "Добавьте изображение $typesText до $maxSizeInKb килобайт";

    if (!isValidFormat($fileAtrributes, $mimeTypes, $extensions) || $fileAtrributes['size'] > $maxSize) {
        return $defaultMessage;
    }

    return null;
}

/**
 * Проверяет принадлежность файла к одному из указанных MIME типов и
 * наличие у него нужного расширения.
 *
 * @param   array     $fileAttributes  Массив с атрибутами файла
 * @param   array     $mimeTypes       Допустимые MIME типы
 * @param   array     $extensions      Допустимые расширения
 * @return  boolean                    true, если у файла нужные MIME тип и расширение; иначе false
 */
function isValidFormat(array $fileAttributes, array $mimeTypes, array $extensions): bool
{
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $fileType = $finfo->file($fileAttributes['tmp_name']);
    $fileExtension = pathinfo($fileAttributes['name'], PATHINFO_EXTENSION);
    return in_array($fileType, $mimeTypes, true) && in_array($fileExtension, $extensions, true);
}

/**
 * Получает ошибку валидации поля, используя настройки из конфига
 *
 * @param   string        $name               Имя поля
 * @param   mixed         $value              Значение поля
 * @param   string|null   $requiredMessage    Сообщение на случай, если поле обязательно, но не заполнено
 * @param   bool          $isFile             Является ли файловым полем
 * @param   array|null    $validators         Массив с функциями-валидаторами
 * @return  string|null                       Текст ошибки
 */
function getFieldError(string $name, $value, ?string $requiredMessage, bool $isFile, ?array $validators): ?string
{
    if ($requiredMessage) {
        if (($isFile && empty($value['name'])) || (!$isFile && empty($value))) {
            return $requiredMessage;
        }
    }

    if ($validators) {
        foreach ($validators as $validator) {
            $params = $validator['params'] ?? [];
            $error = $validator['function']($value, ...$params);

            if ($error) {
                return $error;
            }
        }
    }

    return null;
}

/**
 * Получает ошибки валидации данных из формы, используя настройки из конфига
 * @param   array   $formData          Данные формы
 * @param   array   $fieldsRules       Правила валидации и дальнейшей обработки значений полей формы
 * @param   array   $fieldsTypesNames  Конфиг с названиями типов полей
 * @return  array                      Массив с информацией об ошибках
 */
function getFormErrors(array $formData, array $fieldsRules, array $fieldsTypesNames): array
{
    $errors = [];

    foreach ($formData as $name => $value) {
        $fieldRules = $fieldsRules[$name];
        $validators = $fieldRules['validators'] ?? null;
        $requiredMessage = $fieldRules['requiredMessage'] ?? null;
        $type = $fieldRules['type'] ?? false;
        $isFile = $type === $fieldsTypesNames['fileTypeName'];
        $errors[$name] = getFieldError($name, $value, $requiredMessage, $isFile, $validators);
    }

    return array_filter($errors);
}

/**
 * Получает имена полей указанного типа из конфига валидации формы
 * @param   array    $fieldsRules  Конфиг валидации и дальнейшей обработки значений полей формы
 * @param   string   $type         Тип поля
 * @return  array                  Поля укзанного типа
 */
function getFieldsByType(array $fieldsRules, string $type): array
{
    $wantedFields = array_filter($fieldsRules, function($fieldConfig) use ($type) {
        return ($fieldConfig['type'] ?? null) === $type;
    });

    $wantedFieldsNames = array_keys($wantedFields);
    return $wantedFieldsNames;
}

/**
 * Заменяет запятую на точку в значениях полей, принимающих дробные числа
 * @param   array   $formData          Данные формы
 * @param   array   $fieldsRules       Правила валидации и дальнейшей обработки значений полей формы
 * @param   array   $fieldsTypesNames  Конфиг с названиями типов полей
 * @return  array                      Обновлённые данные формы
 */
function formatDecimalValues(array $formData, array $fieldsRules, array $fieldsTypesNames): array
{
    $decimalTypeName = $fieldsTypesNames['decimalTypeName'];
    $decimalFieldsNames = getFieldsByType($fieldsRules, $decimalTypeName);

    foreach ($decimalFieldsNames as $decimalFieldName) {
        $formData[$decimalFieldName] = str_replace(',', '.', $formData[$decimalFieldName]);
    }

    return $formData;
}

/**
 * Генерирует имя для файла и перемещеает его в указанную папку
 * @param   array    $fileAttributes  Атрибуты файла
 * @param   string   $uploadFolder    Название папки, в которую должен быть загружен файл
 * @return  string                    Относительный путь файла
 */
function moveFile(array $fileAttributes, string $uploadFolder): string
{
    $fileName = uniqid() . "." . pathinfo($fileAttributes['name'], PATHINFO_EXTENSION);
    $fileRelativePath = "$uploadFolder/" . $fileName;
    $fileAbsolutePath = __DIR__ . "/$fileRelativePath";
    move_uploaded_file($fileAttributes['tmp_name'], $fileAbsolutePath);
    return $fileRelativePath;
}

/**
 * Перемещает добавленные через форму файлы и записывает их новый адрес в данные формы
 * @param   array   $formData          Данные формы
 * @param   array   $fieldsRules       Правила валидации и дальнейшей обработки значений полей формы
 * @param   array   $fieldsTypesNames  Конфиг с названиями типов полей
 * @return  array                      Данные формы с добавленным адресом перемещения для каждого файла
 */
function moveFiles(array $formData, array $fieldsRules, array $fieldsTypesNames): array
{
    $fileTypeName = $fieldsTypesNames['fileTypeName'];
    $fileFieldsNames = getFieldsByType($fieldsRules, $fileTypeName);

    foreach ($fileFieldsNames as $fileFieldName) {
        $fileAttributes = &$formData[$fileFieldName];
        $uploadFolder = $fieldsRules[$fileFieldName]['uploadFolder'];
        $fileAttributes['relativePath'] = moveFile($fileAttributes, $uploadFolder);
    }

    return $formData;
}
