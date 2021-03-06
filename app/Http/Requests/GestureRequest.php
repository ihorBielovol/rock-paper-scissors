<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GestureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'game_id' => 'required|id',
            'nickname' => 'required|string',
            'gesture' => 'required|string|in:rock,paper,scissors',
        ];
    }
}
