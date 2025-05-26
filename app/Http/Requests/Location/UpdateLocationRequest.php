<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && ( Auth::user()->hasAnyRole(['super admin','admin','organizer']));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name'      => ['nullable','string','max:255'],
            'address'   => ['nullable','string','max:255'],
            'city'      => ['nullable','string','max:255'],
            'capacity'  => ['nullable','integer'],
            'path'      => ['nullable','mimes:jpg,jpeg,png','max:2048'],
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
            'name.max'          => 'The length of the name may not be more than 255 characters',
            'address.max'       => 'The length of the address may not be more than 500 characters',
            'city.max'          => 'The length of the city may not be more than 500 characters',
            'capacity.integer'  => 'The capacity must be an integer',
            'path.mimes'        => 'The image must be a file of type: jpg,jpeg,png',
            'path.max'          => 'The image size must not exceed 2 MB',
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

