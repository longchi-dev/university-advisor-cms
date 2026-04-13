<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
//            'image'       => 'required|string|max:5000',
            'validate'    => 'required|string|max:5000',
//            'labels'      => 'required|array',
//            'labels.*.label'    => 'required|string|max:255',
//            'labels.*.label_en' => 'required|string|max:255',
//            'labels.*.themes'   => 'required|array|size:2',
//            'labels.*.themes.*' => 'required|string|max:255',
        ];
    }
}
