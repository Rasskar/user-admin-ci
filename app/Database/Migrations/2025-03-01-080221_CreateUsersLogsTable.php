<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersLogsTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'unsigned' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'action' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'model' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'model_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'old_data' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'new_data' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'ip' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => false,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'created_at'  => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users_logs');
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->forge->dropTable('users_logs');
    }
}
