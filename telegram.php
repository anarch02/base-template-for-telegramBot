<?php

    class TelegramBot {
        private $token;
        private $chatId;
        private $apiUrl = "https://api.telegram.org/bot";
    
        public function __construct($token, $chatId) {
            $this->token = $token;
            $this->chatId = $chatId;
        }
    
        public function sendMessage($text, $replyMarkup = null) {
            $url = $this->apiUrl . $this->token . "/sendMessage";
            $data = [
                "chat_id" => $this->chatId,
                "text" => $text,
            ];
    
            if ($replyMarkup !== null) {
                $data['reply_markup'] = json_encode($replyMarkup);
            }
    
            return $this->sendRequest($url, $data);
        }

        public function sendFile($file, $caption = null) {
            $url = $this->apiUrl . $this->token . "/sendDocument";
            $data = [
                "chat_id" => $this->chatId,
                "document" => $file,
                "caption" => $caption,
            ];
    
            return $this->sendRequest($url, $data);
        }
    
        public function sendPhoto($photo, $caption = null) {
            $url = $this->apiUrl . $this->token . "/sendPhoto";
            $data = [
                "chat_id" => $this->chatId,
                "photo" => $photo,
                "caption" => $caption,
            ];
    
            return $this->sendRequest($url, $data);
        }
    
        public function answerCallbackQuery($callbackQueryId, $text) {
            $url = $this->apiUrl . $this->token . "/answerCallbackQuery";
            $data = [
                "callback_query_id" => $callbackQueryId,
                "text" => $text,
            ];
    
            return $this->sendRequest($url, $data);
        }
    
        private function sendRequest($url, $data) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
    
            $result = curl_exec($ch);
            curl_close($ch);
    
            return $result;
        }
    }

class Button {
    private $text;
    private $callbackData;

    public function __construct($text, $callbackData) {
        $this->text = $text;
        $this->callbackData = $callbackData;
    }

    public function toArray() {
        return [
            'text' => $this->text,
            'callback_data' => $this->callbackData,
        ];
    }
}

class GroupButtons {
    private $buttons;

    public function __construct($buttons = []) {
        $this->buttons = $buttons;
    }

    public function addButton(Button $button) {
        $this->buttons[] = $button;
    }

    public function toArray() {
        $buttonArray = [];
        foreach ($this->buttons as $button) {
            $buttonArray[] = $button->toArray();
        }
        return $buttonArray;
    }

    public function send($chatId, $telegramBot) {
        $text = '';
        $replyMarkup = null;
        if (!empty($this->buttons)) {
            $text = 'Please select an option:';
            $replyMarkup = [
                'inline_keyboard' => [$this->toArray()]
            ];
        }
        $telegramBot->sendMessage($text, $replyMarkup);
    }
}

class Text {
    private $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function send($chatId, $telegramBot)
    {
        $telegramBot->sendMessage($this->text);
    }
}


