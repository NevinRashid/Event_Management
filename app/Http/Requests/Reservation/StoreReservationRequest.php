<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && ( Auth::user()->hasAnyRole(['super admin','admin','organizer','attendee']));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'status'    => ['required','string','in:confirmed,cancelled,pending','max:255'],
            'user_id'   => ['required','exists:users,id'],
            'event_id'  => ['required','exists:events,id'],
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
            'status.required'   => 'The status is required please',
            'status.max'        => 'The length of the status may not be more than 255 characters',
            'status.in'         => 'The status You must be one of (confirmed,cancelled,pending)',
            'user_id.required'  => 'The user who booked the reservation is required please',
            'user_id.exists'    => 'The value entered does not exist in the users table.',
            'event_id.required' => 'The event is required please',
            'event_id.exists'   =>  'The value entered does not exist in the events table.',
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
