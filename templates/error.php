<?= includeTemplate('categories-navigation.php', ['categories' => $categories]) ?>
<section class="lot-item container">
    <h2><?= esc($responseCode) ?> <?= esc($title) ?></h2>
    <p><?= esc($errorText) ?></p>
</section>
