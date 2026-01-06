<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class userLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => [ 'min:3' , 'max:100'],
            'password' => [ 'min:3' , 'max:100'],
            'email' => [ 'email' , 'min:3'],
            'foto_profil' => ['mimes:png,jpg,jpeg'  , 'max:2080']
        ];
        if ($this->method('POST')) {
            array_push($rules['name'] , 'required');
            array_push($rules['password'] , 'required');
            array_push($rules['email'] , 'required');
        }
        return $rules;
    }

    public function messages()
    {
        return parent::messages();
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
