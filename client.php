<?php

class mailpitClient{
    public static function getServer()
    {
        return !isset($_ENV['MAILPIT_SERVER'])?'http://127.0.0.1:8025':$_ENV['MAILPIT_SERVER'];
    }
    public static function fetch(string $url, string $method = 'GET', $bodyParams = []): string
    {
        $mailpitServer = self::getServer();
        $ch = curl_init();
        if ($bodyParams) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($bodyParams));
        }
        curl_setopt($ch, CURLOPT_URL, $mailpitServer . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public static function fetchMessages()
    {
        $output = self::fetch( '/api/v1/messages' );
        return json_decode($output, true);
    }

    public static function fetchMessage(string $messageId)
    {
        $output = self::fetch( '/api/v1/message/' . $messageId );
        if( !is_null( $output ) ){
            return json_decode($output, true);
        }else{
            return array( 'error'=>'Message Not Found' );
        }
    }

    public static function deleteMessage(array $messageId = [])
    {
        $json = ["IDs"=>$messageId];
        $mailpitServer = self::getServer();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $mailpitServer . '/api/v1/messages');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        if( count($messageId) != 0 ){
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);
        return $result;
    }
}