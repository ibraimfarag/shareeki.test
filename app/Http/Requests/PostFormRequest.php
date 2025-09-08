<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
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

        if(auth()->user() == null) { return []; }

        return [
            'main_image' => 'mimes:jpeg,jpg,png,gif|sometimes|max:10000', // max 10000kb
            'area_id' => 'sometimes|numeric',
            'category_id' => 'required|numeric',
            'title' => 'required',
            'sort' => 'required',
            'partner_sort' => 'in:0,1',
            'the_tags' => 'sometimes',
            'partnership_percentage' => 'sometimes',
            'weeks_hours' => 'sometimes',
            'price' => 'required|numeric',
            'partners_no' => 'required|min:1|numeric',
            'body' => 'required',
            // 'phone' => 'required|numeric',
            'the_attachment.*'     => 'required|file|mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,doc,docx,pdf,tif,tiff'
        ];
    }
}
