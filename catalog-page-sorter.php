<?php
// Database settings
$servername = "localhost";
$username = "root";
$password = "password";
$database = "database_name";

// Pages with the parent id's specified below, will be excluded from the sorting
$excludePages = [
    -1, // Your catalog page IDs to exclude from the sorting. Eg. catalog tabs etc. separate them by commas. - Eg. 1,2,3,4,5,6
];

$excludePages = implode(',', $excludePages);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

$catalogPage = "SELECT id, parent_id, caption, order_num FROM catalog_pages WHERE parent_id NOT IN ($excludePages) ORDER BY caption";
$pageSql = $conn->prepare($catalogPage);
$pageSql->execute();

$fetchedPages = $pageSql->fetchAll(PDO::FETCH_ASSOC);

$orderNum = 0;
foreach ($fetchedPages as $page) {
    $query = "UPDATE catalog_pages SET order_num = :orderNum WHERE id = :id";
    $sth = $conn->prepare($query);
    $sth->bindParam('orderNum', $orderNum, PDO::PARAM_INT);
    $sth->bindParam('id', $page['id'], PDO::PARAM_INT);
    $sth->execute();

    $orderNum++;
}

$conn = null;

echo "\nAll pages have now been sorted alphabetically\n";

exit();
