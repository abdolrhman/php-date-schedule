<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GenerateDatesScheduler extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "NoOfDaysToFinishChapter" => 'required|integer',
            //could add date date_format:Y-m-d"
            "startDate" => 'required|date',
            "daysOfWeekToFinishBook" => 'required|array',
            "daysOfWeekToFinishBook.*" => 'integer'
        ];
    }

    /**
     * Get the error messages that apply to the request parameters.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'NoOfDaysToFinishChapter.required' => 'NoOfDaysToFinishChapter field is required',
            'startDate.required' => 'startDate field is required',
            'daysOfWeekToFinishBook.required' => 'daysOfWeekToFinishBook field is required',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
