<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        return [
            'limit' => ['integer', 'min:0', 'max:100'],
            'sort' => ['string', 'in:id,created_at,price,name', 'max:255'],
            'order' => ['string', 'in:asc,desc', 'max:255'],
            'search' => ['string', 'max:255'],
            'status' => ['string', 'in:1,2'],
            'collection_id' => ['string', 'exists:collections,id'],
            'category_id' => ['string', 'exists:categories,id'],
        ];
    }
}
