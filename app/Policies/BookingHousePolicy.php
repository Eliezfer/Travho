<?php

namespace App\Policies;

use App\BookingHouse;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;


class BookingHousePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any booking houses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the booking house.
     *
     * @param  \App\User  $user
     * @param  \App\BookingHouse  $bookingHouse
     * @return mixed
     */
    public function view(User $user, BookingHouse $bookingHouse)
    {
        //
        return $user->id == $bookingHouse->user_id;
    }

    /**
     * Determine whether the user can create booking houses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the booking house.
     *
     * @param  \App\User  $user
     * @param  \App\BookingHouse  $bookingHouse
     * @return mixed
     */
    public function update(User $user, BookingHouse $bookingHouse)
    {
        //

        return $user->id == $bookingHouse->user_id;
    }

    /**
     * Determine whether the user can delete the booking house.
     *
     * @param  \App\User  $user
     * @param  \App\BookingHouse  $bookingHouse
     * @return mixed
     */
    public function delete(User $user, BookingHouse $bookingHouse)
    {
        //
    }

    /**
     * Determine whether the user can restore the booking house.
     *
     * @param  \App\User  $user
     * @param  \App\BookingHouse  $bookingHouse
     * @return mixed
     */
    public function restore(User $user, BookingHouse $bookingHouse)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the booking house.
     *
     * @param  \App\User  $user
     * @param  \App\BookingHouse  $bookingHouse
     * @return mixed
     */
    public function forceDelete(User $user, BookingHouse $bookingHouse)
    {
        //
    }
}
