<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBukuRequest extends StoreBukuRequest
{
  public function rules(): array
    {
        
        $rules = parent::rules();
        
        
        foreach ($rules as $key => $rule) {
            $rules[$key] = str_replace('required', 'sometimes', $rule);
        }
        
        return $rules;
    }
}
