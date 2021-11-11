<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/models/items.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fieldsTypesNames = [
        'decimalTypeName' => 'decimal',
        'fileTypeName' => 'file',
    ];

    $minNameLength = null;
    $maxNameLength = 100;

    $categories_ids = array_column($categories, 'category_id');

    $imageMimes = ['image/jpeg', 'image/png'];
    $imageExtensions = ['jpg', 'jpeg', 'png'];
    $imageTypesText = "JPEG или PNG";
    $maxImageSize = 50000;

    $priceMinValue = 0.01;
    $priceWholeMaxLength = 8;
    $priceDecimalMaxLength = 2;

    $stepMinValue = 1;
    $stepWholeMaxLength = 8;

    $minDateInterval = 1;

    $fieldsRules = [
        'lot-name' => [
            'requiredMessage' => 'Введите наименование лота',
            'validators' => [
                [
                    'function' => 'validateLength',
                    'params' => [$minNameLength, $maxNameLength],
                ],
            ],
        ],
        'category_id' => [
            'requiredMessage' => 'Выберите категорию',
            'validators' => [
                [
                    'function' => 'validateInArray',
                    'params' => [$categories_ids],
                ],
            ],
        ],
        'description' => [
            'requiredMessage' => 'Напишите описание лота',
            'validators' => [],
        ],
        'image' => [
            'type' => $fieldsTypesNames['fileTypeName'],
            'requiredMessage' => "Добавьте изображение $imageTypesText до $maxImageSize байт",
            'validators' => [
                [
                    'function' => 'validateFile',
                    'params' => [$imageMimes, $imageExtensions, $imageTypesText, $maxImageSize],
                ],
            ],
            'uploadFolder' => 'uploads',
        ],
        'lot-rate' => [
            'type' => $fieldsTypesNames['decimalTypeName'],
            'requiredMessage' => 'Введите начальную цену',
            'validators' => [
                [
                    'function' => 'validateFloat',
                ],
                [
                    'function' => 'validateNumberRange',
                    'params' => [$priceMinValue],
                ],
                [
                    'function' => 'validateDecimalLengths',
                    'params' => [$priceWholeMaxLength, $priceDecimalMaxLength],
                ],
            ],
        ],
        'lot-step' => [
            'requiredMessage' => 'Введите шаг ставки',
            'validators' => [
                [
                    'function' => 'validateInt',
                ],
                [
                    'function' => 'validateNumberRange',
                    'params' => [$stepMinValue],
                ],
                [
                    'function' => 'validateDecimalLengths',
                    'params' => [$stepWholeMaxLength],
                ],
            ],
        ],
        'lot-date' => [
            'requiredMessage' => 'Введите дату завершения торгов',
            'validators' => [
                [
                    'function' => 'validateDateFormat',
                ],
                [
                    'function' => 'validateDateInterval',
                    'params' => [$minDateInterval],
                ],
            ],
        ],
    ];

    $formData = array_merge($_POST, $_FILES);
    $errors = getFormErrors($formData, $fieldsRules, $fieldsTypesNames);

    if (!count($errors)) {
        $formData = formatDecimalValues($formData, $fieldsRules, $fieldsTypesNames);
        $formData = moveFiles($formData, $fieldsRules, $fieldsTypesNames);
        insertItem($db, $formData);
        header("Location: //{$_SERVER['SERVER_NAME']}/lot.php?item_id=" . $db->insert_id);
    }
}

echo getHtml('add-lot.php', [
    'categories' => $categories,
    'formData' => $formData ?? [],
    'errors' => $errors ?? [],
], $categories, $isAuth, $userName, 'Добавление лота');
