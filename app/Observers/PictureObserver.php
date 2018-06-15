<?php

namespace App\Observer;

use App\Models\Picture;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PictureObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  Picture $picture
     * @return void
     */
    public function deleting(Picture $picture)
    {
        // Delete all pictures files
        if (!empty($picture->filename)) {
            $filePath = str_replace('uploads/', '', $picture->filename);
        
            // Delete the picture with its thumbs
            $filename = last(explode('/', $filePath));
            $files = Storage::files(dirname($filePath));
            if (!empty($files)) {
                foreach($files as $file) {
                    // Don't delete the default picture
                    if (str_contains($file, config('larapen.core.picture.default'))) {
                        continue;
                    }
                    if (str_contains($file, $filename)) {
                        Storage::delete($file);
                    }
                }
            }
        }
    }
	
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param  Picture $picture
	 * @return void
	 */
	public function saved(Picture $picture)
	{
		// Removing Entries from the Cache
		$this->clearCache($picture);
	}
	
	/**
	 * Listen to the Entry deleted event.
	 *
	 * @param  Picture $picture
	 * @return void
	 */
	public function deleted(Picture $picture)
	{
		// Removing Entries from the Cache
		$this->clearCache($picture);
	}
	
	/**
	 * Removing the Entity's Entries from the Cache
	 *
	 * @param $picture
	 */
	private function clearCache($picture)
	{
		Cache::forget('post.withoutGlobalScopes.with.user.city.pictures.' . $picture->post_id);
		Cache::forget('post.with.user.city.pictures.' . $picture->post_id);
	}
}
