<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class orderRequest extends FormRequest
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
            'order_items' => ['array'],
            'delete_items' => ['array'],
            'order_items.*.id' => ['numeric'],
            'order_items.*.product_id' => ['numeric'],
            'order_items.*.price' => ['numeric'],
            'order_items.*.quantity' => ['numeric'],
            'order_items.*.variants' => ['string'],
        ];
        if ($this->isMethod('post')) {
            array_push($rules['order_items.*.product_id'] ,'required');
            array_push($rules['order_items.*.price'] ,'required');
            array_push($rules['order_items.*.quantity'] ,'required');
            array_push($rules['order_items.*.variants'] ,'required');
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
