<?php

$token = getenv('TELEGRAM_BOT_TOKEN') ?: '';
$chatId = getenv('TELEGRAM_CHAT_ID') ?: '';
$message = $argv[1] ?? '';

if ($token === '' || $chatId === '' || $message === '') {
    fwrite(STDERR, "Usage: TELEGRAM_BOT_TOKEN=... TELEGRAM_CHAT_ID=... php scripts/telegram_notify.php \"message\"\n");
    exit(1);
}

$payload = http_build_query([
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'HTML'
]);

$url = "https://api.telegram.org/bot{$token}/sendMessage";

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $payload,
        'timeout' => 10
    ]
]);

$result = @file_get_contents($url, false, $context);
if ($result === false) {
    fwrite(STDERR, "Failed to send message\n");
    exit(2);
}

echo $result;
