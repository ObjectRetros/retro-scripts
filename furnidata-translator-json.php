<?php

$remoteFurnidataUrl = "https://www.habbo.com/gamedata/furnidata_json/1"; // Url to the furnidata, you want to use as the translator
$localFurnidataPath = "your-absolute-path-to-your-json-furnidata"; // Path to your local stored furnidata, that you want to be translated

$backupFilename = $localFurnidataPath . '_' . time();
if (!copy($localFurnidataPath, $backupFilename)) {
    die("Failed to create a backup of the local furnidata file.");
}

// Create a custom context with Mozilla user agent
$options = [
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n"
    ]
];

$context = stream_context_create($options);

try {
    $localData = file_get_contents($localFurnidataPath);
} catch (\Exception $e) {
    return $e;
}

try {
    $remoteData = file_get_contents($remoteFurnidataUrl, false, $context);
} catch (\Exception $e) {
    return $e;
}

$localDecodedData = json_decode($localData, true);
$remoteDecodedData = json_decode($remoteData, true);

// Return error if decoding was unsuccesful
if (json_last_error() != JSON_ERROR_NONE) {
    return "Error decoding JSON data: " . json_last_error_msg();
}

$localItems = [
    'roomitemtypes' => $localDecodedData['roomitemtypes']['furnitype'],
    'wallitemtypes' => $localDecodedData['wallitemtypes']['furnitype']
];

$remoteItems = [
    'roomitemtypes' => $remoteDecodedData['roomitemtypes']['furnitype'],
    'wallitemtypes' => $remoteDecodedData['wallitemtypes']['furnitype']
];

foreach ($remoteItems as $itemType => $items) {
    foreach ($items as $remoteItem) {
        updateLocalItems($remoteItem, $localItems, $itemType);
    }
}

// Update the local furnidata with the new data
$localDecodedData['roomitemtypes']['furnitype'] = $localItems['roomitemtypes'];
$localDecodedData['wallitemtypes']['furnitype'] = $localItems['wallitemtypes'];

// Encode the updated local data as JSON
$updatedLocalData = json_encode($localDecodedData, JSON_PRETTY_PRINT);

// Save the updated local data to the file
if (file_put_contents($localFurnidataPath, $updatedLocalData) === false) {
    return "Failed to save the updated local furnidata.\n";
}


echo "\n\nFinished translating";

// Helper function
function updateLocalItems($remoteItem, &$localItems, $itemType) {
    $remoteClassname = $remoteItem['classname'] ?? '';
    $remoteName = $remoteItem['name'] ?? '';
    $remoteDescription = $remoteItem['description'] ?? '';

    // Find the index of the matching classname in the localItems array
    $index = array_search($remoteClassname, array_map(function ($item) {
        return $item['classname'] ?? '';
    }, $localItems[$itemType]));

    // Update the name and description if a match is found
    if ($index !== false) {
        $localItems[$itemType][$index]['name'] = $remoteName;
        $localItems[$itemType][$index]['description'] = $remoteDescription;

        echo "\n" . $localItems[$itemType][$index]['classname'] . ' has been updated';
    }
}
