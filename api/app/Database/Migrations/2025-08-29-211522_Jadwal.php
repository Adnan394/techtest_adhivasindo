<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Jadwal extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'flag_color' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'comment'    => 'Warna penanda (misal: red, #ff0000)',
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal' => [
                'type' => 'DATE', // format otomatis YYYY-MM-DD
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('jadwals');
    }

    public function down()
    {
        $this->forge->dropTable('jadwals', true);
    }
}