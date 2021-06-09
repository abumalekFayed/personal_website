<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeavingPolicyRequest extends FormRequest
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
            'title'=>'required|string',
            'title_en'=>'required|string',
            'max_days_number'=>'required|numeric',
            'max_successive_days_number'=>'required|numeric',
            'applicable_after'=>'required|numeric',
            'color'=>'required|string',
            'description'=>'present'
        ];
    }
}
