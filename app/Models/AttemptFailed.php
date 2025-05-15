<?php
#---------------------------------------------------------#
#                                                         #
# Program: AttemptFailed.php                              #
# Application: Attempt Failed                             #
# Option: User Attempt Failed                             #
# Initial Version: 2021-02-22                             #
# Developer: Mehul Chaudhari                              #
# Date: 2021-02-22                                        #
#---------------------------------------------------------#
namespace App\Models;

use App\Traits\EncryptDecryptTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class AttemptFailed extends Model
{
    use HasFactory;

    protected $table = 'user_attempt_failed';
    protected $primaryKey = 'attempt_failed_id';

    protected $fillable = [
        'attempt_type',
        'user_id',
        'no_of_attempt',
        'is_blocked',
        'unblock_at',
    ];
    public function getUnblockAtDispAttribute()
    {
        if (is_null($this->attributes['unblock_at']) || $this->attributes['unblock_at'] == "") {
            return "";
        } else {
            return date(config('constants.UNBLOCKED_AT_DATE_FORMAT'), strtotime($this->attributes['unblock_at']));
        }
    }

    public function getFullNameAttribute()
    {
        if (!empty($this->attributes['full_name'])) {
            return EncryptDecryptTrait::decryptData($this->attributes['full_name']);
        } else {
            return '';
        }
    }

    public function getEmailAttribute()
    {
        if (!empty($this->attributes['email'])) {
            return EncryptDecryptTrait::decryptData($this->attributes['email']);
        } else {
            return '';
        }
    }

    /* User login wrong password attempt */
    public function attemptFaild($email)
    {
        try {
            $user = User::where('email', $email)->first();
            if (isset($user)) {
                /* Check user is blocked */
                $isBlocked = $this->isBlocked($user->user_id, config('constants.ATTEMPT_TYPES.LOGIN')); //return 0 or 1
                $msg = '';
                if ($isBlocked) {
                    $msg = Lang::get('users.attempt_failed.login_attempt');
                } else {
                    $attempt = $this->createOrUpdate($user->user_id, config('constants.ATTEMPT_TYPES.LOGIN'));
                    $attemptCount = (config('constants.ATTEMPT_OTP') - $attempt->no_of_attempt);
                    if ($attemptCount >= 1) {
                        $msg = sprintf(Lang::get('users.attempt_failed.wrong_credential'), $attemptCount);
                    } else {
                        $msg = Lang::get('users.attempt_failed.login_no_more_attempt');
                    }
                }
                return successResponse(Response::HTTP_UNAUTHORIZED, $msg);
            } else {
                return errorResponse(Response::HTTP_UNAUTHORIZED, Lang::get('users.auth.invalid'));
            }
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /* Get user attempt */
    public function getAttempt($userId, $attemptType)
    {
        return AttemptFailed::where(['user_id' => $userId, 'attempt_type' => $attemptType])->first();
    }

    /* Check user is blocked return 1 or 0*/
    public function isBlocked($userId, $attemptType)
    {
        $isBlocked = AttemptFailed::where(['user_id' => $userId, 'attempt_type' => $attemptType])->where('unblock_at', '>', now())->first();
        return isset($isBlocked) ? $isBlocked->is_blocked : 0;
    }

    /* Create or update user attempt */
    public function createOrUpdate($userId, $attemptType)
    {
        $attemptFailed = AttemptFailed::where(['user_id' => $userId, 'attempt_type' => $attemptType])->first();
        if (isset($attemptFailed)) {
            $attemptFailed->increment('no_of_attempt');
            $attemptCount = config('constants.ATTEMPT_OTP');
            $attemptTypeArray = [
                config('constants.ATTEMPT_TYPES.RESEND_OTP'),
                config('constants.ATTEMPT_TYPES.RESEND_OTP_FORGOT_PWD'),
            ];
            if (in_array($attemptType, $attemptTypeArray)) {
                $attemptCount = config('constants.ATTEMPT_RESEND_OTP');
            }
            if ($attemptFailed->no_of_attempt >= $attemptCount) {
                $attemptFailed->is_blocked = 1;
                $attemptFailed->unblock_at = Carbon::now()->addDay();
                $attemptFailed->update();
            }
        } else {
            $attemptFailed = new AttemptFailed;
            $attemptFailed->user_id = $userId;
            $attemptFailed->attempt_type = $attemptType;
            $attemptFailed->no_of_attempt = 1;
            $attemptFailed->save();
        }
        return $attemptFailed;
    }

    /**
     * @todo : return blocked user list
     * @function : getBlockedUserList
     * @param : $request | object
     * @return : message with blocked user list
     * @developer : Ravi Tewatia
     */

    public static function getBlockedUserList($request)
    {
        try {
            $perPage = !empty($request->per_page) ? $request->per_page : env("PER_PAGE");
            $sortColumn = !empty($request->sort_column) ? $request->sort_column : "uaf.created_at";
            $sortOrder = !empty($request->sort_order) ? $request->sort_order : "DESC";
            $search = empty($request->filter_value) ? '' : $request->filter_value;
            $encryptKey = env('ENCRYPT_KEY');
            $query = AttemptFailed::from("user_attempt_failed as uaf")
                ->join('users as u', "uaf.user_id", "u.user_id")
                ->selectRaw("u.full_name,u.email,u.slug")
                ->addSelect("unblock_at", "uaf.unblock_at as unblock_at_disp")
                ->where("uaf.is_blocked", config('constants.IS_BLOCKED'));
            if (!empty($search)) {
                $query->where(function ($query) use ($search, $encryptKey) {
                    $query->whereRaw("AES_DECRYPT(from_base64(u.full_name),'$encryptKey') like '%$search%'");
                    $query->orWhereRaw("AES_DECRYPT(from_base64(u.email),'$encryptKey') like '%$search%'");
                });
            }
            $blockedUserList = $query->orderBy($sortColumn, $sortOrder)
                ->paginate($perPage);
            if ($blockedUserList) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $blockedUserList);
            } else {
                return errorResponse(Response::HTTP_NOT_FOUND, Lang::get('messages.HTTP_NOT_FOUND'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**
     * @todo : unblock blocked user immidiatly
     * @function : unblockedUser
     * @param : $request | object
     * @return : message with blocked user list
     * @developer : Ravi Tewatia
     * */
    public function unblockedUser($request)
    {
        try {
            $userSlug = !empty($request->user_token) ? $request->user_token : "";
            $userId = getKeyBySlug('users', 'user_id', $userSlug);
            $unblockedUser = AttemptFailed::where("user_id", $userId)->delete();
            if ($unblockedUser) {
                return successResponse(Response::HTTP_ACCEPTED, Lang::get('users.action.unblocked'));
            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.action.unblocked_error'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }
}
