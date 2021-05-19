<?php


namespace App\Http\Services;


use App\Models\Chat;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\HigherOrderCollectionProxy;

class MessageService
{
    /**
     * @param $chat_id
     * @param $author
     * @param $body
     * @return Builder|Model
     */
    public function newMessage($chat_id, $author, $body)
    {
        $chat = Chat::query()->findOrFail($chat_id);

        if (! $this->checkAuthorInChat($chat, $author)){
            throw new ModelNotFoundException();
        }

        return  Message::query()->create([
           'chat_id'=>$chat_id,
           'author_id'=>$author->id,
           'body'=>$body,
        ]);
    }

    /**
     * @param $chat
     * @param $author
     * @return bool
     */
    private function checkAuthorInChat($chat, $author)
    {
        return ! $chat->chatMembers->where('member_id', $author->id)->isEmpty();
    }

    private function checkMessageAuthor($chat, $message_id, $author)
    {

        return ! $chat->messages->where('author_id', $author->id)->where('id', $message_id)->isEmpty();
    }

    /**
     * @param $id
     * @param $user
     * @return HigherOrderBuilderProxy|HigherOrderCollectionProxy|mixed
     */
    public function chatMessages($id, $user)
    {
        $chat= Chat::query()->findOrFail($id);
        if (! $this->checkAuthorInChat($chat, $user)){
            throw new ModelNotFoundException();
        }
        return $chat->messages;
    }

    /**
     * @param $body
     * @param $chat_id
     * @param $message_id
     * @param $author
     * @return int
     */

    public function editMessage($body, $chat_id, $message_id, $author)
    {

        $chat = Chat::query()->findOrFail($chat_id);
        if (! $this->checkAuthorInChat($chat, $author)){
            throw new ModelNotFoundException();
        }
        elseif (! $this->checkMessageAuthor($chat, $message_id, $author)){
            throw new ModelNotFoundException();
        }

    return  Message::query()->where('id', $message_id)->update(['body'=> $body]);
    }

    /**
     * @param $chat_id
     * @param $message_id
     * @param $author
     * @return mixed
     */
    public function delete($chat_id, $message_id, $author)
    {

        $chat = Chat::query()->findOrFail($chat_id);
        if (! $this->checkAuthorInChat($chat, $author)){
            throw new ModelNotFoundException();
        }
        elseif (! $this->checkMessageAuthor($chat, $message_id, $author)){
            throw new ModelNotFoundException();
        }

        return $chat->messages->where('id', $message_id)->delete();
    }

}
