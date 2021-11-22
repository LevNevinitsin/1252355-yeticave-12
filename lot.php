<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/models/items.php';
require __DIR__ . '/models/bids.php';

$itemId = $_GET['item_id'] ?? null;

if (!$itemId) {
    httpError($categories, $user, 404);
}

$item = getItem($db, $itemId);

if (!$item) {
    httpError($categories, $user, 404);
}

$bidMinimumValue = $item['current_price'] + $item['item_bid_step'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$user) {
        httpError($categories, $user, 403);
    }

    $bidData = $_POST;
    require __DIR__ . '/validators.php';

    $fieldsRules = [
        'cost' => [
            ['validateRequired'],
            ['validateInt'],
            ['validateNumberRange', [$bidMinimumValue]],
        ],
    ];

    $errors = getFormErrors($bidData, $fieldsRules);

    if (!count($errors)) {
        $userId = $user['user_id'];
        insertBid($db, $userId, $itemId, $bidData);
        header("Location: /lot.php?item_id=$itemId");
        exit;
    }
}

$bids = getItemBids($db, $itemId);
foreach ($bids as &$bid) {
    $bid['relativeTime'] = getRelativeTime($bid['bid_date_created'], 'yesterday', 'tomorrow');
}

$lastBidUserId = $bids[0]['user_id'] ?? null;

echo getHtml('lot.php', [
    'categories' => $categories,
    'user' => $user,
    'itemId' => $itemId,
    'item' => $item,
    'lastBidUserId' => $lastBidUserId,
    'bids' => $bids,
    'bidMinimumValue' => $bidMinimumValue,
    'bidData' => $bidData ?? [],
    'errors' => $errors ?? [],
], $categories, $user, $item['item_name']);
