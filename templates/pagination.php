<ul class="pagination-list">
    <?php if ($currentPage === 1): ?>
    <li class="pagination-item pagination-item-prev pagination-item-disabled"><a>Назад</a></li>
    <?php else: ?>
    <li class="pagination-item pagination-item-prev">
        <a href="<?= esc($addressWithoutPageNumber . ($currentPage - 1)) ?>">Назад</a>
    </li>
    <?php endif ?>

    <?php foreach($pages as $page): ?>
        <?php if ($page === $currentPage): ?>
        <li class="pagination-item pagination-item-active"><a><?= esc($page) ?></a></li>
        <?php else: ?>
        <li class="pagination-item">
            <a href="<?= esc($addressWithoutPageNumber . $page) ?>"><?= esc($page) ?></a>
        </li>
        <?php endif ?>
    <?php endforeach ?>

    <?php if ($currentPage === $pagesCount): ?>
    <li class="pagination-item pagination-item-next pagination-item-disabled"><a>Вперед</a></li>
    <?php else: ?>
    <li class="pagination-item pagination-item-next">
        <a href="<?= esc($addressWithoutPageNumber . ($currentPage + 1)) ?>">Вперед</a>
    </li>
    <?php endif ?>
</ul>
