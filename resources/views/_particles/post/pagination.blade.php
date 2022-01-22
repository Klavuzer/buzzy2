@if(isset($post->pagination) and $post->pagination!=null)
<ul class="post-pagination clearfix">
    @if($entries->currentPage()!=1)
        <a href="{{ generate_post_url($post).'?page='.($entries->currentPage()-1)  }}"
        class="button button-big button-blue pull-l ">
        {!! trans('pagination.previous') !!}
        </a>
    @endif
    @if($entries->currentPage()!=$entries->lastPage())
        <a href="{{ generate_post_url($post).'?page='.($entries->currentPage()+1) }}" style="float:right"
        class="button button-big button-blue pull-r">
        {!! trans('pagination.next') !!}
        </a>
    @endif
</ul>
@endif
