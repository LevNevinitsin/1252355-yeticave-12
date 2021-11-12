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
 * Заменяет запятую на точку в значении
 *
 * @param   string   $value  Исходное значение
 * @return  string           Значение с заменой
 */
function formatDecimalValues(string $value): string
{
    return str_replace(',', '.', $value);
}

/**
 * Генерирует имя для файла и перемещеает его в указанную папку
 * @param   array   $fileAttributes  Атрибуты файла
 * @param   string  $uploadFolder    Название папки, в которую должен быть загружен файл
 * @return  string                   Относительный путь файла
 */
function moveFile(array $fileAttributes, string $uploadFolder = 'uploads'): string
{
    $fileName = uniqid() . "." . pathinfo($fileAttributes['name'], PATHINFO_EXTENSION);
    $fileRelativePath = "$uploadFolder/" . $fileName;
    $fileAbsolutePath = __DIR__ . "/$fileRelativePath";
    move_uploaded_file($fileAttributes['tmp_name'], $fileAbsolutePath);
    return $fileRelativePath;
}

/**
 * Перемещает добавленные через форму файлы и записывает их новый адрес в данные формы
 * @param   array  $formData  Данные формы
 * @return  array             Данные формы с добавленным адресом перемещения для каждого файла
 */
function moveFiles(array $formData): array
{
    foreach ($formData as $fieldname => $value) {
        if (isset($value['tmp_name'])) {
            $formData[$fieldname]['relativePath'] = moveFile($value);
        }
    }

    return $formData;
}
