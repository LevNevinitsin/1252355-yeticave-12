<?php
require __DIR__ . '/initialize.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/validators.php';
    require __DIR__ . '/models/items.php';

    $minNameLength = null;
    $maxNameLength = 100;

    $categories_ids = array_column($categories, 'category_id');

    $imageMimes = ['image/jpeg', 'image/png'];
    $imageExtensions = ['jpg', 'jpeg', 'png'];
    $maxImageSize = 50000;

    $priceMinValue = 0.01;
    $priceWholeMaxLength = 8;
    $priceDecimalMaxLength = 2;

    $stepMinValue = 1;
    $stepWholeMaxLength = 8;

    $minDate = 'tomorrow';

    $fieldsRules = [
        'lot-name' => [
            ['validateRequired', ['Введите наименование лота']],
            ['validateScalar'],
            ['validateLength', [$minNameLength, $maxNameLength]],
        ],
        'category_id' => [
            ['validateRequired', ['Выберите категорию']],
            ['validateScalar'],
            ['validateInArray', [$categories_ids]],
        ],
        'description' => [
            ['validateRequired', ['Введите описание лота']],
            ['validateScalar'],
        ],
        'image' => [
            ['validateRequiredFile', ['Добавьте изображение']],
            ['validateFile', [$imageMimes, $imageExtensions, $maxImageSize]],
        ],
        'lot-rate' => [
            ['validateRequired', ['Введите начальную цену']],
            ['validateFloat'],
            ['validateNumberRange', [$priceMinValue]],
            ['validateDecimalLengths', [$priceWholeMaxLength, $priceDecimalMaxLength]],
        ],
        'lot-step' => [
            ['validateRequired', ['Введите шаг ставки']],
            ['validateInt'],
            ['validateNumberRange', [$stepMinValue]],
            ['validateDecimalLengths', [$stepWholeMaxLength]],
        ],
        'lot-date' => [
            ['validateRequired', ['Введите дату завершения торгов']],
            ['validateScalar'],
            ['validateDateFormat'],
            ['validateDateInterval', [$minDate]]
        ],
    ];

    $formData = array_merge($_POST, $_FILES);
    $errors = getFormErrors($formData, $fieldsRules);

    if (!count($errors)) {
        $formData['lot-rate'] = formatDecimalValues($formData['lot-rate']);
        $formData = moveFiles($formData, $fieldsRules);
        $itemId = insertItem($db, $formData);
        header("Location: //{$_SERVER['SERVER_NAME']}/lot.php?item_id=" . $itemId);
    }
}

echo getHtml('add-lot.php', [
    'categories' => $categories,
    'formData' => $formData ?? [],
    'errors' => $errors ?? [],
], $categories, $isAuth, $userName, 'Добавление лота');
