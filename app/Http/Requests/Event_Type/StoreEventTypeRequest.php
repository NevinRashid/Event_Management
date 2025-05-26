<?php

namespace App\Http\Requests\Event_Type;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreEventTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && ( Auth::user()->hasAnyRole(['super admin','admin']));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name'        => ['required','string','max:255'],
            'description' => ['required','string','max:500'],
        ];
        return $rules;
    }

    /**
     *  Get the error messages for the defined validation rules.
     * 
     *  @return array<string, string>
     */
    public function messages():array
    {
        return[
            'name.required'        => 'The name is required please',
            'name.max'             => 'The length of the name may not be more than 255 characters',
            'description.required' => 'The description is required please',
            'description.max'      => 'The length of the description may not be more than 500 characters',
        ];
    }

        /**
     * Handle a failed validation attempt.
     * 
     * @param  \Illuminate\Validation\Validator  $validator
     * 
     * @return void
     */
    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json
            ([
                'success' => false,
                'message' => 'Data validation error',
                'errors'  => $validator->errors()
            ] , 422));
    }

}
