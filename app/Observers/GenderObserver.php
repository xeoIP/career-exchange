<?php

namespace App\Observer;

use App\Models\Gender;

class GenderObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  Gender $gender
     * @return void
     */
    public function deleting(Gender $gender)
    {
        // Delete all translated entries
        $gender->translated()->delete();
    }
}
