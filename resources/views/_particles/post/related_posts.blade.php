@if(get_buzzy_theme_config('PostPageAutoload') == 'related')
<?php
$postTags = $post->tags()->pluck('id')->toArray();

$lastFeatures = \App\Post::whereHas('tags', function ($q) use ($postTags) {
    $q->whereIn('id', $postTags);
})
    ->withCount(['tags' => function ($q) use ($postTags) {
        $q->whereIn('id', $postTags);
    }])
    ->where('posts.id', '!=', $post->id)
    ->byPublished()->byLanguage()->byApproved()
    ->orderBy('tags_count', 'desc')
    ->inRandomOrder()
    ->limit(6)
    ->getCached('post_related_'.$post->id, now()->addMinutes(5));
?>
@if(!empty($lastFeatures))
<br>
<div class="colheader sea">
    <h3 class="header-title">{{ trans('index.maylike') }}</h3>
</div>
<div class="sidebar-block  clearfix" style="margin-top: 20px;">
    <ol class="sidebar-mosts sidebar-mosts--readed column_list tree_column">
        @foreach($lastFeatures->slice(0, 6) as $itema)
            <li class="sidebar-mosts__item " style="    height: 300px;">
                <a class="sidebar-mosts__item__link" href="{{ generate_post_url($itema) }}" title="{{ $itema->title }}">
                    <figure class="sidebar-mosts__item__body">
                        <div class="sidebar-mosts__item__image">
                            <img class="sidebar-mosts__item__image__item  lazyload" data-src="{{ makepreview($itema->thumb, 's', 'posts') }}" alt="{{ $itema->title }}">
                        </div>
                        <figcaption class="sidebar-mosts__item__caption">
                            <h3 class="sidebar-mosts__item__title">{{ $itema->title }}</h3>
                        </figcaption>
                        <div class="content-timeline__detail--bottom">
                            <div class="content-timeline__detail__date share_counts hide-phone" style="margin-left: -5px">
                                <span class="facebook"><i class="buzz-icon buzz-facebook"></i>{{ isset($item->shared->facebook) ? $item->shared->facebook : '0'}}</span>
                                <span class="twitter"><i class="buzz-icon buzz-twitter"></i>{{ isset($item->shared->twitter) ? $item->shared->twitter : '0'}}</span>
                                <span class="whatsapp"><i class="buzz-icon buzz-whatsapp"></i>{{ isset($item->shared->whatsapp) ? $item->shared->whatsapp : '0'}}</span>
                            </div>

                        </div>
                    </figure>
                </a>
            </li>
        @endforeach
    </ol>
</div>
@endif
@endif
