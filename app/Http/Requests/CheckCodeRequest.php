<?php

namespace App\Http\Requests;

use HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CheckCodeRequest extends FormRequest
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
            'email'=>'required|string',
            'code'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required!',
            'code.required' => 'Token is required!',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json(['status'=>'error','message'=>$validator->errors()], 422));
    }
}
