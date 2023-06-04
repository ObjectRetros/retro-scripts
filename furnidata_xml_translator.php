<?php 

$remoteFurnidata = "https://www.habbo.com/gamedata/furnidata_xml/1";
$localFurnidata = "YOUR-ABSOLUTE-PATH";

// Create a custom context with Mozilla user agent
$options = [
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n"
    ]
];

$context = stream_context_create($options);

// Fetch data from URL using the custom context
$remoteContent = file_get_contents($remoteFurnidata, false, $context);

// Check if data is fetched successfully
if (!$remoteContent) {
    return "Error fetching data from the URL";
}

$remoteXml = new SimpleXMLElement($remoteContent);

// Fetch data from URL using the custom context
$localContent = file_get_contents($localFurnidata, false);
// Check if data is fetched successfully
if (!$localContent) {
    return "Error fetching the local furnidata";
}

$localXml = new SimpleXMLElement($localContent);

// Create a backup of the local furnidata
$backupFilename = $localFurnidata . "_" . time() . "_backup";
file_put_contents($backupFilename, $localContent);

$types = ['roomitemtypes', 'wallitemtypes'];

foreach ($types as $type) {
    $remoteItems = $remoteXml->$type->furnitype;
    $localItems = $localXml->$type->furnitype;

    $remoteData = [];
    foreach ($remoteItems as $remoteItem) {
        $classname = (string) $remoteItem['classname'];
        $remoteData[$classname] = [
            'name' => (string) $remoteItem->name,
            'description' => (string) $remoteItem->description,
        ];
    }

    foreach ($localItems as $localItem) {
        $classname = (string) $localItem['classname'];

        if (isset($remoteData[$classname])) {
            $localItem->name = $remoteData[$classname]['name'];
            $localItem->description = $remoteData[$classname]['description'];
        }
    }
}

$updatedLocalContent = $localXml->asXML();
file_put_contents($localFurnidata, $updatedLocalContent);

echo "Furniture xml data translated successfully!";