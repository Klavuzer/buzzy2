<div class="item_category clearfix">
    @foreach ($post->categories()->get() as $item)
    <a href="{{action('PagesController@showCategory', ['catname' => $item->name_slug ])}}" class="seca">
        {{$item->name}}</a>
    @endforeach
</div>
