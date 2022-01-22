
<ol style="list-style: none;margin: 0;padding: 0;">
    @foreach($results as $keyp => $entry)
    <li class="quiz_result" data-order="{{ $keyp }}" data-result="{{ $entry->id }}"
        data-link="{{ Request::url() }}" data-name="{{ trans('buzzyquiz.yougot', ['title'=> $entry->title]) }}"
        data-iname="{{ trans('buzzyquiz.igot', ['title'=> $entry->title, 'posttitle'=> $post->title]) }}"
        data-itname="{{ trans('buzzyquiz.igotfortweet', ['title'=> $entry->title]) }}"
        data-description="{{ strip_tags($entry->body) }}"
        data-picture="{{ $entry->image > "" ? url(makepreview($entry->image, null, 'entries')) : url(makepreview($post->thumb, 'b', 'posts'))  }}">
        <div class="quiz_headline">
            {{ trans('buzzyquiz.yougot', ['title'=> $entry->title]) }}
        </div>
        <div class="clear"></div>
        <div class="quiz_text" {{ $entry->image == '' ? 'style=width:100%' :'' }}>{!! $entry->body !!}</div>

        <div class="quiz_img {{ $entry->image == '' ? 'hide' :'' }}">
            <img class=" lazy responsive_img" style=" float: right;"
                src="data:image/gif;base64,R0lGODlhAQABAPAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==">
        </div>
    </li>
    @endforeach
</ol>
