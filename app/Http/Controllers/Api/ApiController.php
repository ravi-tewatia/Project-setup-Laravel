<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    protected $errorMessage;
    /**********************************************************
     * return : check validation and retuen array of errors if validation fails
     * developer : Ravi Tewatia
     **********************************************************/
    public function checkValidation($request, $params = [], $messages = [])
    {
        $validator = Validator::make(
            $request->all(),
            $params,
            !empty($messages) ? $messages : Lang::get('validation')
        );
        if ($validator->fails()) {
            $this->errorMessage['errorDetail'] = [];
            foreach ($validator->errors()->getMessages() as $key => $message) {
                $this->errorMessage['errorDetail'][] = [
                    'errorField' => $key,
                    'errorMessage' => $message,
                ];
            }
            return 0;
        } else {
            return 1;
        }
    }
}
