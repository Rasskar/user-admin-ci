<?php

namespace App\Commands;

use App\Controllers\WebSocket\WebSocketController;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SwooleStart extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'swoole';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'swoole:start';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Starting the Swoole WebSocket server.';

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Starting the Swoole WebSocket server...', 'green');

        $server = new WebSocketController();
        $server->start();
    }
}
