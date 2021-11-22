<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/models/bids.php';

if (!$user) {
    httpError($categories, $user, 403);
}

$bids = getUserBids($db, $user['user_id']);
foreach ($bids as &$bid) {
    list (
        $bid['remainingHours'],
        $bid['remainingMinutes'],
        $bid['remainingSeconds'],
    ) = getRemainingTime($bid['item_date_expire']);
    $bid['relativeTime'] = getRelativeTime($bid['bid_date_created'], 'yesterday', 'tomorrow');
}

echo getHTML('my-bets.php', [
    'categories' => $categories,
    'user' => $user,
    'bids' => $bids,
], $categories, $user, 'Мои ставки');



