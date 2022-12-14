<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorsRequest extends FormRequest
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
            'logo' => 'required_without:id|mimes:jpg,jpeg,png',
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:100',
            'mobile' => 'required|max:12|unique:vendors,mobile,'.$this->id,
            'email' => 'required|email|unique:vendors,email,'.$this->id,
            'category_id' => 'required|exists:main_categories,id',
            'password' => 'required_without:id|string|min:6',
        ];
    }

    public function messages()
    {
        return [

            'required' => 'هذا الحقل مطلوب',
            'mobile.unique' => 'هذا الرقم مسجل لدينا من قبل',
            'email.unique' => 'هذا البريد الالكتروني مسجل لدينا من قبل',
            'name.string' => 'اسم اللغة لابد ان يكون احرف',
            'address.string' => 'العنوان لابد ان يكون احرف',
            'max' => 'هذا الحقل طويل  ',
            'category_id.exists' => 'هذا القسم غير موجود  ',
            'email.email' => 'صيغه البريد الالكتروني غير صحيحه ',
            'logo.required_without' => 'الصوره مطلوبه  ',
        ];
    }
}
