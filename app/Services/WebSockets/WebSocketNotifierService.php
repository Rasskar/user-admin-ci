<?php

namespace App\Services\WebSockets;

class WebSocketNotifierService
{
    /**
     * @param string $action
     * @param string $channel
     * @return void
     */
    public static function sendEvent(string $action, string $channel): void
    {
        $host = getenv('SWOOLE_HOST') ?: '127.0.0.1';
        $port = getenv('SWOOLE_PORT') ?: 9501;
        $protocol = getenv('APP_ENV') === 'production' ? 'https' : 'http';

        $url = "{$protocol}://{$host}:{$port}/broadcast";
        $payload = json_encode(['action' => $action, 'channel' => $channel]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // We are not waiting for an answer
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100); // Limit to 100 ms
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1); // Allows you to work in asynchronous mode
        curl_exec($ch);
        curl_close($ch);
    }
}
