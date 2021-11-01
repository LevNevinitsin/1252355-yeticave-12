<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
        <li class="promo__item promo__item--<?= esc($category['category_code']) ?>">
            <a class="promo__link" href="pages/all-lots.html"><?= esc($category['category_name']) ?></a>
        </li>
        <?php endforeach ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($items as $item): ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?= esc($item['item_image']) ?>" width="350" height="260" alt="<?= esc($item['item_name']) ?>">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?= esc($item['category_name']) ?></span>
                <h3 class="lot__title"><a class="text-link" href="lot.php?item_id=<?= esc($item['item_id']) ?>"><?= esc($item['item_name']) ?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost"><?= esc(formatPrice($item['item_initial_price'])) ?></span>
                    </div>
                    <?php list ($hours, $minutes) = getRemainingTime($item['item_date_expire']); ?>
                    <div class="lot__timer timer <?= ($hours === '00') ? 'timer--finishing' : '' ?>">
                        <?= esc($hours . ":" . $minutes) ?>
                    </div>
                </div>
            </div>
        </li>
        <?php endforeach ?>
    </ul>
</section>
