<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/validators.php';

$currentPage = $_GET['page'] ?? 1;

if (validateInt($currentPage) || validateNumberRange($currentPage, 1)) {
    httpError($categories, $user, 404);
}

$searchString = $_GET['search'] ?? '';

if ($searchMessage = validateScalar($searchString)) {
    $searchString = '';
} else {
    $searchString = trim($searchString);
    $searchMessage = validateLength($searchString, 3);
}

if (!$searchMessage) {
    require __DIR__ . '/models/items.php';
    $foundItemsCount = countFoundItems($db, $searchString);
    $pageItemsLimit = 9;
    $currentPage = (int) ($currentPage);
    $pagesCount = (int) ceil($foundItemsCount / $pageItemsLimit) ?: 1;

    if ($currentPage > $pagesCount) {
        httpError($categories, $user, 404);
    }

    $offset = ($currentPage - 1) * $pageItemsLimit;
    $pages = range(1, $pagesCount);
    $addressWithoutPageNumber = $_SERVER['PHP_SELF'] . '?' . getQsWithoutPageNumber($_GET);
    $foundItems = searchItems($db, $searchString, $pageItemsLimit, $offset);
    $foundItems = includeCbResultsForEachElement($foundItems, 'getRemainingTime', ['item_date_expire']);
}

echo getHtml('search.php', [
    'categories' => $categories,
    'searchString' => $searchString,
    'searchMessage' => $searchMessage ?? 'Ничего не найдено по вашему запросу',
    'foundItems' => $foundItems ?? null,
    'currentPage' => $currentPage ?? null,
    'pagesCount' => $pagesCount ?? null,
    'pages' => $pages ?? null,
    'addressWithoutPageNumber' => $addressWithoutPageNumber,
], $categories, $user, 'Результаты поиска', $searchString);
