<?php

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'DATABASE_NAME');
define('DB_USER', 'root');
define('DB_PASSWORD', 'password');
define('API_KEY', 'YOUR_API_KEY');
define('SOURCE_LANG', 'EN');
define('TARGET_LANG', 'DA');

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$captions = $pdo->query("SELECT caption FROM catalog_pages")->fetchAll(PDO::FETCH_COLUMN);

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

foreach ($captions as $caption) {
    $url = "https://api-free.deepl.com/v2/translate?auth_key=" . API_KEY . "&text=" . urlencode($caption) . "&source_lang=" . SOURCE_LANG . "&target_lang=" . TARGET_LANG;
    curl_setopt($ch, CURLOPT_URL, $url);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        continue;
    }

    $response = json_decode($response, true);
    $translatedCaption = $response['translations'][0]['text'] ?? null;

    if ($translatedCaption) {
        $stmt = $pdo->prepare("UPDATE catalog_pages SET caption = :caption WHERE caption = :oldCaption");
        $stmt->execute(['caption' => $translatedCaption, 'oldCaption' => $caption]);
        echo "Caption: $caption has been translated to: $translatedCaption\n";
    } else {
        echo "Failed to translate caption: $caption\n";
    }
}

curl_close($ch);
