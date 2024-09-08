<?php

namespace App\Http\Requests;

use App\Http\Controllers\TaskController;
use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{

    use ResponseHandler;

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        // $errors = $validator->errors();
        throw new HttpResponseException($this->redirectWithError('An error just happened, invalid parameters.', __METHOD__, TaskController::class, 400));
    }
}
