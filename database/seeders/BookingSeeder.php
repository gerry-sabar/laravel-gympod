<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Booking;
use DB;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Booking::count() > 0){
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('pods')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        Booking::create([
            'uuid' => Str::uuid()->toString(),
            'user_id' => 1,
            'pod_id' => 1,
            'status' => 'Active',
            'phone' => '1234567',
            'booking_datetime' => date('Y-m-d H:i:s'),
        ]);
        
        Booking::create([
            'uuid' => Str::uuid()->toString(),
            'user_id' => 1,
            'pod_id' => 1,
            'status' => 'Pending',
            'phone' => '19874646',
            'booking_datetime' => date('Y-m-d H:i:s', strtotime("+1 day")),
        ]);

        Booking::create([
            'uuid' => Str::uuid()->toString(),
            'user_id' => 2,
            'pod_id' => 3,
            'status' => 'Active',
            'phone' => '98765432',
            'booking_datetime' => date('Y-m-d H:i:s'),
        ]);
        
        Booking::create([
            'uuid' => Str::uuid()->toString(),
            'user_id' => 2,
            'pod_id' => 3,
            'status' => 'Pending',
            'phone' => '223123466',
            'booking_datetime' => date('Y-m-d H:i:s', strtotime("+1 day")),
        ]);


    }
}
