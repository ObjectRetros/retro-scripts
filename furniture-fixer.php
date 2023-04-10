<?php

// Database settings
$servername = "127.0.0.1";
$username = "root";
$password = "password";
$database = "your_db_name";

$url = "https://www.habbo.com/gamedata/furnidata_json/1";

// Create a custom context with Mozilla user agent
$options = [
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n"
    ]
];

$context = stream_context_create($options);

// Fetch data from URL using the custom context
$data = file_get_contents($url, false, $context);

// Check if data is fetched successfully
if (!$data) {
    return "Error fetching data from the URL";
}

$decodedData = json_decode($data, true);

// Return error if decoding was unsuccesful
if (json_last_error() != JSON_ERROR_NONE) {
    return "Error decoding JSON data: " . json_last_error_msg();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

$roomItems = $decodedData['roomitemtypes']['furnitype'];
foreach ($roomItems as $item) {
    $width = $item['xdim'] ?? 1;
    $length = $item['ydim'] ?? 1;
    $allowSit = $item['cansiton'] ? 1 : 0;
    $allowLay = $item['canlayon'] ? 1 : 0;
    $allowWalk = $item['canstandon'] ? 1 : 0;
    $itemName = $item['classname'] ?? '';

    $stmt = $conn->prepare("UPDATE items_base SET
                                width = :width,
                                length = :length,
                                allow_sit = :allow_sit,
                                allow_lay = :allow_lay,
                                allow_walk = :allow_walk
                            WHERE item_name = :item_name");

    $stmt->bindParam(':width', $width);
    $stmt->bindParam(':length', $length);
    $stmt->bindParam(':allow_sit', $allowSit);
    $stmt->bindParam(':allow_lay', $allowLay);
    $stmt->bindParam(':allow_walk', $allowWalk);
    $stmt->bindParam(':item_name', $itemName);

    $stmt->execute();

    echo "\n" . $item['classname'] . ' has been updated';
}

echo "\n\nFinished updating furniture";
