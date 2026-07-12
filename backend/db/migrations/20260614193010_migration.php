<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/*
    docker compose exec postgres psql -U postgres -d postgres -c "DROP DATABASE developmentdb WITH (FORCE);"
    docker compose exec postgres psql -U postgres -d postgres -c "CREATE DATABASE developmentdb OWNER postgres;"
    docker compose exec php composer phinx migrate
    docker compose exec php composer phinx seed:run
*/

final class Migration extends AbstractMigration
{
    public function change(): void
    {
        // USERS TABLE
        $users = $this->table('users', ['id' => 'user_id']);
        $users
            ->addColumn('username', 'string', ['limit' => 255])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('image_tokens', 'integer')
            ->addColumn('role', 'string', [
                'limit' => 10,
                'default' => 'User'
            ])
            ->create();

        // IMAGES TABLE
        $images = $this->table('images', ['id' => 'image_id']);
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
            ->addForeignKey('owner_id', 'users', 'user_id', ['delete' => 'SET_NULL', 'update' => 'SET_NULL'])
            ->addForeignKey('creator_id', 'users', 'user_id', ['delete' => 'SET_NULL', 'update' => 'SET_NULL'])
            ->create();
    }
}
