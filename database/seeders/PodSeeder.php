<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Pod;
use DB;

class PodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Pod::count() > 0){
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('pods')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        Pod::create([
            'uuid' => Str::uuid()->toString(),
            'pod_name' => 'Changi Airport',
            'price' => 50,
        ]);

        Pod::create([
            'uuid' => Str::uuid()->toString(),
            'pod_name' => 'Orchard',
            'price' => 30,
        ]);

        Pod::create([
            'uuid' => Str::uuid()->toString(),
            'pod_name' => 'Anson Rd',
            'price' => 40,
        ]);

    }
}
