<?php

namespace MillionsSaving\Policies;

//use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use MillionsSaving\CustomModels\Event;
use MillionsSaving\User;


class EventPolicy
{
    //use HandlesAuthorization;

    /**
     * Determine whether the user can view any events.
     *
     * @param  \MillionsSaving\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return ($user->role == 'Administrator')
               ? Response::allow()
               : Response::deny('Your account is inactive, please 
              inform your super administrator');
                         // ? Response::allow() 
                         // : Response::deny("You don't own this event.");
    }

    public function canViewEvents(User $user){
        $user->name === "Peter"
                         ? Response::allow() 
                         : Response::deny("You don't own this event.");
    });

    /**
     * Determine whether the user can view the event.
     *
     * @param  \MillionsSaving\User  $user
     * @param  \MillionsSaving\CustomModels\Event  $event
     * @return mixed
     */
    public function view(User $user, Event $event)
    {
        ($user->name == $event->event_registra)
                         ? Response::allow() 
                         : Response::deny("You don't own this event.");
    }

    /**
     * Determine whether the user can create events.
     *
     * @param  \MillionsSaving\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the event.
     *
     * @param  \MillionsSaving\User  $user
     * @param  \MillionsSaving\CustomModels\Event  $event
     * @return mixed
     */
    public function update(User $user, Event $event)
    {
        ($user->name == $event->event_registra)
                         ? Response::allow() 
                         : Response::deny("You don't own this event.");
    }

    /**
     * Determine whether the user can delete the event.
     *
     * @param  \MillionsSaving\User  $user
     * @param  \MillionsSaving\CustomModels\Event  $event
     * @return mixed
     */
    public function delete(User $user, Event $event)
    {
        //
    }

    /**
     * Determine whether the user can restore the event.
     *
     * @param  \MillionsSaving\User  $user
     * @param  \MillionsSaving\CustomModels\Event  $event
     * @return mixed
     */
    public function restore(User $user, Event $event)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the event.
     *
     * @param  \MillionsSaving\User  $user
     * @param  \MillionsSaving\CustomModels\Event  $event
     * @return mixed
     */
    public function forceDelete(User $user, Event $event)
    {
        //
    }
}
