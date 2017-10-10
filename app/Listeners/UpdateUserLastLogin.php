<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Events\UserWasLogged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Request as Request;

class UpdateUserLastLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Handle the event.
     *
     * @param  UserWasLogged $event
     * @return void
     */
    public function handle(UserWasLogged $event)
    {
        $event->user->last_login_at = (config('timezone.id')) ? Carbon::now(config('timezone.id')) : Carbon::now();
        $event->user->save();
    }
}
