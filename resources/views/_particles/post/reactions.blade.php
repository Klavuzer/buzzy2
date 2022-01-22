@if (get_buzzy_config('p_reactionform') == 'on')
<section class="user-reactions" id="reactions{{ $post->id }}">
    <div class="colheader sea">
        <h3 class="header-title">{{ trans('updates.reaction.yourreaction') }}</h3>
    </div>
    <?php
        $most_reactions = \App\ReactionIcon::withCount(['reactions' => function($q) use ($post){
            $q->where('post_id', $post->id);
        }])
        ->orderByDesc('reactions_count')
        ->get();
        $user_reactions = $post->reactions()->currentUserHasVoteOnPost($post->id)->pluck('reaction_type')->all();
        $total_votes = $post->reactions()->count();
    ?>
    <div class="clear"></div>
    <div class="percentage-bar">
        @foreach($most_reactions as $reaction)
        <div class="reaction-emoji">
            <div class="bar">
                <span class="count f"
                    style="height: {{ $reaction->reactions_count ? number_format(($reaction->reactions_count / $total_votes) * 100, 8) : 0 }}%;">
                    <span class="count-text">{{ $reaction->reactions_count }}</span>
                </span>
            </div>
            <a {!! get_reaction_user_vote($post, $reaction->reaction_type, $user_reactions) !!}>
                <img alt="{{ $reaction->name }}" src="{{ $reaction->icon }} " width="50">
                <span class="text">{{ $reaction->name }}</span>
            </a>
        </div>
        @endforeach
    </div>
</section>
@endif
