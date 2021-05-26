<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users    = User::factory(4)->create();
        $chat     = Chat::factory(2)->create();
        $messages = Message::factory(10)->create();

        $chat->each(fn($chat) => $chat->users()->sync($users));


    }
}
