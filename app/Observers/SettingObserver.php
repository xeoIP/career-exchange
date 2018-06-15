<?php

namespace App\Observer;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  Setting $setting
     * @return void
     */
    public function deleting(Setting $setting)
    {
        // Don't delete the default medias (logo, favicon, etc.)
        if (
            !str_contains($setting->value, config('larapen.core.logo')) &&
            !str_contains($setting->value, config('larapen.core.favicon'))
        )
        {
            // Delete file
            Storage::delete($setting->value);
        }
    }
    
    /**
     * Listen to the Entry saved event.
     *
     * @param  Setting $setting
     * @return void
     */
    public function saved(Setting $setting)
    {
        // Removing Entries from the Cache
        $this->clearCache($setting);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  Setting $setting
     * @return void
     */
    public function deleted(Setting $setting)
    {
        // Removing Entries from the Cache
        $this->clearCache($setting);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $setting
     */
    private function clearCache($setting)
    {
        Cache::flush();
    }
}
