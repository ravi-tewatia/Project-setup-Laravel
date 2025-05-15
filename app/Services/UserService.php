<?php

namespace App\Services;

use App\Models\User;
use App\Models\AttemptFailed;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    /**********************************************************
     * return : login user is user is activate otherwise throw error
     * developer : Ravi Tewatia
     **********************************************************/
    public function login($request)
    {
        try {
            $email = $request->email;
            $user = User::where('email', $email)->first();
            if ($user->status_id == config('constants.PENDING_ACTIVATION')) {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('messages.USER_ACTIVATION_PENDING'));
            } elseif ($user->status_id == config('constants.STATUS_DRAFT')) {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('messages.USER_APPROVE_PENDING'));
            } elseif (in_array($user->status_id, [config('constants.STATUS_INACTIVE'), config('constants.STATUS_DELETE'), config('constants.STATUS_REJECTED')])) {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('messages.INVALID_CREDENTIAL'));
            }
            $attemptFailed = new AttemptFailed;
            $isBlocked = $attemptFailed->isBlocked($user->user_id, config('constants.ATTEMPT_TYPES.LOGIN'));
            if ($isBlocked) {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.attempt_failed.login_attempt'));
            }
            return $this->loginAccess($user->user_id);
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * return : if user credentials are match than update attampt table entry and return header token
     * developer : Ravi Tewatia
     **********************************************************/
    public function loginAccess($userId)
    {
        $user = new User;
        $response = $user->getToken($userId);
        if (!empty($response['result']['headerToken'])) {
            AttemptFailed::where('user_id', $userId)->update([
                'no_of_attempt' => 0,
                'is_blocked' => 0,
                'unblock_at' => null,
            ]);
            return successResponse(Response::HTTP_OK, Lang::get('users.auth.login_success'), [
                'headerToken' => $response['result']['headerToken'],
                "user_data" => $response['result']['user_data'],
                "permissions" => $response['result']['permissions'],
            ]);
        } else {
            return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.user.user_not_found'));
        }
    }
}
