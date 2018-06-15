<?php

namespace App\Observer;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CategoryObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  Category $category
     * @return void
     */
    public function deleting(Category $category)
    {
        // Delete all translated categories
        $category->translated()->delete();
    
        // Delete all category ads
        $posts = Post::where('category_id', $category->tid)->get();
        if ($posts->count() > 0) {
            foreach ($posts as $post) {
                $post->delete();
            }
        }
    
        // Don't delete the default pictures
        $defaultPicture = 'app/default/categories/fa-folder-' . config('settings.app_skin', 'skin-default') . '.png';
        if (!str_contains($category->picture, $defaultPicture)) {
            // Delete the category picture
            Storage::delete($category->picture);
        }
    
        // If the category is a parent category, delete all its children
        if ($category->parent_id == 0) {
            $cats = self::where('parent_id', $category->tid)->get();
            if ($cats->count() > 0) {
                foreach ($cats as $cat) {
                    $cat->delete();
                }
            }
        }
    }
    
    /**
     * Listen to the Entry saved event.
     *
     * @param  Category $category
     * @return void
     */
    public function saved(Category $category)
    {
        // Removing Entries from the Cache
        $this->clearCache($category);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  Category $category
     * @return void
     */
    public function deleted(Category $category)
    {
        // Removing Entries from the Cache
        $this->clearCache($category);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $category
     */
    private function clearCache($category)
    {
        Cache::flush();
    }
}
