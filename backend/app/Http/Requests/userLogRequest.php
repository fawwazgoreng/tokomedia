<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

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
            'name' => ['required' , 'min:3' , 'max:100'],
            'password' => ['required' , 'min:3' , 'max:100'],
            'email' => ['required' , 'email' , 'min:3'],
            'foto_profil' => ['mimes:png,jpg,jpeg'  , 'max:2mb']
        ];
        if ($this->path('login')) {
            $rules['name'] = [];
        }
        if ($this->method('PUT')) {
            $rules['name'] = ['min:3' , 'max:100'];
            $rules['password'] = ['min:3' , 'max:100'];
            $rules['email'] = ['email' , 'min:3'];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'nama wajib diisi',
            'name.min' => 'nama minimal berjumlah :min karakter',
            'name.max' => 'maximal nama 100 :max',
            'password.required' => 'password wajib diisi',
            'password.min' => 'password minimal berjumlah :min karakter',
            'password.max' => 'maximal password 100 :max',
            'email.required' => 'email wajib diisi',
            'email.min' => 'email minimal berjumlah :min karakter',
            'email.email' => 'format email harus benar',
            'foto_profil.mimes' => 'foto profil harus berupa png , jpg atau jpeg',
            'foto_profil.max' => 'maximal foto profil :max'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'validation error',
            'error' => $validator->errors()
        ] , 401);
    }
}
