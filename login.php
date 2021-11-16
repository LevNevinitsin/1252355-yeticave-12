<?php
require __DIR__ . '/initialize.php';

if ($user) {
    header("Location: /");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/validators.php';
    require __DIR__ . '/models/users.php';

    $fieldsRules = [
        'email' => [
            ['validateRequired', ['Введите e-mail']],
            ['validateEmail'],
        ],
        'password' => [
            ['validateRequired', ['Введите пароль']],
            ['validateScalar'],
        ],
    ];

    $formData = $_POST;
    $errors = getFormErrors($formData, $fieldsRules);

    if(!count($errors)) {
        $user = getUserByEmail($db, $formData['email']);

        if ($user && password_verify($formData['password'], $user['user_password'])) {
            $_SESSION['user'] = $user;
            header("Location: /");
            exit;
        }

        $errors['auth'] = 'Неверный email или пароль';
    }
}

echo getHtml('login.php', [
    'categories' => $categories,
    'formData' => $formData ?? [],
    'errors' => $errors ?? [],
], $categories, $user, 'Вход');
