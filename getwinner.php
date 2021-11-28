<?php
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/models/items.php';
require __DIR__ . '/models/bids.php';

$transport = Transport::fromDsn($config['dsn']);
$mailer = new Mailer($transport);

$serverName = $_SERVER['SERVER_NAME'];
$betsPageLink = "//$serverName/my-bets.php";
$newWinners = getNewWinners($db);

foreach ($newWinners as $newWinner) {
    $itemId = $newWinner['item_id'];
    $userId = $newWinner['user_id'];
    $wasSet = setItemWinner($db, $itemId, $userId);

    if ($wasSet) {
        $itemLink = "//$serverName/lot.php?item_id=$itemId";

        $message = new Email();
        $message->to($newWinner['user_email']);
        $message->from("keks@phpdemo.ru");
        $message->subject("Ваша ставка победила");
        $message->html(includeTemplate('email.php', [
            'userName' => $newWinner['user_name'],
            'itemLink' => $itemLink,
            'itemName' => $newWinner['item_name'],
            'betsPageLink' => $betsPageLink,
        ]));

        $mailer->send($message);
    }
}
