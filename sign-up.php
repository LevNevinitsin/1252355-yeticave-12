<?php
require __DIR__ . '/initialize.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/validators.php';
    require __DIR__ . '/models/users.php';

    $fieldsRules = [
        'email' => [
            ['validateRequired', ['Введите e-mail']],
            ['validateEmail'],
            ['validateUniqueEmail', [$db]],
        ],
        'password' => [
            ['validateRequired', ['Введите пароль']],
            ['validateScalar'],
        ],
        'name' => [
            ['validateRequired', ['Введите имя']],
            ['validateScalar'],
        ],
        'message' => [
            ['validateRequired', ['Напишите как с вами связаться']],
            ['validateScalar'],
        ],
    ];

    $formData = $_POST;
    $errors = getFormErrors($formData, $fieldsRules);

    if(!count($errors)) {
        $formData['password'] = password_hash($formData['password'], PASSWORD_DEFAULT);
        insertUser($db, $formData);
        header("Location: /"); // TODO: поменять адрес, когда появится страница для логина
    }
}

echo getHtml('sign-up.php', [
    'categories' => $categories,
    'formData' => $formData ?? [],
    'errors' => $errors ?? [],
], $categories, $isAuth, $userName, 'Регистрация');
