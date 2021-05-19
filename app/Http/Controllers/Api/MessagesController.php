<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\MessageCreateRequest;
use App\Http\Resources\Messages\MessageResource;
use App\Http\Services\MessageService;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\HigherOrderCollectionProxy;


class MessagesController extends Controller
{
    /**
     * @param MessageService $messageService
     * @param $id
     * @return HigherOrderBuilderProxy|HigherOrderCollectionProxy|mixed
     */
    public function index(MessageService $messageService,$id)
    {
        return $messageService->chatMessages($id, auth()->user());
    }

    /**
     * @param MessageCreateRequest $request
     * @param $chat_id
     * @param MessageService $messageService
     * @return Builder|Model
     */
    public function store(MessageCreateRequest $request, $chat_id,MessageService $messageService)
    {
        return   $messageService->newMessage($chat_id, auth()->user(),$request->body);
    }

    /**
     * @param Message $message
     * @return MessageResource
     */
    public function show(Message $message)
    {
        return MessageResource::make($message);
    }

    /**
     * @param MessageCreateRequest $request
     * @param $chat_id
     * @param $message_id
     * @param MessageService $messageService
     * @return JsonResponse
     */
    public function edit(MessageCreateRequest $request, $chat_id, $message_id,MessageService $messageService)
    {

        if ($messageService->editMessage($request->body, $chat_id, $message_id, auth()->user())) {
            return response()->json(['message' => 'success message updated'], 200);
        }

        return response()->json([], 500);
    }

    /**
     * @param $chat_id
     * @param $message_id
     * @param MessageService $messageService
     * @return JsonResponse
     */
    public function destroy($chat_id, $message_id,MessageService $messageService)
    {
        if ($messageService->delete($chat_id, $message_id, auth()->user())) {
            return response()->json(['message' => 'success message delete'], 200);
        }

        return response()->json([], 500);
    }
}
