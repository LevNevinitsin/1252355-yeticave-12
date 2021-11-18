<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
        <li class="nav__item">
            <a href="all-lots.html"><?= esc($category['category_name']) ?></a>
        </li>
        <?php endforeach ?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= esc($searchString) ?></span>»</h2>
        <ul class="lots__list">
            <?php if ($foundItems): ?>
                <?php foreach($foundItems as $item): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= esc($item['item_image']) ?>" width="350" height="260" alt="<?= esc($item['item_name']) ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= esc($item['category_name']) ?></span>
                        <h3 class="lot__title"><a class="text-link" href="/lot.php?item_id=<?= esc($item['item_id']) ?>"><?= esc($item['item_name']) ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= esc(formatPrice($item['item_initial_price'])) ?></span>
                            </div>
                            <?php list ($hoursCount, $minutesCount, $secondsCount) = getRemainingTime($item['item_date_expire']); ?>
                            <div class="lot__timer timer">
                                <?= esc("$hoursCount:$minutesCount:$secondsCount") ?>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endforeach ?>
            <?php else: ?>
                <p><?= esc($searchMessage) ?></p>
            <?php endif ?>
        </ul>
    </section>
    <?php if ($pagesCount > 1): ?>
    <ul class="pagination-list">
        <?php if ($currentPage === 1): ?>
        <li class="pagination-item pagination-item-prev pagination-item-disabled"><a>Назад</a></li>
        <?php else: ?>
        <li class="pagination-item pagination-item-prev"><a <?= getSearchLink($searchString, $currentPage - 1) ?>>Назад</a></li>
        <?php endif ?>

        <?php foreach($pages as $page): ?>
            <?php if ($page === $currentPage): ?>
            <li class="pagination-item pagination-item-active"><a><?= $page ?></a></li>
            <?php else: ?>
            <li class="pagination-item"><a <?= getSearchLink($searchString, $page) ?>><?= $page ?></a></li>
            <?php endif ?>
        <?php endforeach ?>

        <?php if ($currentPage === $pagesCount): ?>
        <li class="pagination-item pagination-item-next pagination-item-disabled"><a>Вперед</a></li>
        <?php else: ?>
        <li class="pagination-item pagination-item-next"><a <?= getSearchLink($searchString, $currentPage + 1) ?>>Вперед</a></li>
        <?php endif ?>
    </ul>
    <?php endif ?>
</div>
