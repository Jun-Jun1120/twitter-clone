<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'search' => 'sometimes|required|max:' . config('const.SEARCH.MAX')
        ];
    }

    public function messages()
    {
        return [
            'search.required' => '1文字以上入力して下さい',
            'search.max' => ':max文字以内で入力して下さい',
        ];
    }
}
