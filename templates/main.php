<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
        <li class="promo__item promo__item--<?= esc($category['category_code']) ?>">
            <a class="promo__link" href="/lots-by-category.php?category_id=<?= esc($category['category_id']) ?>">
                <?= esc($category['category_name']) ?>
            </a>
        </li>
        <?php endforeach ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <?= includeTemplate('items-list.php', ['items' => $items]) ?>
</section>
