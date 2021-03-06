<?php

namespace App\Policies;

use App\BookingHouse;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\House;
use Illuminate\Auth\Access\Response;

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
        $house=House::findorfail($bookingHouse->house_id);
        return ($user->id == $bookingHouse->user_id) || ($user->id == $house->user_id)
                ? Response::allow()
                : Response::deny('Acción no autorizada, las reservaciones son privadas, no puede ver este booking');;
    }

    /**
     * Determine whether the user can create booking houses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, House $house)
    {
        return $user->id != $house->user_id
                ? Response::allow()
                : Response::deny('Acción no autorizada, no puede rentar su propia casa');
    }

    public function isHouseAvailable(User $user, $house){
        return $house->status
                ? Response::allow()
                : Response::deny('Acción no autorizada, no puede rentar una casa dada de baja');
    }

    public function isHouseAvailableToTheDate(User $user, $bookingsAccept){
        $isEmpty = $bookingsAccept->isEmpty();
        return $isEmpty
                ? Response::allow()
                : Response::deny('Acción no autorizada, la casa ha sido rentada en esas fechas');
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
        $house=House::findorfail($bookingHouse->house_id);
        return ($user->id == $bookingHouse->user_id) || ($user->id == $house->user_id)? Response::allow()
        : Response::deny('Acción no autorizada, las reservaciones son privadas, no puede actualizar este booking');
    }

    public function updateBookingAccepted(User $user, BookingHouse $bookingHouse)
    {
        return $bookingHouse->status!='accepted'
                ? Response::allow()
                : Response::deny('Acción no autorizada, no puede cambiar el estado de una renta aceptada');
        ;
    }
    public function updateBookingCanceled(User $user, BookingHouse $bookingHouse)
    {
        return $bookingHouse->status!='canceled'
                ? Response::allow()
                : Response::deny('Acción no autorizada, la renta se cancelo no puedes cambiar el estado');
        ;
    }

    public function updateBookingRejected(User $user, BookingHouse $bookingHouse)
    {
        return $bookingHouse->status!='rejected'
                ? Response::allow()
                : Response::deny('Acción no autorizada, la renta se rechazo no puedes cambiar el estado');
        ;
    }

    public function updatePastBookingDate(User $user, BookingHouse $bookingHouse)
    {
        return $bookingHouse->check_in > now()
                ? Response::allow()
                : Response::deny('Acción no autorizada, No se puede actualizar reservaciones de fechas pasadas');
        ;
    }

    public function updateBookingToCancel(User $user, BookingHouse $bookingHouse){
        $today = now();
        $remainingDays = $today->diffInDays($bookingHouse->check_in);
        return $remainingDays>'3' ? Response::allow()
        : Response::deny('Acción no autorizada, El tiempo de cancelación ha pasado, ya no puede cancelar');
    }

}
