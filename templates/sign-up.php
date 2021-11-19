<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
        <li class="nav__item">
            <a href="all-lots.html"><?= esc($category['category_name']) ?></a>
        </li>
        <?php endforeach ?>
    </ul>
</nav>
<form class="form container <?= $errors !== [] ? 'form--invalid' : '' ?>" action="/sign-up.php" method="post" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= getErrorClassname($errors, 'email') ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= esc($formData['email'] ?? '') ?>">
        <span class="form__error"><?= esc($errors['email'] ?? '') ?></span>
    </div>
    <div class="form__item <?= getErrorClassname($errors, 'password') ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= esc($formData['password'] ?? '') ?>">
        <span class="form__error"><?= esc($errors['password'] ?? '') ?></span>
    </div>
    <div class="form__item <?= getErrorClassname($errors, 'name') ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= esc($formData['name'] ?? '') ?>">
        <span class="form__error"><?= esc($errors['name'] ?? '') ?></span>
    </div>
    <div class="form__item <?= getErrorClassname($errors, 'message') ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= esc($formData['message'] ?? '') ?></textarea>
        <span class="form__error"><?= esc($errors['message'] ?? '') ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
