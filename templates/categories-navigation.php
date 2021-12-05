<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <?php if (($categoryId ?? null) !== $category['category_id']): ?>
            <li class="nav__item">
                <a href="/lots-by-category.php?category_id=<?= esc($category['category_id']) ?>"><?= esc($category['category_name']) ?></a>
            </li>
            <?php else: ?>
            <li class="nav__item nav__item--current">
                <a><?= esc($category['category_name']) ?></a>
            </li>
            <?php endif ?>
        <?php endforeach ?>
    </ul>
</nav>
