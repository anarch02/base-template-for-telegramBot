<?php
require __DIR__ . '/telegram.php';

$config = require_once 'config.php';
$token = $config['telegram']['token'];
$chatId = $config['telegram']['chat_id'];

$telegramBot = new TelegramBot($token, $chatId);

$update = json_decode(file_get_contents('php://input'), true);

if (isset($update['message']['text'])) {
    $messageText = $update['message']['text'];
    $chatId = $update['message']['chat']['id'];

    if ($messageText == '/start') {
        $text = new Text('Привет! Я бот. Чем могу помочь?');
        $text->send($chatId, $telegramBot);

        $groupButtons = new GroupButtons();

        $button1 = new Button('Кнопка 1', 'test_1');
        $groupButtons->addButton($button1);

        $button2 = new Button('Кнопка 2', 'test_2');
        $groupButtons->addButton($button2);

        
        $button3 = new Button('Кнопка 3', 'test_3');
        $groupButtons->addButton($button2);

        $groupButtons->send($chatId, $telegramBot);
    } elseif ($messageText == '/help') {
        $text = new Text('Это помощь.');
        $text->send($chatId, $telegramBot);
    } else {
        $text = new Text('Я не понимаю вашего сообщения.');
        $text->send($chatId, $telegramBot);
    }
} elseif (isset($update['callback_query'])) {
    $callbackData = $update['callback_query']['data'];
    $chatId = $update['callback_query']['message']['chat']['id'];

    if ($callbackData == 'test_1') {
        $text = new Text('Вы нажали кнопку 1.');
        $text->send($chatId, $telegramBot);
    } elseif ($callbackData == 'test_2') {
        $text = new Text('Вы нажали кнопку 2.');
        $text->send($chatId, $telegramBot);
    }
}


?>
    