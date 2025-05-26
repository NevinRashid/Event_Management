<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
        $rules = [
            'username'   => ['nullable','string','max:255'],
            'first_name' => ['nullable','string','max:255'],
            'last_name'  => ['nullable','string','max:255'],
            'email'      => ['nullable','email',Rule::unique('users')->ignore($this->route('user')),'max:255'],
            'password'   => ['nullable','string','min:8','max:16','confirmed',
                            Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()
                            ],
            'role' => ['nullable','string','max:255','in:super admin,admin,organizer,attendee'],

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
            'username.max'             => 'The length of the username may not be more than 255 characters',
            'first_name.max'           => 'The length of the first name may not be more than 255 characters',
            'last_name.max'            => 'The length of the last name may not be more than 255 characters',
            'email.email'              => 'Please adhere to the email format (example@gmail.com)',
            'email.unique'             => 'The email must be unique and not duplicate. Please use another email',
            'email.max'                => 'The length of the email may not be more than 255 characters',
            'password.min'             => 'Password must be at least 8 characters long',
            'password.max'             => 'The length of the password may not be more than 16 characters',
            'password.confirmed'       => 'Password must be confirmed',
            'password.letters'         => 'Password must contain at least one character',
            'password.mixed'           => 'Password must contain at least one uppercase letter and one lowercase letter',
            'password.numbers'         => 'Password must contain at least one number',
            'password.symbols'         => 'Password must contain at least one character',
            'password.uncompromised'   => 'You should choose a more secure password',
            'role.max'                 => 'The length of the role may not be more than 255 characters',
            'role.in'                  => 'The role You must be one of (super admin,admin,organizer,attendee)',
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

