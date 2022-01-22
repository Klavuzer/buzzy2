<?php $okm = "" ?>
<div class="row">
    @foreach(\App\Category::byMain()->byLanguage()->byActive()->orderBy('order')->take(10)->get() as $cat)
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{!! $cat->icon !!} {{ trans('admin.recentlyadded') }}
                    <b>{{ $cat->name }}</b>
                </h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i
                            class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i
                            class="fa fa-times"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                @php($posts = \App\Post::byCategories(get_category_ids_recursively($cat->id))->byPublished()->byApproved()->take(5)->get())
                @if($posts)
                <ul class="products-list product-list-in-box">
                    @foreach( $posts as $item)
                   <li class="item" style="    max-height: 70px; min-height: 70px;">
                        <div class="product-img" style="overflow: hidden;width:50px;">
                            <img src="{{ makepreview($item->thumb, 's', 'posts') }}" width="auto" style="   margin-left:-10px; width: auto;">
                        </div>
                        <div class="product-info">
                            <a href="{{ generate_post_url($item) }}" target="_blank" class="product-title">
                                {{ $item->title }}
                            </a>
                            <span class="product-description" style="color:#ccc">
                            <i class="fa fa-user" style="font-size:11px"></i>
                            @if( $item->user)<a href="{{ url('/profile/'.$item->user->username_slug) }}" target="_blank" style="color:#ccc">
                            {{ $item->user->username }}</a>
                            @endif
                            <i class="fa fa-clock-o" style="margin-left:7px;font-size:11px"></i> {{ $item->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                {{ trans('admin.nothingtoseehere') }}
                @endif
            </div><!-- /.box-body -->
            <div class="box-footer text-center">
                <a href="/admin/posts/?type=category&category_id={!! $cat->id !!}" class="uppercase">{{ trans('admin.viewall')
                    }}</a>
            </div><!-- /.box-footer -->
        </div>
    </div>
    @endforeach
</div>
