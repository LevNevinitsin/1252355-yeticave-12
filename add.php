<?php
require __DIR__ . '/initialize.php';

if (!$user) {
    http_response_code(403);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/validators.php';
    require __DIR__ . '/models/items.php';

    $categories_ids = array_column($categories, 'category_id');

    $fieldsRules = [
        'lot-name' => [
            ['validateRequired', ['Введите наименование лота']],
            ['validateScalar'],
            ['validateLength', [1, 100]],
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
            ['validateFile', [['image/jpeg', 'image/png'], ['jpg', 'jpeg', 'png'], 50000]],
        ],
        'lot-rate' => [
            ['validateRequired', ['Введите начальную цену']],
            ['validateFloat'],
            ['validateNumberRange', [0.01]],
            ['validateDecimalLengths', [8, 2]],
        ],
        'lot-step' => [
            ['validateRequired', ['Введите шаг ставки']],
            ['validateInt'],
            ['validateNumberRange', [1]],
            ['validateDecimalLengths', [8]],
        ],
        'lot-date' => [
            ['validateRequired', ['Введите дату завершения торгов']],
            ['validateScalar'],
            ['validateDateFormat'],
            ['validateDateInterval', ['tomorrow']]
        ],
    ];

    $itemData = array_merge($_POST, $_FILES);
    $errors = getFormErrors($itemData, $fieldsRules);

    if (!count($errors)) {
        $itemData['lot-rate'] = formatDecimalValues($itemData['lot-rate']);
        $itemData['image']['webPath'] = moveFile($itemData['image']);
        $itemData['user_id'] = $user['user_id'];
        $itemId = insertItem($db, $itemData);
        header("Location: /lot.php?item_id=" . $itemId);
        exit;
    }
}

echo getHtml('add-lot.php', [
    'categories' => $categories,
    'itemData' => $itemData ?? [],
    'errors' => $errors ?? [],
], $categories, $user, 'Добавление лота');
