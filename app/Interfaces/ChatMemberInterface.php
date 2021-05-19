<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface ChatMemberInterface
{

    /**
     * @return MorphToMany
     */
    public function chats():MorphToMany;

    /**
     * @return string
     */
    public function getUserAvatar(): string;
}
