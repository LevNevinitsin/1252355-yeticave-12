<?php
$fieldsTypesNames = require __DIR__ . "/../configs/config-fields-types.php";

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

return [
    'fieldsRules' => [
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
    ],
    'queryFunction' => 'insertItem',
    'redirectLocation' => "//{$_SERVER['SERVER_NAME']}/lot.php?item_id=",
];

