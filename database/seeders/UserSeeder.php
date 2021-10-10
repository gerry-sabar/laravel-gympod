<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() > 0){
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('users')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        User::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'John Doe',
            'email' => 'testing@testing.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'Billy Evans',
            'email' => 'billy@evans.com',
            'password' => bcrypt('password'),
        ]);
    }
}
