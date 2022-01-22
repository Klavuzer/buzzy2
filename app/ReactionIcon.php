<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReactionIcon extends Model
{
    protected $table = 'reactions_icons';

    protected $fillable = ['ord', 'icon', 'name', 'reaction_type', 'display'];

    /**
     * Reaction belongs to a post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reactions()
    {
        return $this->hasMany('App\Reaction', 'reaction_type', 'reaction_type');
    }
}
