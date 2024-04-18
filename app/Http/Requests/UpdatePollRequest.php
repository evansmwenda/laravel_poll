<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //check user updating
        $poll = $this->route('poll');
        if($this->user()->id !== $poll->user_id){
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:1000',
            'status' => 'required|boolean',
            'image' => 'nullable|string',
            'user_id' => 'exists:users,id',
            'description' => 'nullable|string|',
            'expiry_date' => 'nullable|date|after:tomorrow',
            'questions' => 'array'
        ];
    }
}
