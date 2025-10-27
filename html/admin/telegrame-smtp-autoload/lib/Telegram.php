<?php

class Telegram
{
    static function command($id, $text) {
        $type = strtolower(__CLASS__);
        $result = Helper::processCommand($type, $id, $text);
        if ($result) {
            self::sendMessage($id, null, $result);
        }
    }
    
    static function sendMessage($chatId, $subject, $message, $attachment=null) {
        if ((int)$chatId == 0 && IsNullString($chatId)) {
            return;
        }
        
        $text = "<b>[{$subject}]</b>\n{$message}";
        if (IsNullString($subject)) $text = $message;
        if ($attachment) {
            $file = GetStrVal('name', $attachment);
            $size = GetIntVal('size', $attachment);
            $text .= "\n * 첨부파일 : {$file} ({$size} bytes)\n * 첨부파일은 이메일에서 확인 가능합니다.";
        }

        $datas = [];
        $datas['chat_id'] = $chatId;
        $datas['text'] = $text;
        $datas['parse_mode'] = 'HTML';
        return self::run('sendMessage', $datas);
    }

    //=================================================================================================================
    
    //static function getMe() { return self::run('getMe'); }
    static function getWebhookInfo() { return self::run('getWebhookInfo'); }
    static function setWebhook() { return self::run('setWebhook', ['url'=>GetReqVal('url')]); }
    static function deleteWebhook() { return self::run('deleteWebhook'); }

    static private function getApiUrl() {
        return 'https://api.telegram.org/bot'.Config::$TELEGRAM_TOKEN.'/';
    }
    static private function run($cmd, $datas=null) {
        $postData = null;
        if ($datas) {
            $postData = http_build_query($datas);
        }
        return CallURL(self::getApiUrl().$cmd, null, $postData);
    }
}
