<?php

use Illuminate\Database\Seeder;
use App\House;
use App\Address;
use App\User;
use App\BookingHouse;
class BookingHousesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(BookingHouse::class, 50)->create();
        //
    }
}
