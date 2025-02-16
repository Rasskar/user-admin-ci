<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileIdToUsers extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('users', [
            'profile_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', 'profile_id');
    }
}
