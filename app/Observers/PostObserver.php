<?php

namespace App\Observer;

use App\Models\Message;
use App\Models\Picture;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  Post $post
     * @return void
     */
    public function deleting(Post $post)
    {
        // Delete all messages
        $messages = Message::where('post_id', $post->id)->get();
        if ($messages->count() > 0) {
            foreach ($messages as $message) {
                $message->delete();
            }
        }
    
        // Delete all entries by users in database
        $post->savedByUsers()->delete();
    
        // Remove logo files (if exists)
        if (!empty($post->logo)) {
            $filename = str_replace('uploads/', '', $post->logo);
            if (!str_contains($filename, config('larapen.core.picture.default'))) {
                Storage::delete($filename);
            }
        }
    
        // Delete all pictures entries in database
        $pictures = Picture::where('post_id', $post->id)->get();
        if ($pictures->count() > 0) {
            foreach ($pictures as $picture) {
                $picture->delete();
            }
        }
    
        // Delete the paymentof this Ad
        $post->onePayment()->delete();
    }
    
    /**
     * Listen to the Entry saved event.
     *
     * @param  Post $post
     * @return void
     */
    public function saved(Post $post)
    {
        // Removing Entries from the Cache
        $this->clearCache($post);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  Post $post
     * @return void
     */
    public function deleted(Post $post)
    {
        // Removing Entries from the Cache
        $this->clearCache($post);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $post
     */
    private function clearCache($post)
    {
        Cache::forget($post->country_code . '.sitemaps.posts.xml');
        
        Cache::forget($post->country_code . '.home.getPosts.sponsored');
        Cache::forget($post->country_code . '.home.getPosts.latest');
        Cache::forget($post->country_code . '.home.getFeaturedPostsCompanies');
    
        Cache::forget('post.withoutGlobalScopes.with.user.city.pictures.' . $post->id);
        Cache::forget('post.with.user.city.pictures.' . $post->id);
        Cache::forget('posts.similar.category.' . $post->category_id . '.post.' . $post->id);
        Cache::forget('posts.similar.city.' . $post->city_id . '.post.' . $post->id);
    }
}
