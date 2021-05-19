<?php


namespace App\Traits;


use App\Models\Chat;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait ChatMemberTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function chats(): MorphToMany
    {
        return $this->morphToMany(Chat::class, 'member', 'chats_members');
    }
}
