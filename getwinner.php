<?php
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/models/items.php';
require __DIR__ . '/models/bids.php';

$dsn = 'smtp://6b9bdafa23e812:307575d4205584@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login';
$transport = Transport::fromDsn($dsn);
$mailer = new Mailer($transport);

$serverName = $_SERVER['SERVER_NAME'];
$betsPageLink = "//$serverName/my-bets.php";
$expiredItemsWithoutWinners = getExpiredItemsWithoutWinners($db);

foreach ($expiredItemsWithoutWinners as $item) {
    $itemId = $item['item_id'];
    $winner = determineItemWinner($db, $itemId);

    if ($winner) {
        setItemWinner($db, $itemId, $winner['user_id']);
        $itemLink = "//$serverName/lot.php?item_id=$itemId";

        $message = new Email();
        $message->to($winner['user_email']);
        $message->from("keks@phpdemo.ru");
        $message->subject("Ваша ставка победила");
        $message->html(includeTemplate('email.php', [
            'userName' => $winner['user_name'],
            'itemLink' => $itemLink,
            'itemName' => $item['item_name'],
            'betsPageLink' => $betsPageLink,
        ]));

        $mailer->send($message);
    } else {
        setItemWinner($db, $itemId, 0);
    }
}
