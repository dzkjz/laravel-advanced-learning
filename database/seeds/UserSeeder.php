<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('users')->insert([
            'name' => \Illuminate\Support\Str::random(10),
            'email' => \Illuminate\Support\Str::random(10) . '@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        //上面这个太复杂，建议使用模型工厂方法
        // For example, let's create 50 users and attach a relationship to each user:
        factory(\App\Models\User::class, 50)
            ->create()
            ->each(function ($user) {
//                $user->posts()->save(factory(\App\Models\Post::class)->make());
            });
    }
}
