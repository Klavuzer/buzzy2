<?php

namespace App\Http\Controllers;

use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class PostsController extends Controller
{
    /**
     * Show a Post
     *
     * @return \Illuminate\View\View
     */
    public function index($catname, $slug)
    {
        $post = get_post_from_url($catname, $slug);

        if (!$post) {
            return redirect('404');
        }

        $publish_from_now = $post->published_at && $post->published_at->getTimestamp() > Carbon::now()->getTimestamp();

        if ($post->approve !== 'yes' || $publish_from_now) {
            if (!Auth::check() || Auth::user()->usertype != 'Admin' && Auth::user()->id != $post->user->id) {
                return redirect('404');
            }
        }

        $this->postHit($post);

        $entries = $post->entries();
        if ($post->pagination == null) {
            $entries =  $entries->where('type', '!=', 'answer')->orderBy('order', 'asc')->get();
        } else {
            $entries =  $entries->where('type', '!=', 'answer')->orderBy('order', $post->ordertype == 'desc' ? 'desc' : 'asc')->paginate($post->pagination);
        }

        $lastTrending = Post::where('posts.id', '!=', $post->id)
            ->byActiveTypes()
            ->getStats('one_day_stats', 'DESC', 10)
            ->byPublished()
            ->byLanguage()
            ->byApproved()
            ->getCached('post_trending', now()->addMinutes(5));

        return view(
            "pages/post",
            compact(
                'post',
                'entries',
                'lastTrending',
                'publish_from_now'
            )
        );
    }


    /**
     *
     * @return \Illuminate\View\View
     */
    public function ajax_previous(Request $request)
    {
        $post = "";
        $id = $request->query('id');
        $type = $request->query('type');
        $pid = $request->query('pid');

        $posta = Post::with(['user'])->byPublished()->byApproved()->find($id);

        if (!$posta || $posta->type == 'quiz') {
            return "no";
        }

        $idarays = array_filter(explode('|', $pid));

        $post = null;
        foreach ($posta->tags()->get() as $tag) {
            $posto = $tag->posts()->where('posts.type', '!=', 'quiz')->whereNotIn('posts.id',  $idarays)->byPublished()->byLanguage()->byApproved()->first();
            if ($posto) {
                $post = $posto;
                break;
            }
        }

        foreach ($posta->categories()->get() as $category) {
            $posto = $category->posts()->where('posts.type', '!=', 'quiz')->whereNotIn('posts.id',  $idarays)->byPublished()->byLanguage()->byApproved()->first();
            if ($posto) {
                $post = $posto;
                break;
            }
        }

        if (!$post) {
            return "no";
        }

        $entries = $post->entries();
        if ($post->pagination == null) {
            $entries =  $entries->where('type', '!=', 'answer')->orderBy('order', $post->ordertype == 'desc' ? 'desc' : 'asc')->get();
        } else {
            $entries =  $entries->where('type', '!=', 'answer')->orderBy('order', $post->ordertype == 'desc' ? 'desc' : 'asc')->paginate($post->pagination);
        }

        $publish_from_now = '';

        return view("pages.postloadpage", compact(
            "post",
            'entries',
            'publish_from_now'
        ));
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function commentload(Request $request)
    {
        $id = $request->query('id');
        $url = $request->query('url');

        return view('_particles.post.comments', compact('id', 'url'));
    }

    public function postHit($post)
    {
        if (null == Cookie::get('BuzzyPostHit' . $post->id)) {
            $post->hit();
            Cookie::queue('BuzzyPostHit' . $post->id, "1", 10, generate_post_url($post));
        }
    }
}
