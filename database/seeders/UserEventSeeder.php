<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 users and attach to event with id 1

        // create 10 users

        $users = \App\Models\User::factory()->count(10)->create();

        $event = \App\Models\Event::find(1);

        // attach users to event with id 1

        $users->each(function ($user) use ($event) {
            $event->users()->attach($user);
        });

        // save

        $users->each(function ($user) {
            $user->save();
        });
        
    }
}
