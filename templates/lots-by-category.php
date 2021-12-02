<?= includeTemplate('categories-navigation.php', ['categories' => $categories, 'categoryId' => $categoryId]) ?>
<div class="container">
    <section class="lots">
    <h2>Все лоты в категории <span>«<?= esc($categoryName) ?>»</span></h2>
    <?= includeTemplate('items-list.php', ['items' => $categoryItems])?>
    </section>
    <?php if ($pagesCount > 1): ?>
    <ul class="pagination-list">
        <?php if ($currentPage === 1): ?>
        <li class="pagination-item pagination-item-prev pagination-item-disabled"><a>Назад</a></li>
        <?php else: ?>
        <li class="pagination-item pagination-item-prev">
            <a href="/lots-by-category.php?category_id=<?= esc($categoryId) ?>&page=<?= esc($currentPage) - 1 ?>">Назад</a>
        </li>
        <?php endif ?>

        <?php foreach($pages as $page): ?>
            <?php if ($page === $currentPage): ?>
            <li class="pagination-item pagination-item-active"><a><?= esc($page) ?></a></li>
            <?php else: ?>
            <li class="pagination-item">
                <a href="/lots-by-category.php?category_id=<?= esc($categoryId) ?>&page=<?= esc($page) ?>"><?= esc($page) ?></a>
            </li>
            <?php endif ?>
        <?php endforeach ?>

        <?php if ($currentPage === $pagesCount): ?>
        <li class="pagination-item pagination-item-next pagination-item-disabled"><a>Вперед</a></li>
        <?php else: ?>
        <li class="pagination-item pagination-item-next">
            <a href="/lots-by-category.php?category_id=<?= esc($categoryId) ?>&page=<?= esc($currentPage) + 1 ?>">Вперед</a>
        </li>
        <?php endif ?>
    </ul>
    <?php endif ?>
</div>
