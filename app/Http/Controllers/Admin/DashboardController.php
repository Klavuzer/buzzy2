<?php

namespace App\Http\Controllers\Admin;

use App\Post;
use App\User;
use Carbon\Carbon;

class DashboardController extends MainAdminController
{
    public function index()
    {
        $rangetoday = Carbon::now()->subDays(1);

        $postunapprove = Post::approve('no')->count();

        $todaypost = Post::where('created_at', '>=', $rangetoday)->count();

        $todayusers = User::where('created_at', '>=', $rangetoday)->count();

        $todaylogins = User::where('updated_at', '>=', $rangetoday)->count();

        $listcount = Post::byType('list')->count();

        $videocount = Post::byType('video')->count();

        $pollcount = Post::byType('poll')->count();

        $newscount = Post::byType('news')->count();

        $lastunappruves = Post::with('user')->approve('no')->take('10')->latest()->get();


        $lastusers = User::latest("created_at")->take('10')->get();

        return view(
            '_admin.pages.index',
            compact(
                'todaypost',
                'todaypost',
                'postunapprove',
                'todayusers',
                'todaylogins',
                'listcount',
                'videocount',
                'pollcount',
                'newscount',
                'lastunappruves',
                'lastusers'
            )
        );
    }
}
