<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PathfinderCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'required',
            'full_phone' => 'required',
            'birthday' => 'required',
            'email' => 'required',
            'address' => 'required',
        ];
    }
}
