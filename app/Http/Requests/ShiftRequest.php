<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftRequest extends FormRequest
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
            'start_time'=>'required|date_format:H:i',
            'end_time'=>'required|date_format:H:i',
            'start_at'=>'required|date_format:H:i',
            'end_at'=>'required|date_format:H:i',
            'week_days'=>'required|array',
            'late_after'=>'required|numeric',
            'start_time2'=>'date_format:H:i',
            'end_time2'=>'date_format:H:i',
            'start_at2'=>'date_format:H:i',
            'end_at_at2'=>'date_format:H:i',
        ];
    }
}
