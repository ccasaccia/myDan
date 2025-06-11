<?php

if(!is_null($user->events()->first()) && !empty($eventId)) {
    foreach ($user->events()->get() as $event) {
        if ($event->id == $eventId) {

            return  $user->can($this->ModelName() . $permission) == true ? true : abort(403);
        }
    }
    return abort(404);
}
return  $user->can($this->ModelName() . $permission) == true ? true : abort(403);
