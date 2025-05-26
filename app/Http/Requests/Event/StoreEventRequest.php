<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && ( Auth::user()->hasAnyRole(['super admin','admin']));
    }

    /**
     * Prepare the data for validation.
     * 
     * @return void
     */
    protected function prepareForValidation(){
    
        $this->merge([
                'status' => $this->status ?:'draft'
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
            'name'              => ['required','string','max:255'],
            'description'       => ['required','string','max:500'],
            'event_date'        => ['required','date','after:today'],
            'start_time'        => ['required','date'],
            'end_time'          => ['required','date'],
            'capacity'          => ['required','integer'],
            'status'            => ['required','string','max:255','in:draft,pending,cancelled,published,completed'],
            'location_id'       => ['required','exists:locations,id'],
            'event_type_id'     => ['required','exists:event_types,id'],
            'organizer_id'      => ['nullable','exists:users,id'],
            'created_by'        => ['nullable','exists:users,id'],
            'images'            => ['nullable','mimes:jpg,jpeg,png','max:2048'],
            'path'              => ['mimes:jpg,jpeg,png','max:2048'],
            'attendee_ids'      => ['nullable','array'],
            'attendee_ids.*'    => ['integer','exists:users,id'],
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
            'name.required'             => 'The name is required please',
            'name.max'                  => 'The length of the name may not be more than 255 characters',
            'description.required'      => 'The description is required please',
            'description.max'           => 'The length of the description may not be more than 500 characters',
            'event_date.required'       => 'The event date is required please',
            'event_date.date'           => 'The event date must be a date ',
            'event_date.after'          => 'The event date must be a future date.',
            'start_time.required'       => 'The start time is required please',
            'start_time.date'           => 'The start time must be a date ',
            'end_time.required'         => 'The end time is required please',
            'end_time.date'             => 'The end time must be a date ',
            'capacity.required'         => 'The capacity is required please',
            'capacity.integer'          => 'The capacity must be an integer',
            'status.required'           => 'The status is required please',
            'status.max'                => 'The length of the status may not be more than 255 characters',
            'status.in'                 => 'The status You must be one of (draft,pending,cancelled,published,completed)',
            'location_id.required'      => 'The location is required please',
            'location_id.exists'        =>  'The value entered does not exist in the locations table.',
            'event_type_id.required'    => 'The event type is required please',
            'event_type_id.exists'      => 'The value entered does not exist in the event_types table.',
            'organizer_id.exists'       => 'The value entered does not exist in the users table.',
            'created_by.exists'         => 'The value entered does not exist in the users table.',
            'attendee_ids.array'        => 'The attendee ids field must be an array.',
            'attendee_ids.exists'       => 'The values entered does not exist in the users table.',
            'path.mimes'                => 'The image must be a file of type: jpg,jpeg,png',
            'path.max'                  => 'The image size must not exceed 2 MB',
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
