<ul class="lots__list">
    <?php foreach ($items as $item): ?>
    <li class="lots__item lot">
        <div class="lot__image">
            <img src="<?= esc($item['item_image']) ?>" width="350" height="260" alt="<?= esc($item['item_name']) ?>">
        </div>
        <div class="lot__info">
            <span class="lot__category"><?= esc($item['category_name']) ?></span>
            <h3 class="lot__title"><a class="text-link" href="/lot.php?item_id=<?= esc($item['item_id']) ?>"><?= esc($item['item_name']) ?></a></h3>
            <div class="lot__state">
                <div class="lot__rate">
                    <span class="lot__amount"><?= getBidsCountText($item['bids_count']) ?></span>
                    <span class="lot__cost"><?= esc(formatPrice($item['current_price'])) ?></span>
                </div>
                <?php list ($hoursCount, $minutesCount, $secondsCount) = getRemainingTime($item['item_date_expire']); ?>
                <div class="lot__timer timer <?= ($hours === '00') ? 'timer--finishing' : '' ?>">
                    <?= esc("$hoursCount:$minutesCount:$secondsCount") ?>
                </div>
            </div>
        </div>
    </li>
    <?php endforeach ?>
</ul>
