<?= includeTemplate('categories-navigation.php', ['categories' => $categories, 'categoryId' => $categoryId]) ?>
<div class="container">
    <section class="lots">
    <h2>Все лоты в категории <span>«<?= esc($categoryName) ?>»</span></h2>
    <?= includeTemplate('items-list.php', ['items' => $categoryItems])?>
    </section>
    <?php if ($pagesCount > 1): ?>
        <?php echo includeTemplate('pagination.php', [
            'pageAddress' => $pageAddress,
            'qsParameters' => $qsParameters,
            'currentPage' => $currentPage,
            'categoryId' => $categoryId,
            'pages' => $pages,
            'pagesCount' => $pagesCount,
        ]) ?>
    <?php endif ?>
</div>
