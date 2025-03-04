<?php

namespace App\Services\WebSockets;

use CodeIgniter\CLI\CLI;
use Swoole\WebSocket\Server;
use Swoole\Table;

class WebSocketService
{
    /**
     * @var Server
     */
    protected Server $server;

    /**
     * @var Table
     */
    protected Table $clientsTable;

    /**
     * @var string
     */
    protected string $host;

    /**
     * @var int
     */
    protected int $port;

    public function __construct()
    {
        $this->host = getenv('SWOOLE_HOST') ?: '0.0.0.0';
        $this->port = getenv('SWOOLE_PORT') ?: 9501;

        $this->clientsTable = new Table(1024);
        $this->clientsTable->column('fd', Table::TYPE_INT);
        $this->clientsTable->create();

        $this->server = new Server($this->host, $this->port);
    }

    /**
     * @return void
     */
    public function start(): void
    {
        CLI::write("WebSocket + HTTP API: ws://{$this->host}:{$this->port}");

        $this->server->on("Open", function (Server $server, $request) {
            CLI::write("WebSocket connection: #{$request->fd}");
            $this->clientsTable->set((string) $request->fd, ['fd' => $request->fd]);
        });

        $this->server->on("Message", function (Server $server, $frame) {
            CLI::write("Message from the client #{$frame->fd}: {$frame->data}");
        });

        $this->server->on("Close", function (Server $server, $fd) {
            CLI::write("Client disabled: #{$fd}");
            $this->clientsTable->del((string) $fd);
        });

        $this->server->on("Request", function ($request, $response) {
            if ($request->server['request_uri'] === '/broadcast') {
                CLI::write("HTTP Request to /broadcast");

                if ($request->server['request_method'] == 'POST') {
                    $this->broadcast($request->rawContent());
                } else {
                    CLI::write("Method Not Allowed", 'red');
                }
            }
        });

        $this->server->start();
    }

    /**
     * @param string $message
     * @return void
     */
    public function broadcast(string $message): void
    {
        CLI::write("Sending a message: $message");

        foreach ($this->clientsTable as $client) {
            if ($this->server->isEstablished($client['fd'])) {
                $this->server->push($client['fd'], $message);
                CLI::write("Sent to client #{$client['fd']}");
            } else {
                $this->clientsTable->del((string) $client['fd']);
                CLI::write("Unavailable client removed #{$client['fd']}");
            }
        }
    }
}
