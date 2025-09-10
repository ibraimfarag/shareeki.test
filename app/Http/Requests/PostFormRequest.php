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

        if (auth()->user() == null) {
            return [];
        }

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
            'partners_no' => 'required_if:full_partnership,on|min:1|max:100|numeric',
            'body' => 'required',
            // 'phone' => 'required|numeric',
            'the_attachment.*' => 'required|file|mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,doc,docx,pdf,tif,tiff'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // رسائل الحقول المطلوبة
            'category_id.required' => 'يجب اختيار فئة للإعلان',
            'category_id.numeric' => 'فئة الإعلان غير صحيحة',
            'title.required' => 'يجب كتابة عنوان الإعلان',
            'sort.required' => 'يجب اختيار نوع الفرصة (فكرة ، عمل قائم )',
            'price.required' => 'يجب تحديد المبلغ المطلوب',
            'price.numeric' => 'المبلغ يجب أن يكون رقماً صحيحاً',
            'partners_no.required_if' => 'يجب تحديد عدد الشركاء عند اختيار الشراكة',
            'partners_no.min' => 'عدد الشركاء يجب أن يكون على الأقل شريك واحد',
            'partners_no.max' => 'عدد الشركاء لا يمكن أن يزيد عن 100 شريك',
            'partners_no.numeric' => 'عدد الشركاء يجب أن يكون رقماً صحيحاً',
            'body.required' => 'يجب كتابة وصف تفصيلي عن الفرصة',

            // رسائل الصورة الرئيسية
            'main_image.mimes' => 'الصورة يجب أن تكون من نوع: JPEG, JPG, PNG, أو GIF',
            'main_image.max' => 'حجم الصورة يجب أن يكون أقل من 10 ميجابايت',

            // رسائل المنطقة
            'area_id.numeric' => 'المنطقة المختارة غير صحيحة',
            'area_id.sometimes' => 'يجب اختيار منطقة صحيحة',

            // رسائل المرفقات
            'the_attachment.*.required' => 'يجب اختيار ملف للمرفق',
            'the_attachment.*.file' => 'المرفق يجب أن يكون ملفاً صحيحاً',
            'the_attachment.*.mimes' => 'المرفق يجب أن يكون من الأنواع المسموحة: Excel, Word, PDF, أو صورة',

            // رسائل اختيارية إضافية
            'partner_sort.in' => 'اختيار نوع الشريك غير صحيح',
            'partnership_percentage.numeric' => 'نسبة الشراكة يجب أن تكون رقماً',
            'the_tags.sometimes' => 'الكلمات المفتاحية يجب أن تكون نصاً صحيحاً',
            'weeks_hours.sometimes' => 'ساعات العمل يجب أن تكون رقماً صحيحاً',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'category_id' => 'فئة الإعلان',
            'title' => 'عنوان الإعلان',
            'sort' => 'نوع الفرصة',
            'price' => 'المبلغ المطلوب',
            'partners_no' => 'عدد الشركاء',
            'body' => 'وصف الفرصة',
            'main_image' => 'الصورة الرئيسية',
            'area_id' => 'المنطقة',
            'the_attachment.*' => 'المرفق',
            'partner_sort' => 'نوع الشريك',
            'partnership_percentage' => 'نسبة الشراكة',
            'the_tags' => 'الكلمات المفتاحية',
            'weeks_hours' => 'ساعات العمل',
        ];
    }
}
