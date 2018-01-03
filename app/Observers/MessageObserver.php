<?php

namespace App\Observer;

use App\Models\Message;
use Illuminate\Support\Facades\Storage;

class MessageObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  Message $message
     * @return void
     */
    public function deleting(Message $message)
    {
        // Delete all files
        if (!empty($message->filename)) {
            $filename = str_replace('uploads/', '', $message->filename);
            Storage::delete($filename);
        }
    }
}
