@foreach($post->entries()->byType('quizquestion')->oldest("order")->get() as $key => $entry)
<section class="entry quizquestion selectableQuest" id="section_{{ $entry->order }}" data-entry="{{ $entry->id }}">
    @if($entry->title)
    <h2 class="sub-title">
    {{ $entry->title }}
    </h2>
    @endif

   @include('_particles.post._entries._entry_image')

   {!! $entry->body !!}

    <div class="clear"></div>
    <div class="answer" style="margin-left:-15px;">
      @include('_particles.post._entries._quiz_answers')
    </div>
    <div class="clear"></div>
</section>
@endforeach

<section class="entry results" id="quiz_result" data-popup="{{ get_buzzy_config('BuzzyQuizzesPopup') }}"
    data-qtype="{{ $post->ordertype }}">
    <div class="quiz_result_area">
        <h2 class="post-title">{{ $post->title }}</h2>
        @if($post->ordertype=='trivia' )
        <div id="triviaresult" class="quiz_result active" data-link="{{ Request::url() }}" data-name="" data-iname=""
            data-itname="" data-description="{{ strip_tags($entry->body) }}"
            data-picture="{{ url(makepreview($post->thumb, 'b', 'posts')) }}" style="display: none;">
            <h2 class="quiz_headline">{!! trans('buzzyquiz.triviaresult') !!}</h2>
        </div>
        @endif
        @include('_particles.post._entries._quiz_results', ['results' => $post->entries()->byType("quizresult")->oldest("order")->get()])
    </div>
    <div class="clear"></div>
    <div class="quiz_result_share">
        <h2 class="bold share_title">{{trans('buzzyquiz.shareresult') }}</h2>
        <div class="external-sign-in">
            <a href="javascript:" class="Facebook do-signup postToResultFeed"
                style="float: left;margin-right:10px;width: 140px;padding: 5px 10px;">
                <div class="buzz-icon buzz-facebook-big"></div> {{trans('index.sharefacebook') }}
            </a>
            <a href="javascript:" class="Twitter do-signup postToResultFeed"
                style="float: left;margin-right:10px;width: 140px;padding: 5px 10px;">
                <div class="buzz-icon buzz-twitter-big"></div>{{trans('index.sharetweet') }}
            </a>
            <a href="javascript:" class="Pinterest do-signup postToResultFeed"
                style="float: left;margin-right:10px;width: 140px;padding: 5px 10px;">
                <div class="buzz-icon buzz-pinterest-big"></div>{{trans('index.sharepinterest') }}
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
</section>
