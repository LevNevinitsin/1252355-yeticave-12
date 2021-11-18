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
        <?php if ($foundItems): ?>
        <?= includeTemplate('items-list.php', ['items' => $foundItems])?>
        <?php else: ?>
        <p><?= esc($searchMessage) ?></p>
        <?php endif ?>
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
