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
                'username' => 'admin',
                'password' => '$2y$12$D.Ju/TZfyqx2mq.0pu.pcebxBWxN0nQx6zuxlfpqF48VvuXnOpiyG',
                'image_tokens' => 100000,
                'role' => 'Admin'
            ],
            [
                'username' => 'user',
                'password' => '$2y$12$exTQCqOyLIxAg4IE0ZCoA.7QSLXlMTdg5oiKbXRNRijqo6eXy5p1.',
                'image_tokens' => 1000,
                'role' => 'User'
            ]
        ];

        $this->table('users')->insert($users)->saveData();

        // IMAGES
        $images = [
            [
                'owner_id' => 1,
                'creator_id' => 1,
                'name' => 'TestImage',
                'description' => 'This image does not have a .png file yet. You can make a new image containing a .png by uploading a new one.',
                'price' => null,
                'is_moderated' => 0,
                'is_onsale' => 0,
                'time_created' => '2026-04-25 16:10:11',
                'alt_text' => 'This image does not have a .png file yet. You can make a new image containing a .png by uploading a new one.'
            ]
        ];

        $this->table('images')->insert($images)->saveData();
    }
}
