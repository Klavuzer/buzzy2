<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use App\Followers;
use App\Managers\UploadManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UsersController extends UserBaseController
{

    public function index(Request $request)
    {
        $lastPost = $this->user->posts()->with('user')->byActiveTypes();

        if (!Auth::check() || Auth::user()->id !== $this->user->id) {
            $lastPost = $lastPost->byPublished()->byLanguage()->byApproved();
        }

        $lastPost = $lastPost->latest('published_at')->paginate(15);

        return view("pages.user.dashboard", compact('lastPost'));
    }

    public function draftposts()
    {
        $this->authorize('view', $this->user);

        $lastPost = $this->user->posts()->with('user')->byActiveTypes()->approve('draft')->latest('published_at')->paginate(15);

        $patitle = trans('index.draft');

        return view("pages.user.otherposts", compact('lastPost', 'patitle'));
    }

    public function deletedposts()
    {
        $this->authorize('view', $this->user);

        $lastPost = $this->user->posts()->with('user')->byActiveTypes()->onlyTrashed()->latest('published_at')->paginate(15);

        $patitle = trans('index.trash');

        return view("pages.user.otherposts", compact('lastPost', 'patitle'));
    }

    public function follow()
    {
        $this->authorize('follow', $this->user);

        $follow = Followers::where("followed_id", $this->user->id)->where("user_id", Auth::user()->id)->first();
        if ($follow) {
            $follow->delete();
        } else {
            $follow = new Followers;
            $follow->user_id = Auth::user()->id;
            $follow->followed_id = $this->user->id;
            $follow->save();
        }

        $user = $this->user;
        return view('_particles.user.follow_button', compact('user'));
    }


    public function following()
    {
        $follows = $this->user->following()->paginate(36);

        return view("pages.user.following", compact('follows'));
    }

    public function followers()
    {
        $follows = $this->user->followers()->paginate(36);

        return view("pages.user.followers", compact('follows'));
    }

    public function followfeed()
    {
        $this->authorize('update', $this->user);

        $userIds = $this->user->following()->pluck('followed_id');

        $lastPost = Post::whereIn('user_id', $userIds)->byActiveTypes()->byPublished()->byLanguage()->byApproved()->paginate(10);

        return view("pages.user.followingposts", compact('lastPost'));
    }

    public function settings()
    {
        $this->authorize('update', $this->user);

        return view("pages.user.settings");
    }

    public function updatesettings(Request $request)
    {
        $this->authorize('update', $this->user);

        $inputs = $request->all();

        $val = $this->validator($request, $this->user->id);

        if ($val->fails()) {
            Session::flash('error.message',  $val->errors()->first());

            return redirect()->back()->withInput($inputs);
        }

        $username = isset($inputs['username']) ? $inputs['username'] : Auth::user()->username;
        $email = isset($inputs['email']) ? $inputs['email'] : Auth::user()->email;
        $password = $inputs['password'];

        if ($request->hasFile('icon')) {
            try {
                $image = new UploadManager();
                $image->path('upload/media/members/avatar');
                $image->file($request, 'icon');
                $image->name(str_slug($username, '-') . '-' . time());
                $image->make();
                $image->mime('jpg');
                $image->save([
                    'fit_width' => 200,
                    'fit_height' => 200,
                    'image_size' => 'b',
                ]);
                $image->save([
                    'fit_width' => 90,
                    'fit_height' => 90,
                    'image_size' => 's',
                ]);

                // delete previous image
                $image->delete(makepreview($this->user->icon, 'b', 'members/avatar'));
                $image->delete(makepreview($this->user->icon, 's', 'members/avatar'));

                $this->user->icon = $image->getPathforSave();
            } catch (\Exception $e) {
                Session::flash('error.message',  $e->getMessage());

                return redirect()->back()->withInput($inputs);
            }
        }

        if ($request->hasFile('splash')) {
            try {
                $image = new UploadManager();
                $image->file($request, 'splash');
                $image->path('upload/media/members/splash');
                $image->name(str_slug($username, '-') . '-' . time());
                $image->make();
                $image->mime('jpg');
                $image->save([
                    'fit_width' => 910,
                    'fit_height' => 250,
                    'image_size' => 'b',
                ]);
                $image->save([
                    'fit_width' => 300,
                    'fit_height' => 120,
                    'image_size' => 's',
                ]);

                // delete previous image
                $image->delete(makepreview($this->user->splash, 'b', 'members/splash'));
                $image->delete(makepreview($this->user->splash, 's', 'members/splash'));

                $this->user->splash = $image->getPathforSave();
            } catch (\Exception $e) {
                Session::flash('error.message',  $e->getMessage());

                return redirect()->back()->withInput($inputs);
            }
        }

        if (get_buzzy_config('UserEditUsername') == 'true' or Auth::user()->usertype == 'Admin') {
            if ($username) {
                $this->user->username = $username;
                $this->user->username_slug =  str_slug($username, '-');
            }
        }

        if (get_buzzy_config('UserEditEmail') == 'true' or Auth::user()->usertype == 'Admin') {
            if ($email) {
                $this->user->email = $email;
            }
        }

        if ($password) {
            $this->user->password =  bcrypt($password);
        }

        if (isset($inputs['social_profiles'])) {
            $this->user->social_profiles = (array) $inputs['social_profiles'];
        }

        // if email is new set unverified
        if ($email != Auth::user()->email) {
            $this->user->email_verified_at = null;
        }

        $this->user->name = $inputs['name'];
        $this->user->town = $inputs['town'];
        $this->user->genre = $inputs['gender'];
        $this->user->about = $inputs['about'];
        $this->user->facebookurl = isset($inputs['facebook']) ? $inputs['facebook'] : '';
        $this->user->twitterurl = isset($inputs['twitter']) ? $inputs['twitter'] : '';
        $this->user->weburl = isset($inputs['web']) ? $inputs['web'] : '';

        $this->user->save();

        Session::flash('success.message',  trans('index.successupdated'));

        return redirect('/profile/' . str_slug($username, '-') . '/settings');
    }

    /**
     * Validator update.
     */
    public function validator($request, $userid)
    {
        $rules = [
            'username' => 'max:35|min:3|unique:users,username,' . $userid,
            'email' => 'email|max:75|unique:users,email,' . $userid,
            'icon' => 'nullable|mimes:jpg,jpeg,gif,png',
            'name' => 'nullable|nullable|max:20|min:3',
            'town' => 'nullable|max:20|min:3',
            'gender' => 'nullable|max:20|min:3',
            'about' => 'nullable|max:500|min:3',
            'facebook' => 'nullable|url|max:100|min:7',
            'twitter' => 'nullable|url|max:100|min:7',
            'web' => 'nullable|url|max:100|min:7',
        ];

        if ($request->input('password')) {
            $rules = array_merge($rules, [
                'password' => 'min:6|max:15',
            ]);
        }

        return Validator::make($request->all(), $rules);
    }
}
