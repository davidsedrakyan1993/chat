<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chats\ChatCreateRequest;
use App\Http\Resources\Chats\ChatResource;
use App\Http\Services\ChatService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    /**
     * @param ChatService $chatService
     * @return AnonymousResourceCollection
     */
    public function index(ChatService $chatService)
    {
        return $chatService->index(auth::user());
    }


    /**
     * @param ChatCreateRequest $request
     * @param ChatService $chatService
     * @return ChatResource
     */
    public function store(ChatCreateRequest $request, ChatService $chatService)
    {
        $chat = $chatService->create($request, auth()->user());

        return new ChatResource($chat);
    }


    /**
     * @param ChatService $chatService
     * @param $chat_id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function show(ChatService $chatService, $chat_id)
    {
        return $chatService->findOrFail($chat_id, auth::user());
    }

    /**
     * @param ChatCreateRequest $request
     * @param $id
     * @param ChatService $chatService
     * @return bool|int
     */
    public function update(ChatCreateRequest $request, $id,  ChatService $chatService)
    {
        return   $chatService->update($request,$id,auth()->user());
    }

    /**
     * @param $chat_id
     * @param ChatService $chatService
     * @return JsonResponse
     */
    public function destroy($chat_id, ChatService $chatService)
    {

        if ($chatService->delete($chat_id, auth()->user())) {
            return response()->json(['message' => 'success chat delete'], 200);
        }

        return response()->json([], 500);
    }
}
