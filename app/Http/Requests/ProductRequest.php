<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductRequest extends Request
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
            'name'         => 'required|string',
            'slug'         => 'string',
            'category_id'  => 'integer',
            'price'        => 'required|numeric',
            'quantity'     => 'integer',
            'published_at' => 'in:true',
            'status'       => 'in:opened,closed',
            'thumbnail'    => 'image|max:3000',
            'delete'       => 'in:true'
        ];
    }
}
