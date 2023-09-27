<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
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
            'name' => 'sometimes|string|min:' . config('const.NAME.MIN') . '|max:' . config('const.NAME.MAX'),
            'email' => 'sometimes|nullable|email:filter,dns'
        ];
    }

    public function messages()
    {
        return [
            'name.min' => config('const.NAME.MIN') . '文字以上で入力して下さい。',
            'name.max' => config('const.NAME.MAX') . '文字以下で入力して下さい。',
            'email.email' => '有効なメールアドレスを入力してください。',
        ];
    }

}
