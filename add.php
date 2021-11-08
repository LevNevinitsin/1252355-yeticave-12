<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/models/items.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formConfig = require __DIR__ . '/configs/config-add-lot.php';
    $fieldsRules = $formConfig['fieldsRules'];
    $formData = array_merge($_POST, $_FILES);

    $errors = getFormErrors($formData, $fieldsRules, $fieldsTypesNames);

    if (!count($errors)) {
        $formData = formatDecimalValues($formData, $fieldsRules, $fieldsTypesNames);
        $formData = moveFiles($formData, $fieldsRules, $fieldsTypesNames);
        $queryFunction = $formConfig['queryFunction'];
        $queryFunction($db, $formData);
        header("Location: {$formConfig['redirectLocation']}" . $db->insert_id);
    }
}

echo getHtml('add-lot.php', [
    'categories' => $categories,
    'formData' => $formData ?? [],
    'errors' => $errors ?? [],
], $categories, $isAuth, $userName, 'Добавление лота');
