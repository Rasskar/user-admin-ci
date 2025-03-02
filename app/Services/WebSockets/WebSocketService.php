<?php

namespace App\Services\WebSockets;

use CodeIgniter\CLI\CLI;
use Swoole\Table;
use Swoole\WebSocket\Server;

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

    public function start()
    {
        $this->server->on("Start", function (Server $server) {
            CLI::write("Swoole WebSocket server running on ws://{$this->host}:{$this->port}\n");
        });

        $this->server->on("Open", function (Server $server, $request) {
            CLI::write("New connection: #{$request->fd}\n");
            $this->clientsTable->set((string) $request->fd, ['fd' => $request->fd]);
        });

        $this->server->on("Message", function (Server $server, $frame) {
            CLI::write("Message from the client #{$frame->fd}: {$frame->data}\n");
        });

        $this->server->on("Close", function (Server $server, $fd) {
            CLI::write("Connection #{$fd} is closed\n");
            $this->clientsTable->del((string) $fd);
        });

        $this->server->start();
    }

    /**
     * @param string $message
     * @return void
     */
    public function broadcast(string $message): void
    {
        CLI::write("Start send message to client");

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