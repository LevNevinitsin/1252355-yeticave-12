<?= includeTemplate('categories-navigation.php', ['categories' => $categories]) ?>
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
        <?php echo includeTemplate('pagination.php', [
            'currentPage' => $currentPage,
            'searchString' => $searchString,
            'addressWithoutPageNumber' => $addressWithoutPageNumber,
            'pages' => $pages,
            'pagesCount' => $pagesCount,
        ]) ?>
    <?php endif ?>
</div>
