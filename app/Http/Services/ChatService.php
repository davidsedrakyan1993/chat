<?php

namespace App\Http\Services;

use App\Http\Controllers\Api\MembersController;
use App\Http\Requests\Chats\ChatCreateRequest;
use App\Http\Requests\Members\MemberCreateRequest;
use App\Http\Resources\Chats\ChatResource;
use App\Models\Chat;
use App\Models\ChatsMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ChatService
{
    /**
     * @param $user
     * @return mixed
     */
    public function index($user)
    {
        return $this->getChatByMember($user);
    }

    /**
     * @param ChatCreateRequest $request
     * @param $user
     * @return Chat
     */
    public function create(ChatCreateRequest $request, $user): Chat
    {
        $chat = Chat::query()->create($request->all());
        $chat->chatMembers()->create([
            'member_id' => $user->id,
            'member_type' => config('chats.member_models')[get_class($user)],
        ]);

        if ($request['type'] == 'direct') {
            $chat->chatMembers()->create([
                'member_id' => $request->member_id,
                'member_type' => $request->member_type,
            ]);
        }

        return $chat;
    }


    /**
     * @param ChatCreateRequest $request
     * @param $chat_Id
     * @param $member
     * @return bool|int
     */
    public function update(ChatCreateRequest $request,$chat_Id,$member)
    {
        $chat = Chat::query()->find($chat_Id);
        if (! $this->checkChatForMember($chat, $member)){
            throw new ModelNotFoundException();
        }
        return   $chat->update($request->all());
    }

    /**
     * @param $id
     * @param $member
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function findOrFail($id, $member)
    {
        $chat = Chat::query()->findOrFail($id);

        if (! $this->checkChatForMember($chat, $member)){
            throw new ModelNotFoundException();
        }

        return $chat;
    }

    /**
     * @param $user
     * @return mixed
     */
    private function getChatByMember($user)
    {
        return $user->chats;
    }

    /**
     * @param $chat
     * @param $member
     * @return bool
     */
    private function checkChatForMember($chat, $member)
    {
        return ! $chat->chatMembers->where('member_id', $member->id)->isEmpty();
    }

    /**
     * @param $chat_id
     * @param $user
     * @return int
     */
    public function delete($chat_id, $user)
    {
        $chat = Chat::query()->findOrFail($chat_id);
        if (! $this->checkChatForMember($chat, $user)){
            throw new ModelNotFoundException();
        }
        return Chat::destroy($chat_id);
    }
}
