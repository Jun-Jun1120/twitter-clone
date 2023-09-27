<?php

namespace App\Http\Requests;

use App\Rules\MaxWordCountValidation;
use Illuminate\Foundation\Http\FormRequest;

class ReplyRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'content' => ['required', new MaxWordCountValidation(280)]
        ];
    }

    public function messages()
    {
        return [
            'content.required' => '文字を1文字以上入力して下さい',
        ];
    }
}
