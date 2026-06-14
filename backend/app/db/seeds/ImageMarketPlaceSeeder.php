<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class ImageMarketPlaceSeeder extends AbstractSeed
{
    public function run(): void
    {
        // USERS
        $users = [
            [
                'user_id' => 1,
                'username' => 'admin',
                'password' => '$2y$12$D.Ju/TZfyqx2mq.0pu.pcebxBWxN0nQx6zuxlfpqF48VvuXnOpiyG',
                'image_tokens' => 100000,
                'role' => 'Admin'
            ],
            [
                'user_id' => 2,
                'username' => 'user',
                'password' => '$2y$12$exTQCqOyLIxAg4IE0ZCoA.7QSLXlMTdg5oiKbXRNRijqo6eXy5p1.',
                'image_tokens' => 1000,
                'role' => 'User'
            ]
        ];

        $this->table('Users')->insert($users)->saveData();

        // IMAGES
        $images = [
            [
                'image_id' => 1,
                'owner_id' => 1,
                'creator_id' => 1,
                'name' => 'TestImage',
                'description' => 'TestImage',
                'price' => null,
                'is_moderated' => 0,
                'is_onsale' => 0,
                'time_created' => '2026-04-25 16:10:11',
                'alt_text' => 'TestImage alt text'
            ]
        ];

        $this->table('Images')->insert($images)->saveData();
    }
}
