<?php

namespace App\Http\Controllers;

use App\Post;
use App\Entry;
use App\Reaction;
use App\PollVotes;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class PollController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (get_buzzy_config('sitevoting') == "1") {
            $this->middleware('auth');
        }
    }


    public function VoteANewPoll($entryid, $slug, Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $post = Post::where('slug', $slug)->first();
        $entry = Entry::where('id', $entryid)->first();

        $voteid = $request->query('vote');

        if (Auth::check()) {
            $auser = Auth::user()->id;
        } else {
            $auser = $request->ip();
        }

        $vote = new PollVotes;
        $vote->post_id = $entry->id;
        $vote->option_id = $voteid;
        $vote->user_id = $auser;
        $vote->save();

        if ($request->ajax()) {
            return view('_particles.post._entries._poll_answers', compact("post", "entry"));
        }

        return true;
    }

    public function VoteAPoll($catname, $slug, Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $post = Post::where('type', $catname)->where('slug', $slug)->first();

        $entries = $post->entry;

        $voteid = $request->query('vote');

        if (Auth::check()) {
            $auser = Auth::user()->id;
        } else {
            $auser = $request->ip();
        }

        $vote = new PollVotes;
        $vote->post_id = $post->id;
        $vote->option_id = $voteid;
        $vote->user_id = $auser;
        $vote->save();


        if ($request->ajax()) {
            return view('_particles.post.entries', compact("post", "entries"));
        }


        return true;
    }
    public function VoteReaction($catname, $slug, Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $post = Post::where('slug', $slug)->first();

        $voteid = $request->query('reaction');

        if (Auth::check()) {
            $auser = Auth::user()->id;
        } else {
            $auser = $request->ip();
        }

        if (Reaction::currentUserHasVoteOnPost($post->id)->count() <= 2) {
            $reactions = new Reaction;
            $reactions->post_id = $post->id;
            $reactions->reaction_type = $voteid;
            $reactions->user_id = $auser;
            $reactions->save();

            $reactions = $post->reactions;

            if ($request->ajax()) {
                return view('_particles.post.reactions', compact("reactions", "post"));
            }
        };


        return true;
    }

    public function shared(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $inputs = $request->all();

        $id = Arr::get($inputs, 'contentId');
        $shareType = Arr::get($inputs, 'shareType');
        $post = Post::findOrFail($id);

        if (!isset($shareType)) {
            $shareType = 'facebook';
        }


        if (null ==  Cookie::get('BuzzyPosthared' . $shareType . $post->id)) {
            cookie('BuzzyPosthared' . $shareType . $post->id, $post->id, 15000, generate_post_url($post));
        } else {
            return "ok";
        }

        $oshared = (array) $post->shared;

        $oshared[$shareType] = isset($oshared[$shareType]) ? (int) $oshared[$shareType] + 1 : 0;

        $post->shared = $oshared;
        $post->save();

        return "ok";
    }
}
