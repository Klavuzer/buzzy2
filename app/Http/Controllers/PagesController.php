<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Post;
use App\Pages;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{

    /**
     * Show child categories
     *
     * @param  $catname
     * @param  Request $req
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function showCategory($catname, Request $request)
    {
        $this->cat = $catname;

        $category = Category::with(['children', 'allChildrens'])->byLanguage()->where("name_slug", $catname)->firstOrFail();

        $childIds = get_category_all_childids_recursively($category);

        //top Features
        $lastFeaturestop = Post::with('user')->byCategories($childIds)
            ->byPublished()->byLanguage()->byApproved()->byFeatured()
            ->take(get_headline_posts_count(true))
            ->get();

        $lastItems = Post::with('user')->whereNotIn('id', $lastFeaturestop->pluck('id')->all())->byCategories($childIds)
            ->byPublished()->byLanguage()->byApproved()->paginate(16);

        $lastTrending = Post::with('popularityStats')
            ->getStats('seven_days_stats', 'DESC', 7)
            ->byCategories($childIds)
            ->byPublished()
            ->byLanguage()
            ->byApproved()
            ->getCached('cat_trending_' . $category->id, now()->addMinutes(5));

        if ($request->query('page')) {
            if ($request->ajax()) {
                return view('pages.catpostloadpage', compact('lastItems'));
            }
        }

        return view("pages.showcategory", compact("category", "lastItems", "lastTrending", "lastFeaturestop"));
    }


    /**
     * Show Pages
     *
     * @param  $catname
     * @param  Request $req
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function showpage($catname, Request $req)
    {
        $page = Pages::where("slug", $catname)->first();

        if (!$page) {
            return redirect('404');
        }

        return view("pages.showpage", compact("page"));
    }


    /**
     * Show Reaction Pages
     *
     * @param  $catname
     * @param  Request $req
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function showReaction($reaction_id)
    {
        $lastItems = Post::select('posts.*')
            ->leftJoin(
                'reactions',
                function ($leftJoin) {
                    $leftJoin->on('reactions.post_id', '=', 'posts.id');
                }
            )
            ->where('reactions.reaction_type', '=', $reaction_id)
            ->byActiveTypes()
            ->byPublished()
            ->byLanguage()
            ->byApproved()
            ->orderBy(DB::raw('COUNT(reactions.post_id) '), 'desc')
            ->groupBy("posts.id")->paginate(15);


        if (!$lastItems) {
            return redirect('404');
        }

        $reaction = \App\ReactionIcon::where('reaction_type', $reaction_id)->first();

        if (!$reaction) {
            return redirect('404');
        }
        $reaction = $reaction->name;

        return view("pages.showreactions", compact("lastItems", "reaction"));
    }

    public function dort()
    {
        return view("errors.404");
    }
}
