<?php
use App\House;
use App\Address;
use App\User;
use App\BookingHouse;
use Illuminate\Database\Seeder;

class HousesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(House::class, 50)->create();
        //
    }
}
