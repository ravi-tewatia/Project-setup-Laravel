<?php

namespace App\Models;

use App\Traits\EncryptDecryptTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class PasswordReset extends Model
{
    use HasFactory;
    protected $table = "password_resets";
    protected $fillable = [
        'user_id',
        'email',
        'token',
        'token_type',
        'token_validity',
    ];

    public static function generateForgotToken($user, $tokenType)
    {
        try {
            $forgotPassword = new PasswordReset;
            $forgotPassword->user_id = $user->user_id;
            $forgotPassword->email = EncryptDecryptTrait::encryptData($user->email);
            $forgotPassword->token = Str::random(32);
            $forgotPassword->token_type = $tokenType;
            $tokenValidity = Carbon::now()->addMinute(env('RESET_PASSWORD_LINK_LIFETIME'))->toDateTimeString();
            $forgotPassword->token_validity = $tokenValidity;
            if ($forgotPassword->save()) {
                return successResponse(Response::HTTP_OK, Lang::get('users.forgot_password.token_success'), ['password_token' => $forgotPassword->token]);
            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.forgot_password.token_error'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }
    /* Check token validity */
    public static function checkTokenValidity($token)
    {
        $forgotPassword = PasswordReset::where(['token' => $token])
            ->orderBy('created_at', 'desc')->first();
        if (!empty($forgotPassword)) {
            $end = new Carbon($forgotPassword->token_validity);
            $start = Carbon::now();
            if ($start <= $end) {
                PasswordReset::where(['token' => $token])->delete();
                return successResponse(Response::HTTP_OK, Lang::get('users.forgot_password.token_verify_success'), ['userId' => $forgotPassword->user_id]);
            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.forgot_password.token_time_expire'));
            }
        } else {
            return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.forgot_password.invalide_otpToken'));
        }
    }
}
