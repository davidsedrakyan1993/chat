<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Members\MemberCreateRequest;
use App\Http\Services\MemberService;
use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class MembersController extends Controller
{
    /**
     * @param MemberService $memberService
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection\
     */
    public function index(MemberService $memberService)
    {
        return  $memberService->index(auth()->user());
    }

    /**
     * @param MemberCreateRequest $request
     * @param $chat_id
     * @param MemberService $memberService
     * @return mixed
     */
    public function store(MemberCreateRequest  $request, $chat_id,MemberService $memberService)
    {
        return  $memberService->addMemberForChat($request, $chat_id, auth()->user());
    }

    /**
     * @param $id
     * @param MemberService $member_services
     * @return Builder[]|Collection|JsonResponse
     */
    public function show($id,MemberService $member_services)
    {
        return  $member_services->showChatMembers($id, auth()->user());
    }

    /**
     * @param $chat_id
     * @param $member_id
     * @param MemberService $memberService
     * @return JsonResponse
     */
    public function destroy($chat_id, $member_id, MemberService $memberService)
    {

        if ($memberService->delete($member_id, $chat_id,auth()->user())) {
            return response()->json(['message' => 'success member delete'], 200);
        }

        return response()->json([], 500);
    }

}
