<?php

namespace App\Http\Services;

use App\Http\Requests\Members\MemberCreateRequest;
use App\Models\Chat;
use App\Models\ChatsMember;
use App\Models\User;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\HigherOrderCollectionProxy;

class MemberService
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection\
     */
    public function index($user)
    {
        return $user->chats;
    }

    /**
     * @param $id
     * @param $user
     * @return HigherOrderBuilderProxy|HigherOrderCollectionProxy|mixed
     */
    public function showChatMembers($id, $user)
    {
        $chat = Chat::query()->findOrFail($id);

        if (! $this->checkMember($chat, $user)){
            throw new ModelNotFoundException();
        }

        return $chat->chatMembers;
    }

    private function checkMember($chat, $member)
    {
        return ! $chat->chatMembers->where('member_id', $member->id)->isEmpty();
    }

    /**
     * @param MemberCreateRequest $request
     * @param $chat_id
     * @param $user
     * @return mixed
     */
    public function addMemberForChat(MemberCreateRequest $request, $chat_id,$user)
    {
        $chat = Chat::query()->findOrFail($chat_id);

        $member = User::query()->findOrFail($request['member_id']);

        if (! $this->checkMember($chat, $user)){
            throw new ModelNotFoundException();
        }

        return $chat->chatMembers()->firstOrCreate(
            ['member_id' => $request['member_id'],
             'member_type' => config('chats.member_models')[get_class($member)],
        ]);
    }

    /**
     * @param $member_id
     * @param $chat_id
     * @param $user
     * @return mixed
     */
    public function delete($member_id, $chat_id, $user)
    {

        $chat = Chat::query()->findOrFail($chat_id);
        if (! $this->checkMember($chat, $user)){
            throw new ModelNotFoundException();
        }
        return ChatsMember::query()->where('member_id', $member_id)->delete();
    }
}
