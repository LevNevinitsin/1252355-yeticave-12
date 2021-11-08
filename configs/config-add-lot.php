<?php
require __DIR__ . "/../models/information-schema-columns.php";
$fieldsTypesNames = require __DIR__ . "/../configs/config-fields-types.php";

$DB_TABLE_NAME = 'items';

$DB_ITEM_NAME_COLUMN = 'item_name';
$MIN_NAME_LENGTH = 1;
$nameLengthLimit = getCharacterMaxLength($db, $DB_TABLE_NAME, $DB_ITEM_NAME_COLUMN);
$maxNameLength = isset($MAX_NAME_LENGTH) ? $MAX_NAME_LENGTH : $nameLengthLimit;

$categories_ids = array_column($categories, 'category_id');

$IMAGE_MIMES = ['image/jpeg', 'image/png'];
$IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png'];
$IMAGE_TYPES_TEXT = "JPEG или PNG";
$MAX_IMAGE_SIZE = 50000;
$imageSizeInKbytes = getKilobytesValue($MAX_IMAGE_SIZE);

$DB_ITEM_PRICE_COLUMN = 'item_initial_price';
[
    'totalMaxLength' => $priceTotalMaxLength,
    'decimalMaxLength' => $priceDecimalMaxLength,
] = getDecimalMaxLengths($db, $DB_TABLE_NAME, $DB_ITEM_PRICE_COLUMN);
$priceWholeMaxLength = $priceTotalMaxLength - $priceDecimalMaxLength;

$DB_ITEM_BID_STEP_COLUMN = 'item_bid_step';
[
    'totalMaxLength' => $stepTotalMaxLength,
    'decimalMaxLength' => $stepDecimalMaxLength,
] = getDecimalMaxLengths($db, $DB_TABLE_NAME, $DB_ITEM_PRICE_COLUMN);
$stepWholeMaxLength = $stepTotalMaxLength - $stepDecimalMaxLength;

$MIN_DATE_INTERVAL = 1;

return [
    'fieldsRules' => [
        'lot-name' => [
            'requiredMessage' => 'Введите наименование лота',
            'validators' => [
                [
                    'function' => getValidateLength($MIN_NAME_LENGTH, $maxNameLength),
                ],
            ],
        ],
        'category_id' => [
            'requiredMessage' => 'Выберите категорию',
            'validators' => [
                [
                    'function' => getValidateAcceptability($categories_ids),
                    'message' => "Выберите категорию из списка",
                ],
            ],
        ],
        'description' => [
            'requiredMessage' => 'Напишите описание лота',
            'validators' => [],
        ],
        'image' => [
            'type' => $fieldsTypesNames['fileTypeName'],
            'requiredMessage' => "Добавьте изображение $IMAGE_TYPES_TEXT до {$imageSizeInKbytes}Кб",
            'validators' => [
                [
                    'function' => getValidateFile($IMAGE_MIMES, $IMAGE_EXTENSIONS, $IMAGE_TYPES_TEXT, $MAX_IMAGE_SIZE),
                ],
            ],
            'uploadFolder' => 'uploads',
        ],
        'lot-rate' => [
            'type' => $fieldsTypesNames['decimalTypeName'],
            'requiredMessage' => 'Введите начальную цену',
            'validators' => [
                [
                    'function' => getValidatePositiveNumber(),
                ],
                [
                    'function' => getValidateDecimalLengths($priceWholeMaxLength, $priceDecimalMaxLength),
                ],
            ],
        ],
        'lot-step' => [
            'requiredMessage' => 'Введите шаг ставки',
            'validators' => [
                [
                    'function' => getValidatePositiveInt(),
                ],
                [
                    'function' => getValidateDecimalLengths($stepWholeMaxLength, $stepDecimalMaxLength),
                    'message' => "До $priceWholeMaxLength знаков",
                ],
            ],
        ],
        'lot-date' => [
            'requiredMessage' => 'Введите дату завершения торгов',
            'validators' => [
                [
                    'function' => getValidateDateFormat(),
                ],
                [
                    'function' => getValidateDateInterval($MIN_DATE_INTERVAL),
                ],
            ],
        ],
    ],
    'queryFunction' => 'insertItem',
    'redirectLocation' => "//{$_SERVER['SERVER_NAME']}/lot.php?item_id=",
];

