@extends("app")
@include("_particles.post.head")
@section('body_class', 'mode-default')
@section("content")

<div class="content">

    <div class="container">
        <div class="mainside postmainside">

            <div class="post-content" style="margin-top:7px;background: transparent" itemscope=""
                itemtype="https://schema.org/Article">

                <div class="post">
                    <div class="post-head">
                        @if($post->approve == 'draft')
                        <div class="label label-staff">{{ trans('updates.thisdraftpost') }}</div>
                        @endif
                        <h1 itemprop="name" class="post-title">
                            {{ $post->title }}
                        </h1>

                        @can('update', $post)
                        <div style="margin:5px 0">

                            @if($post->approve == 'no')
                            @can('approve', $post)
                            <a href="{{ action('Admin\PostsController@approve', $post->id) }}"
                                class="button button-orange button-small"><i class="fa fa-check"></i>
                                {{ trans('index.approve') }}</a>
                            @else
                            <a href="#" class="button button-orange button-small" style="cursor: default;height:26px"><i
                                    class="fa fa-clock"></i> {{ trans('index.waitapprove') }}</a>
                            @endcan
                            @endif

                            @can('update', $post)
                            <a href="{{ action('PostEditorController@showPostEdit', [$post->id]) }}"
                                class="button button-green button-small"><i class="fa fa-edit"></i>
                                {{ trans('index.edit') }}</a>
                            @endcan
                            @can('delete', $post)
                            <a href="{{ action('PostEditorController@deletePost', [$post->id]) }}" onclick="confim()"
                                class="button button-red button-small "><i class="fa fa-trash"></i></a>
                            @endcan

                            @if($publish_from_now)
                            <div class="label label-admin" style="margin-left:5px">
                                {{ trans('v3.scheduled_date', ['date' => $post->published_at->format('j M Y, h:i A')])
                                }}
                            </div>
                            @endif
                        </div>
                        @endcan



                        <p>
                            {!! nl2br($post->body) !!}
                        </p>
                        <div class="post-head__bar">
                            @if(isset($post->user->username_slug))
                            <div class="user-info {{ $post->user->genre }} answerer">
                                <div class="avatar left">
                                    <img src="{{ makepreview($post->user->icon , 's', 'members/avatar') }}" width="45"
                                        height="45" alt="{{ $post->user->username }}">
                                </div>
                                <div class="info">


                                    <a class="name"
                                        href="{{ action('UsersController@index', [$post->user->username_slug ]) }}"
                                        target="_self">{{ $post->user->username}}</a>

                                    @if($post->user->usertype == 'Admin')
                                    <div class="label label-admin" style="margin-left:5px">
                                        {{ trans('updates.usertypeadmin') }}</div>
                                    @elseif($post->user->usertype == 'Staff')
                                    <div class="label label-staff" style="margin-left:5px">
                                        {{ trans('updates.usertypestaff') }}</div>
                                    @elseif($post->user->usertype == 'banned')
                                    <div class="label label-banned" style="margin-left:5px">
                                        {{ trans('updates.usertypebanned') }}</div>
                                    @endif

                                    <div class="detail">
                                        {!! trans('index.postedon', ['time' => '<time class="content-info__date"
                                            itemprop="datePublished"
                                            datetime="'.$post->published_at->toW3cString() .'">'.$post->published_at->diffForHumans()
                                            .'</time>' ]) !!}
                                        @if($post->published_at < $post->updated_at)
                                            <span class="content-info__line">—</span> {!! trans('index.updatedon',
                                            ['time' => '<time class="content-info__date" itemprop="dateModified"
                                                datetime="'.$post->updated_at->toW3cString() .'">'.$post->updated_at->diffForHumans()
                                                .'</time>' ]) !!}
                                            @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="post-head__meta">
                                <div class="posted-on">


                                </div>

                                <div class="topics-container clearfix">
                                    <div class="item_category">
                                        @foreach ($post->categories()->get() as $item)
                                        <a href="{{action('PagesController@showCategory', ['catname' => $item->name_slug ])}}"
                                            class="seca" style="margin-right:5px">
                                            {{$item->name}}</a>
                                        @endforeach
                                    </div>


                                    <div class="clear"></div>

                                </div>

                            </div>

                        </div>
                        <div class="clear"></div>
                        @include("_particles.others.postsociallinks")
                        <div class="clear"></div>

                        @foreach(\App\Widgets::where('type', 'PostShareBw')->where('display', 'on')->get() as $widget)
                        {!! $widget->text !!}
                        @endforeach

                    </div>

                    <div class="clear"></div>

                    <article class="post-body" id="post-body" itemprop="text">

                        @include("_particles.post.entries")

                    </article>

                </div>

                @include("_particles.post.tags", ['tags' => $post->tags])

                @foreach(\App\Widgets::where('type', 'PostBelow')->where('display', 'on')->get() as $widget)
                {!! $widget->text !!}
                @endforeach


            </div>

            @include("_particles.post.reactions")

            @include("_particles.post.comments")

        </div>
        <div class="sidebar">

            @foreach(\App\Widgets::where('type', 'PostPageSidebar')->where('display', 'on')->get() as $widget)
            {!! $widget->text !!}
            @endforeach

            <div class="colheader" style="border:0;text-transform: uppercase;">
                <h3 class="header-title">{{ trans('index.today') }} {!! trans('index.top', ['type' => '<span
                        style="color:#d92b2b">'.trans('index.posts').'</span>' ]) !!}</h3>
            </div>

            @include("_widgets.trendlist_sidebar")

            @include("_widgets.facebooklike")

        </div>
        <div class="clear"></div>
        <br><br> <br>
        @if(isset($lastFeatures))
        @if(count($lastFeatures) >= 3)
        <div class="colheader">
            <h3 class="header-title">{{ trans('index.maylike') }}</h3>
        </div>
        @include("_widgets.post-between-comments")
        @endif
        @endif
    </div>

</div>


@endsection
@section('footer')
@include("_particles.post.footer")
@endsection
