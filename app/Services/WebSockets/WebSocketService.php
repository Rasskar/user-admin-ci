<?php

namespace App\Services\WebSockets;

use Swoole\WebSocket\Server;

class WebSocketService
{
    protected Server $server;
    protected string $host;
    protected int $port;

    public function __construct()
    {
        $this->host = getenv('SWOOLE_HOST') ?: '0.0.0.0';
        $this->port = getenv('SWOOLE_PORT') ?: 9501;
        $this->server = new Server($this->host, $this->port);
    }

    /**
     * Запуск сервера
     */
    public function start()
    {
        $this->server->on("Start", function (Server $server) {
            echo "✅ Swoole WebSocket-сервер запущен на ws://{$this->host}:{$this->port}\n";
        });

        $this->server->on("Open", function (Server $server, $request) {
            echo "🔗 Новое подключение: #{$request->fd}\n";
        });

        $this->server->on("Message", function (Server $server, $frame) {
            echo "📩 Сообщение от #{$frame->fd}: {$frame->data}\n";
            foreach ($server->connections as $fd) {
                $server->push($fd, "Эхо: " . $frame->data);
            }
        });

        $this->server->on("Close", function (Server $server, $fd) {
            echo "❌ Подключение #{$fd} закрыто\n";
        });

        // Запуск сервера
        $this->server->start();
    }
}