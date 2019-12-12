<?php

namespace Tests\Feature\BookingTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\BookingHouse;
use App\House;
use App\Address;
use App\User;

class BookingHouseUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_host_can_update_a_booking_to_accept()
    {
        $booking = factory(BookingHouse::class)->create();
        
    }

}
