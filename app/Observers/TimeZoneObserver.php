<?php

namespace App\Observer;

use App\Models\TimeZone;
use Illuminate\Support\Facades\Cache;

class TimeZoneObserver
{
    /**
     * Listen to the Entry saved event.
     *
     * @param  TimeZone $timeZone
     * @return void
     */
    public function saved(TimeZone $timeZone)
    {
        // Removing Entries from the Cache
        $this->clearCache($timeZone);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  TimeZone $timeZone
     * @return void
     */
    public function deleted(TimeZone $timeZone)
    {
        // Removing Entries from the Cache
        $this->clearCache($timeZone);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $timeZone
     */
    private function clearCache($timeZone)
    {
        Cache::flush();
    }
}
