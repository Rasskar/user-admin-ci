<?php

namespace App\Services\WebSockets;

use CodeIgniter\CLI\CLI;

class WebSocketNotifierService
{
    /**
     * @param string $action
     * @param string $channel
     * @return void
     */
    public static function sendEvent(string $action, string $channel): void
    {
        error_log("Пробуем навалить базы");

        $ws = service('websocket');

        echo "<pre>";
        var_dump($ws);
        echo "</pre>";

        $ws->broadcast(json_encode(['action' => $action, 'channel' => $channel]));

        /*error_log("Sending a WebSocket event: {$action} -> {$channel}\n");

        $swooleHost = getenv('SWOOLE_HOST') ?: '127.0.0.1';
        $swoolePort = getenv('SWOOLE_PORT') ?: 9501;

        error_log("tcp://{$swooleHost}:{$swoolePort}");

        $socket = stream_socket_client(
            "tcp://{$swooleHost}:{$swoolePort}",
            $code,
            $message,
            1
        );

        if ($socket) {
            fwrite($socket, json_encode(['action' => $action, 'channel' => $channel]));
            fclose($socket);
            error_log("Event sent to WebSocket server\n");
        } else {
            error_log("Error sending WebSocket message: $message ($code)\n");
        }*/
    }
}