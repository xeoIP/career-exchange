<?php

namespace App\Observer;

use App\Models\Post;
use App\Models\Resume;
use App\Models\SavedPost;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\User;

class UserObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  User $user
     * @return void
     */
    public function deleting(User $user)
    {
        // Delete all user's ads with dependencies
        $posts = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('user_id', $user->id)->get();
        if ($posts->count() > 0) {
            foreach ($posts as $post) {
                $post->delete();
            }
        }

        // Delete all user's messages
        $user->messages()->delete();

        // Delete all favorite ads
        $savedPosts = SavedPost::where('user_id', $user->id)->get();
        if ($savedPosts->count() > 0) {
            foreach ($savedPosts as $savedPost) {
                $savedPost->delete();
            }
        }

        // Delete all saved search
        $user->savedSearch()->delete();

        // Delete all user's resumes
        $resumes = Resume::where('user_id', $user->id)->get();
        if (!empty($resumes)) {
            foreach ($resumes as $resume) {
                $resume->delete();
            }
        }
    }
}
