@extends("app")

@section('head_title', $category->name .' | '.get_buzzy_config('sitename') )
@section('head_description', $category->description )
@section('body_class', "category-page")



@section("content")

@if(!empty($lastFeaturestop))

<div class="content shay">

    <div class="container shay">

        <div class="row homefeatures clearfix">
            <h1 style="margin-left: 5px;"><span style="font-weight: 700;">{{ $category->name }}</span> <small
                    style="color:#f1f1f1">|</small>
                @foreach($category->children as $cat)
                <a style="font-size:16px;margin-left:10px;color:#999;" data-type="{{ $cat->name_slug }}"
                    href="/{{ $cat->name_slug }}"> {{ $cat->name }}</a>
                @endforeach
            </h1>
            <div class="pull-l">
                @foreach($lastFeaturestop->slice(0,1) as $item)
                <div class="tile tile-2">
                    @include('._particles._lists.features_list', ['descof' => 'on','metaon' => 'on'])
                </div>
                @endforeach

            </div>
            <div class="pull-l">
                @foreach($lastFeaturestop->slice(1,1) as $item)
                <div class="tile tile-1">
                    @include('._particles._lists.features_list', ['descof' => 'on','metaon' => 'on'])
                </div>
                @endforeach

            </div>

            <div class="pull-l tway">
                @foreach($lastFeaturestop->slice(2,2) as $item)
                <div class="tile tile-3">
                    @include('._particles._lists.features_list', ['metaon' => 'on'])
                </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
@endif

<div class="content">

    <div class="container">

        <div class="mainside cat">
            <div class="external-sign-in rss" style="margin:0;padding:0;width:auto;float:right">
                <a class="Rss mini" target=_blank style="width:24px;height:24px;margin:6px 0 0 0"
                    href="{{ $category->name_slug }}.xml"></a></div>
            <style>
                .external-sign-in.rss a:after {
                    font-size: 14px !important;
                    top: 5px !important;
                    left: -7px
                }
            </style>
            <div class="colheader   none ">
                @if(isset($search))
                <h3 class="header-title">{{ $search }}</h3>
                @elseif(isset($category->name))
                <h3 class="header-title">{{ trans('index.latest', ['type' => $category->name ]) }}</h3>
                @endif

            </div>


            @if(count($lastItems) > 0)
            <div class="jscroll" data-auto="{!!  get_buzzy_config('AutoLoadLists')=='yes' ?: 'false' !!}">
                @include('pages.catpostloadpage')
            </div>
            @else
            @include('errors.emptycontent')
            @endif

        </div>
        <div class="sidebar">

            @foreach(\App\Widgets::where('type', 'CatSide')->where('display', 'on')->get() as $widget)
            {!! $widget->text !!}
            @endforeach
            @if($lastTrending)

            <div class="colheader" style="border:0;text-transform: uppercase">
                <h3 class="header-title">{{ trans('index.weekly') }} {!! trans('index.top', ['type' => '<span
                        style="color:#d92b2b">'.$category->name.'</span>' ]) !!}</h3>
            </div>
            @include("_widgets.trendlist_sidebar")
            @endif
            @include("_widgets/facebooklike")

        </div>
    </div>

</div>


@endsection
