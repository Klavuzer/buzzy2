@if(Auth::check())
    @if($user->followers()->currentUserFollow()->count() > 0)
        <a class="button button-white button-small postable" data-method="post" data-target-selector="{{'.following_area' . $user->id }}" href="{{ action('UsersController@follow', [$user->username_slug] ) }}" rel="nofollow">
            <i class="fa fa-user-times" style="margin-right:5px;"></i>  {{ trans('updates.followinguser') }}
        </a>
    @else
    @if(Auth::user()->id!=$user->id)
    <a class="button button-white button-small postable" data-method="post" data-target-selector="{{'.following_area' . $user->id }}" href="{{ action('UsersController@follow', [$user->username_slug] ) }}" rel="nofollow">
      <i class="fa fa-user-plus" style="margin-right:5px;"></i>  {{ trans('updates.follow') }}
    </a>
    @endif
@endif

@else
<a class="button button-white button-small" href="{{ route('login') }}" rel="get:Loginform">
    <i class="fa fa-user-times" style="margin-right:5px;"></i>  {{ trans('updates.follow') }}
</a>
@endif
