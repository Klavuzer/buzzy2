@extends("app")

@section('head_title', $search .' | '.get_buzzy_config('sitename') )
@section('head_description', $search )

@section("content")

<div class="content">
    <div class="content-title"><h3>{{ $search }}</h3></div>
    <div class="content-body clearfix">
        <div class="content-body__detail">

            @if($lastItems->total() > 0)

                <div class="content-timeline__list">
                    @foreach($lastItems as $k => $item)
                        @include('pages.catpostloadpage')
                    @endforeach
                </div>
            @else
                @include('errors.emptycontent')


            @endif
                <br><br>
            <center>
                <!-- Pagination links... -->
                {!! $lastItems->appends(['q' => request()->get('q')])->render() !!}

            </center>
        </div>
    </div>
</div>

<div class="sidebar info-sidebar hide-mobile">
    <div class="ads">
        @include('_particles.widget.ads', ['position' => 'CatSide', 'width' => 'auto', 'height' => 'auto'])

    </div>

        @include('_particles.widget.follow')
</div>

@endsection
