@php($social_links = collect(config('buzzy.social_links'))->filter(function($item, $provider){
return get_buzzy_config( $provider.'page') > '';
})->map(function($item, $provider){
return array_merge($item, [
'url' => get_buzzy_config( $provider.'page', $item['url'] ?? ''),
'follow_text' => get_buzzy_config( $provider.'page_btn_text', $item['follow_text'] ?? ''),
]);
}) )
<div class="sidebar-block clearfix">
    <div class="colheader sea" style="margin:0px">
        <h3 class="header-title">{{ trans('index.ccommunity') }}</h3>
    </div>
    <div class="social_links">
        @foreach ($social_links as $provider => $item)
        <a href="{!!  $item['url'] !!}" class="button button-white social-{{$provider}}" target="_blank" rel="nofollow"
            @if(!empty($item['color'])) style="color:#fff;background:{{$item['color']}}!important" @endif>
            <img width="26px" src="{{ $item['icon'] }}" />
            <span>{{ !empty($item['follow_text'] ) ? $item['follow_text'] : get_social_links_trans($item) }}</span>
        </a>
        @endforeach
    </div>
</div>
