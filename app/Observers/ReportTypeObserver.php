<?php

namespace App\Observer;

use App\Models\ReportType;

class ReportTypeObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  ReportType $reportType
     * @return void
     */
    public function deleting(ReportType $reportType)
    {
        // Delete all translated entries
        $reportType->translated()->delete();
    }
}
