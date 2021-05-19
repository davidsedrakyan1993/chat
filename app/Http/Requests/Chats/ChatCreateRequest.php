<?php

namespace App\Http\Requests\Chats;

use Illuminate\Foundation\Http\FormRequest;

class ChatCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255|min:5',
            'type' => 'required|in:direct,group',
            'member_id' => 'required_if:type,==,direct',
            'member_type' => 'required_if:type,==,direct|in:'.implode(',', config('chats.member_models')),
        ];
    }
}
