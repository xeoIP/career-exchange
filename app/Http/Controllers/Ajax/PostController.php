<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Post;
use App\Http\Controllers\FrontController;
use App\Models\SavedPost;
use App\Models\SavedSearch;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Larapen\TextToImage\Facades\TextToImage;

class PostController extends FrontController
{
    /**
     * PostController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePost(Request $request)
    {
        $postId = $request->input('postId');
        
        $status = 0;
        if (Auth::check()) {
            $user = Auth::user();
            
            $savedPost = SavedPost::where('user_id', $user->id)->where('post_id', $postId);
            if ($savedPost->count() > 0) {
                // Delete SavedPost
                $savedPost->delete();
            } else {
                // Store SavedPost
                $savedPostInfo = [
                    'user_id' => $user->id,
                    'post_id' => $postId,
                ];
                $savedPost = new SavedPost($savedPostInfo);
                $savedPost->save();
                $status = 1;
            }
        }
        
        $result = [
            'logged'   => (Auth::check()) ? $user->id : 0,
            'postId'   => $postId,
            'status'   => $status,
            'loginUrl' => url($this->lang->get('abbr') . '/' . trans('routes.login')),
        ];
        
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveSearch(Request $request)
    {
        $queryUrl = $request->input('url');
        $tmp = parse_url($queryUrl);
        $query = $tmp['query'];
        parse_str($query, $tab);
        $keyword = $tab['q'];
        $countPosts = $request->input('countPosts');
        if ($keyword == '') {
            return response()->json([], 200, [], JSON_UNESCAPED_UNICODE);
        }
        
        $status = 0;
        if (Auth::check()) {
            $user = Auth::user();
            
            $savedSearch = SavedSearch::where('user_id', $user->id)->where('keyword', $keyword)->where('query', $query);
            if ($savedSearch->count() > 0) {
                // Delete SavedSearch
                $savedSearch->delete();
            } else {
                // Store SavedSearch
                $savedSearchInfo = [
                    'country_code' => $this->country->get('code'),
                    'user_id'      => $user->id,
                    'keyword'      => $keyword,
                    'query'        => $query,
                    'count'        => $countPosts,
                ];
                $savedSearch = new SavedSearch($savedSearchInfo);
                $savedSearch->save();
                $status = 1;
            }
        }
        
        $result = [
            'logged'   => (Auth::check()) ? $user->id : 0,
            'query'    => $query,
            'status'   => $status,
            'loginUrl' => url($this->lang->get('abbr') . '/' . trans('routes.login')),
        ];
        
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPhone(Request $request)
    {
        $postId = $request->input('postId', 0);
        
        $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', $postId)->first();
        
        if (empty($post)) {
            return response()->json(['error' => ['message' => t("Error. Post doesn't exists."),], 404]);
        }
        
        $post->phone = TextToImage::make($post->phone, IMAGETYPE_PNG, ['color' => '#FFFFFF']);
        
        return response()->json(['phone' => $post->phone], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
