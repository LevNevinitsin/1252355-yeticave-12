<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
        <li class="nav__item">
            <a href="all-lots.html"><?= esc($category['category_name']) ?></a>
        </li>
        <?php endforeach ?>
    </ul>
</nav>
<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($bids as $bid): ?>
        <?php $isExpired = $bid['remainingHours'] === null ?>
        <?php $isWinner = $bid['winner_id'] === $user['user_id'] ?>
        <?php if (!$isExpired): ?>
        <tr class="rates__item">
        <?php else: ?>
        <tr class="rates__item <?= $isWinner ? 'rates__item--win' : 'rates__item--end' ?>">
        <?php endif ?>
            <td class="rates__info">
                <div class="rates__img">
                    <img src="<?= esc($bid['item_image']) ?>" width="54" height="40" alt="Сноуборд">
                </div>
                <div>
                    <h3 class="rates__title"><a href="<?= esc("/lot.php?item_id=${bid['item_id']}") ?>"><?= esc($bid['item_name']) ?></a></h3>
                    <?php if ($isExpired && $isWinner): ?>
                    <p><?= esc($bid['seller_contact_info']) ?></p>
                    <?php endif ?>
                </div>
            </td>
            <td class="rates__category">
                <?= esc($bid['category_name']) ?>
            </td>
            <td class="rates__timer">
                <?php if (!$isExpired): ?>
                <div class="timer <?= ($bid['remainingHours'] === '00') ? 'timer--finishing' : '' ?>">
                    <?= esc("{$bid['remainingHours']}:{$bid['remainingMinutes']}:{$bid['remainingSeconds']}") ?>
                </div>
                <?php elseif ($isWinner): ?>
                <div class="timer timer--win">Ставка выиграла</div>
                <?php else: ?>
                <div class="timer timer--end">Торги окончены</div>
                <?php endif ?>
            </td>
            <td class="rates__price">
                <?= esc(formatPrice($bid['bid_price'])) ?>
            </td>
            <td class="rates__time">
                <?= esc($bid['relativeTime']) ?>
            </td>
        </tr>
        <?php endforeach ?>
     </table>
</section>
