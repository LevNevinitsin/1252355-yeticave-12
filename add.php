<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/models/items.php';

$categories_ids = array_column($categories, 'category_id');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = getFieldsErrors($categories_ids);
    $fileError = getFileError('image');

    if ($fileError) {
        $errors['image'] = $fileError;
    }

    if (!count($errors)) {
        $imageAttributes = $_FILES['image'];
        $imagePath = 'uploads/' . uniqid() . "." . pathinfo($imageAttributes['name'], PATHINFO_EXTENSION);
        move_uploaded_file($imageAttributes['tmp_name'], $imagePath);
        insertItem($db, $imagePath);
        header("Location: lot.php?item_id=" . $db->insert_id);
    } else {
        echo getHtml('add-lot.php', ['errors' => $errors, 'categories' => $categories], $categories, $isAuth, $userName, 'Добавление лота');
    }
} else {
    echo getHtml('add-lot.php', ['errors' => [], 'categories' => $categories], $categories, $isAuth, $userName, 'Добавление лота');
}
