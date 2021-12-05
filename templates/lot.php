<?= includeTemplate('categories-navigation.php', ['categories' => $categories]) ?>
<section class="lot-item container">
    <h2><?= esc($item['item_name']) ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= esc($item['item_image']) ?>" width="730" height="548" alt="<?= esc($item['item_name']) ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= esc($item['category_name']) ?></span></p>
            <p class="lot-item__description"><?= esc($item['item_description']) ?></p>
        </div>
        <div class="lot-item__right">
            <?php if ($isAllowedToBet): ?>
            <div class="lot-item__state">
                <div class="lot-item__timer timer <?= ($item['remainingHours'] === '00') ? 'timer--finishing' : '' ?>">
                    <?= esc("{$item['remainingHours']}:{$item['remainingMinutes']}") ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= esc(formatPrice($item['current_price'])) ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= esc(formatPrice($bidMinimumValue))?></span>
                    </div>
                </div>
                <form class="lot-item__form" action="<?= "/lot.php?item_id=$itemId" ?>" method="post" autocomplete="off">
                    <p class="lot-item__form-item form__item <?= getErrorClassname($errors, 'cost') ?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" placeholder="12 000" value="<?= esc($bidData['cost'] ?? '') ?>">
                        <span class="form__error"><?= esc($errors['cost'] ?? '') ?></span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            </div>
            <?php endif ?>
            <?php if ($bids): ?>
            <div class="history">
                <h3>История ставок (<span><?= esc(count($bids)) ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($bids as $bid): ?>
                    <tr class="history__item">
                        <td class="history__name"><?= esc($bid['user_name']) ?></td>
                        <td class="history__price"><?= esc(formatPrice($bid['bid_price'])) ?></td>
                        <td class="history__time"><?= esc($bid['relativeTime']) ?></td>
                    </tr>
                    <?php endforeach ?>
                </table>
            </div>
            <?php endif ?>
        </div>
    </div>
</section>
