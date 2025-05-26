<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     * 
     * @return void
     */
    protected function prepareForValidation(){
    
        $this->merge([
                'role' => $this->role ?:'attendee'
                ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'username'      => ['required','string','max:255'],
            'first_name'    => ['required','string','max:255'],
            'last_name'     => ['required','string','max:255'],
            'email'         => ['required','email','unique:users','max:255'],
            'password'      => ['required','string','min:8','max:16','confirmed',
                                Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()
                                ],
            'role'          => ['required','string','max:255','in:super admin,admin,organizer,attendee'],
            'phone_number'  => ['required','string','max:255'],

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
            'username.required'        => 'The username is required please',
            'username.max'             => 'The length of the username may not be more than 255 characters',
            'first_name.required'      => 'The first name is required please',
            'first_name.max'           => 'The length of the first name may not be more than 255 characters',
            'last_name.required'       => 'The last name is required please',
            'last_name.max'            => 'The length of the last name may not be more than 255 characters',
            'email.required'           => 'The Email is required please',
            'email.email'              => 'Please adhere to the email format (example@gmail.com)',
            'email.unique'             => 'The email must be unique and not duplicate. Please use another email',
            'email.max'                => 'The length of the email may not be more than 255 characters',
            'password.required'        => 'The password is required please',
            'password.min'             => 'Password must be at least 8 characters long',
            'password.max'             => 'The length of the password may not be more than 16 characters',
            'password.confirmed'       => 'Password must be confirmed',
            'password.letters'         => 'Password must contain at least one character',
            'password.mixed'           => 'Password must contain at least one uppercase letter and one lowercase letter',
            'password.numbers'         => 'Password must contain at least one number',
            'password.symbols'         => 'Password must contain at least one character',
            'password.uncompromised'   => 'You should choose a more secure password',
            'role.required'            => 'The role is required please',
            'role.max'                 => 'The length of the role may not be more than 255 characters',
            'role.in'                  => 'The role You must be one of (super admin,admin,organizer,attendee)',
            'phone_number.required'    => 'The phone number is required please',
            'phone_number.max'         => 'The length of the phone number may not be more than 255 characters',
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
