 @if(Auth::check() && Auth::user()->isAdmin() || get_buzzy_config('UserCanPost', 'yes') != "no")
    <div class="create-links hor">
        <a class="header__appbar--right__settings__button  has-dropdown button button-create hide-mobile" style="margin:-2px 10px 0 8px" href="{{ action('PostEditorController@showPostCreate') }}" >{{ trans('index.create') }}</a>
        <a class="header__appbar--right__settings__button material-button material-button--icon ripple visible-mobile" href="{{ action('PostEditorController@showPostCreate') }}" ><i class="material-icons">&#xE148;</i></a>
    </div>
@endif
