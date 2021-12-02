<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/validators.php';

$categoryId = $_GET['category_id'] ?? null;
$allowedCategoriesIds = array_map(function($category) { return $category['category_id']; }, $categories);
$currentPage = $_GET['page'] ?? 1;

if (
    validateInArray($categoryId, $allowedCategoriesIds)
    || validateInt($currentPage)
    || validateNumberRange($currentPage, 1)
) {
    httpError($categories, $user, 404);
}

require __DIR__ . '/models/items.php';
$categoryItemsCount = countCategoryItems($db, $categoryId);

$pageItemsLimit = 9;
$currentPage = (int) ($currentPage);
$pagesCount = (int) ceil($categoryItemsCount / $pageItemsLimit) ?: 1;

if ($currentPage > $pagesCount) {
    httpError($categories, $user, 404);
}

$offset = ($currentPage - 1) * $pageItemsLimit;
$pages = range(1, $pagesCount);
$addressWithoutPageNumber = $_SERVER['PHP_SELF'] . '?' . getQsWithoutPageNumber($_GET);

$categoryItems = getItemsByCategory($db, $categoryId, $pageItemsLimit, $offset);
$categoryItems = includeCbResultsForEachElement($categoryItems, 'getRemainingTime', ['item_date_expire']);

$categoryData = array_merge(...array_filter($categories, function($category) use ($categoryId) {
    return $category['category_id'] === $categoryId;
}));
$categoryName = $categoryData['category_name'];

echo getHtml('lots-by-category.php', [
    'categories' => $categories,
    'categoryId' => $categoryId,
    'categoryItems' => $categoryItems,
    'categoryName' => $categoryName,
    'pagesCount' => $pagesCount,
    'currentPage' => $currentPage,
    'pages' => $pages,
    'addressWithoutPageNumber' => $addressWithoutPageNumber,
], $categories, $user, $categoryName);
