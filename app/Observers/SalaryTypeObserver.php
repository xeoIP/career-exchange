<?php

namespace App\Observer;

use App\Models\SalaryType;
use Illuminate\Support\Facades\Cache;

class SalaryTypeObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  SalaryType $salaryType
     * @return void
     */
    public function deleting(SalaryType $salaryType)
    {
        // Delete all translated entries
        $salaryType->translated()->delete();
    }
    
    /**
     * Listen to the Entry saved event.
     *
     * @param  SalaryType $salaryType
     * @return void
     */
    public function saved(SalaryType $salaryType)
    {
        // Removing Entries from the Cache
        $this->clearCache($salaryType);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  SalaryType $salaryType
     * @return void
     */
    public function deleted(SalaryType $salaryType)
    {
        // Removing Entries from the Cache
        $this->clearCache($salaryType);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $salaryType
     */
    private function clearCache($salaryType)
    {
        Cache::flush();
    }
}
