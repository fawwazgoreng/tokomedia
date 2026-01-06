<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class cartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'products_id' => [ 'numeric'],
            'jumlah' => ['numeric'],
            'variants_id' => ['numeric']
        ];
        if ($this->isMethod('POST')) {
            array_push($rules['products_id'], 'required',);
            array_push($rules['variants_id'], 'required',);
        }
        return $rules;
    }

    public function messages()
    {
        return parent::messages();
    }

    protected function failedValidation(Validator $validator)
    {
        return new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
