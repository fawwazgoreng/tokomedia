<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class productRequest extends FormRequest
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
            'name' => ['string', 'min:3', 'max:100'],
            'gambar' => ['mimes:png,jpg', 'file', 'max:2080'],
            'categories' => ['array'],
            'delete_categories' => ['array'],
            'variants' => ['array'],
            'delete_variants' => ['array'],
            'variants.*.stock' => ['numeric'],
            'variants.*.price' => ['numeric'],
            'variants.*.id' => ['numeric'],
            'variants.*.option_1' => ['string', 'min:1', 'max:30',],
            'variants.*.option_2' => ['string', 'min:1', 'max:30',],
        ];
        if ($this->isMethod('post')) {
            array_push($rules['name'], 'required');
            // array_push($rules['gambar'], 'required');
            array_push($rules['categories'], 'required');
            array_push($rules['variants.*.id'], 'required');
            array_push($rules['variants.*.stock'], 'required');
            array_push($rules['variants.*.price'], 'required');
            array_push($rules['variants.*.option_1'], 'required');
        }
        return $rules;
    }

    public function messages()
    {
        return parent::messages();
    }


    protected function failedValidation(Validator $validator)
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
