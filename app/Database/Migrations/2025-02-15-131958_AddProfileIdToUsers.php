<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileIdToUsers extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('users', [
            'profile_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        $this->forge->addForeignKey('profile_id', 'profiles', 'id', 'CASCADE', 'CASCADE');
    }

    public function down(): void
    {
        $this->forge->dropForeignKey('users', 'users_profile_id_foreign');
        $this->forge->dropColumn('users', 'profile_id');
    }
}
