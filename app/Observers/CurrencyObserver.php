<?php

namespace App\Observer;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;

class CurrencyObserver
{
    /**
     * Listen to the Entry saved event.
     *
     * @param  Currency $currency
     * @return void
     */
    public function saved(Currency $currency)
    {
        // Removing Entries from the Cache
        $this->clearCache($currency);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  Currency $currency
     * @return void
     */
    public function deleted(Currency $currency)
    {
        // Removing Entries from the Cache
        $this->clearCache($currency);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $currency
     */
    private function clearCache($currency)
    {
        Cache::flush();
    }
}
