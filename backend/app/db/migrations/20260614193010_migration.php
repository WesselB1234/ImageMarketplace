<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Migration extends AbstractMigration
{
    public function change(): void
    {
        // USERS TABLE
        $users = $this->table('Users', ['id' => 'user_id']);
        $users
            ->addColumn('username', 'string', ['limit' => 255])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('image_tokens', 'integer')
            ->addColumn('role', 'enum', ['values' => ['User', 'Admin']])
            ->create();
        // composer phinx migrate
        // mariadb -uroot -psecret123 -e "DROP DATABASE developmentdb; CREATE DATABASE developmentdb;"
        // IMAGES TABLE
        $images = $this->table('Images', ['id' => 'image_id']);
        $images
            ->addColumn('owner_id', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('creator_id', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('description', 'string', ['limit' => 255])
            ->addColumn('price', 'integer', ['null' => true])
            ->addColumn('is_moderated', 'boolean')
            ->addColumn('is_onsale', 'boolean')
            ->addColumn('time_created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('alt_text', 'text')
            ->addIndex(['owner_id'])
            ->addIndex(['creator_id'])
            ->addForeignKey('owner_id', 'Users', 'user_id', ['delete' => 'SET_NULL', 'update' => 'SET_NULL'])
            ->addForeignKey('creator_id', 'Users', 'user_id', ['delete' => 'SET_NULL', 'update' => 'SET_NULL'])
            ->create();
    }
}
