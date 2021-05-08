<?php

namespace App\Http\Requests;

use App\Services\AuthService;
use Illuminate\Foundation\Http\FormRequest;

class TestSetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (new AuthService())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.*.id' => 'numeric',
            'data.*.name' => 'string',
            'data.*.param1' => 'integer',
            'data.*.param2' => 'integer',
            'data.*.param3' => 'integer',
        ];
    }
}
