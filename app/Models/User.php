<?php

namespace App\Models;

use App\Exports\CommonExport;
use App\Models\ApiModel;
use App\Models\AttemptFailed;
use App\Models\PasswordReset;
use App\Traits\DateFormatTrait;
use App\Traits\EncryptDecryptTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Maatwebsite\Excel\Facades\Excel;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use DateFormatTrait;

    public $imagePath;
    protected $table = "users";
    protected $primaryKey = 'user_id';
    public $refTableId = 1;
    protected $fillable = [
        'slug', 'username', 'full_name', 'email', 'phone', 'password', 'profile_thumb', 'street_address', 'city', 'postal_code', 'state', 'custom_data', 'status_id', 'activated_at', 'created_by', 'updated_by', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public $userApproveList = ['slug', 'full_name', 'email', 'phone', 'profile_thumb', 'status_id', 'street_address', 'city', 'state', 'postal_code'];
    public $profileSelect = ['slug', 'full_name', 'email', 'phone', 'profile_thumb', 'status_id', 'street_address', 'city', 'state', 'postal_code'];
    public $pendingUserSelect = ['slug', 'full_name', 'email', 'phone', 'profile_thumb', 'status_id', 'street_address', 'city', 'state', 'postal_code'];
    protected $hidden = [
        'user_id',
        'password',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_thumb_url', 'status_id_disp', 'full_address',
    ];

    public function __construct()
    {
        $this->imagePath = config('path.IMAGE_PATH');
    }

    public function getEmailAttribute()
    {
        if (!empty($this->attributes['email'])) {
            return EncryptDecryptTrait::decryptData($this->attributes['email']);
        } else {
            return '';
        }
    }
    public function getPhoneAttribute()
    {
        if (!empty($this->attributes['phone'])) {
            return EncryptDecryptTrait::decryptData($this->attributes['phone']);
        } else {
            return '';
        }
    }
    public function getUsernameAttribute()
    {
        if (!empty($this->attributes['username'])) {
            return EncryptDecryptTrait::decryptData($this->attributes['username']);
        } else {
            return '';
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
    public function getProfileThumbUrlAttribute()
    {
        if (is_null($this->attributes['profile_thumb']) || $this->attributes['profile_thumb'] == "") {
            return "";
        } else {
            return Storage::url($this->imagePath . '/' . $this->attributes['profile_thumb']);
        }
    }

    public function getStatusIdDispAttribute()
    {
        if (is_null($this->attributes['status_id']) || $this->attributes['status_id'] == "") {
            return "Deleted";
        } else {
            #1-Active,2-Inactive,3-Deleted,4-Pending Activation,5 Draft
            switch ($this->attributes['status_id']) {
                case '1':
                    return 'Active';
                    break;
                case '2':
                    return 'Inactive';
                    break;
                case '3':
                    return 'Deleted';
                    break;
                case '4':
                    return 'Pending Activation';
                    break;
                case '5':
                    return 'Draft';
                    break;
                default:
                    return 'Draft';
                    break;
            }
        }
    }

    public function getFullAddressAttribute()
    {
        return $this->attributes['street_address'] . ',<br>' . $this->attributes['city'] . ',' . $this->attributes['state'] . '-' . $this->attributes['postal_code'] . '.';
    }

    public function register($request)
    {
        $response = [];
        try {
            $insArray = $request->only($this->fillable);
            $apiModel = new ApiModel;
            $slug = $apiModel->slug();
            $userId = Auth::id();
            $insArray['username'] = !empty($insArray['email']) ? $insArray['email'] : '';
            $insArray['full_name'] = !empty($insArray['full_name']) ? EncryptDecryptTrait::encryptData($insArray['full_name']) : '';
            $insArray['email'] = !empty($insArray['email']) ? $insArray['email'] : '';
            $insArray['phone'] = !empty($insArray['phone']) ? EncryptDecryptTrait::encryptData($insArray['phone']) : '';
            $insArray['slug'] = $slug;
            $insArray['created_by'] = !empty($userId) ? $userId : 1;
            $insArray['password'] = Hash::make($insArray['password']);
            $insertedId = User::insertGetId($insArray);
            if ($insertedId) {
                $msg = Str::replaceArray(':value', [$request->full_name], Lang::get('users.approve.title'));
                return successResponse(Response::HTTP_OK, $msg);

            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('messages.INSERT_ERROR'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * return : when user click on activation link and token is valid than user account is activate.
     * developer : Ravi Tewatia
     **********************************************************/
    public function accountActivation($activationToken)
    {
        try {
            $forgotPassword = PasswordReset::where([
                'token' => $activationToken,
                'token_type' => config('constants.TOKEN_TYPES.NEW_USER'),
            ])->first();
            if (!$forgotPassword) {
                return errorResponse(Response::HTTP_NOT_FOUND, Lang::get('users.user.activation_token_invalid'));
            }
            $isActive = User::where('user_id', $forgotPassword->user_id)->where('status_id', config('constants.STATUS_ACTIVE'))->first();
            if (!$isActive) {
                if ($forgotPassword) {
                    $response = PasswordReset::checkTokenValidity($activationToken);
                    if ($response['status'] != Response::HTTP_OK) {
                        return $response;
                    }
                    $userId = $forgotPassword->user_id;
                    $userUdt = User::where('user_id', $userId)->update([
                        'status_id' => config('constants.STATUS_ACTIVE'),
                        'activated_at' => now(),
                        'updated_by' => $userId,
                    ]);
                    if ($userUdt) {

                        return successResponse(Response::HTTP_ACCEPTED, Lang::get('users.user.activation_success'));
                    } else {
                        return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.user.activation_wrong'));
                    }
                } else {
                    return errorResponse(Response::HTTP_NOT_FOUND, Lang::get('users.user.activation_token_invalid'));
                }
            } else {
                return errorResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('users.user.already_active'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }
    /**********************************************************
     * return : query with appending other columns
     * developer : Ravi Tewatia
     **********************************************************/
    public static function appendSelectQuery($query)
    {
        $query->addSelect(
            'profile_thumb as profile_thumb_url',
            'status_id as status_id_disp',
            'street_address', 'city', 'postal_code', 'state as full_address'
        );
        return $query;
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

    /**********************************************************
     * return : create header token using createToken function
     * developer : Ravi Tewatia
     **********************************************************/
    public function getToken($userId)
    {
        try {
            $user = User::where('user_id', $userId)->select($this->profileSelect)->addSelect('user_id')->first();
            if (!empty($user)) {
                $token = $user->createToken('authToken');
                $token = $token->plainTextToken;
                $userType = $user->user_group == config('constants.SELLER') ? "seller" : ($user->user_group == config('constants.BUYER') ? "buyer" : "admin");
                $permissionArr = config('permissions.' . $userType);
                return successResponse(Response::HTTP_OK, Lang::get('users.user.header_token_success'), [
                    'headerToken' => $token,
                    "user_data" => $user,
                    "permissions" => $permissionArr,
                ]);
            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.user.user_not_found'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * return : send reset password link when email is valid and in database.
     * developer : Ravi Tewatia
     **********************************************************/
    public function forgotPassword($request)
    {
        try {
            $email = EncryptDecryptTrait::encryptData(strtolower($request->email));
            $user = User::where('email', $email)->where('status_id', '=', config('constants.STATUS_ACTIVE'))->first();
            if (isset($user) && !empty($user)) {
                $fullName = $user->full_name;
                $attemptFailed = new AttemptFailed;
                $isBlocked = $attemptFailed->isBlocked($user->user_id, config('constants.ATTEMPT_TYPES.LOGIN'));
                if ($isBlocked) {
                    return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.attempt_failed.login_attempt'));
                }
                $response = PasswordReset::generateForgotToken($user, config('constants.TOKEN_TYPES.FORGOT_TOKEN'));
                if ($response['status'] != Response::HTTP_OK) {
                    return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('messages.TOKEN_NOT_GENERATED'));
                }
                $token = $response['result']['password_token'];
                $details = [
                    'title' => Str::replaceArray(':value', [$fullName], Lang::get('users.forget_password.title')),
                    "subject" => Lang::get('users.forget_password.subject'),
                    'url' => env('FRONTEND_URL') . Str::replaceArray(':value', [$token], Lang::get('users.forget_password.link')),
                    'buttonname' => Lang::get('users.forget_password.buttonname'),
                    'note' => Lang::get('users.forget_password.note'),
                ];
                $apiModel = new ApiModel;
                return $apiModel->sendInstantMail($request->email, $details, "forget password", "users.forget_password");
            } else {
                return errorResponse(Response::HTTP_NOT_FOUND, Lang::get('users.user.forgot_user_not_found'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * return : when user click reset password than open reset password form and able to set new password when reserttoken is valid
     * developer : Ravi Tewatia
     **********************************************************/
    public function resetPassword($request)
    {
        try {
            $resetToken = $request->reset_token;
            $newPassword = $request->new_password;
            $response = PasswordReset::checkTokenValidity($resetToken);
            if ($response['status'] != Response::HTTP_OK) {
                return $response;
            }
            $userId = $response['result']['userId'];
            $user = User::where('user_id', $userId)->first();
            $user->password = Hash::make($newPassword);
            $user->updated_by = $userId;
            if ($user->update()) {
                return successResponse(Response::HTTP_ACCEPTED, Lang::get('users.user.reset_forgot_success'));
            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.user.not_reset'));
            }

        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * return : user change password when old password is match with database and user is logged in.
     * developer : Ravi Tewatia
     **********************************************************/
    public function changePassword($request)
    {
        try {
            $oldPassword = $request->old_password;
            $newPassword = $request->new_password;
            $user = User::where('user_id', Auth::id())->first();
            if (Hash::check($oldPassword, $user->password)) {
                $user->password = Hash::make($newPassword);
                $user->updated_by = Auth::id();
                if ($user->update()) {
                    return successResponse(Response::HTTP_ACCEPTED, Lang::get('users.user.change_success'));
                } else {
                    return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.user.not_change'));
                }
            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.user.password_not_metch'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * return : get profile of current user which is logged in.
     * developer : Ravi Tewatia
     **********************************************************/
    public function getProfile()
    {
        /** ! for getting user profile with addresses for show and edit */
        try {
            $result = User::whereIn('status_id', [
                config('constants.STATUS_ACTIVE'),
                config('constants.STATUS_INACTIVE'),
            ])
                ->where('user_id', Auth::id())
                ->select(
                    $this->fillable
                )
                ->first();
            if ($result) {
                return successResponse(Response::HTTP_OK, Lang::get('users.user.get_profile'), $result);

            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.user.get_profile_error'));

            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * return : get list of users.
     * developer : Ravi Tewatia
     **********************************************************/
    public function getUserList()
    {
        /** ! for getting user profile with addresses for show and edit */
        try {
            $result = User::whereIn('status_id', [
                config('constants.STATUS_ACTIVE'),
                config('constants.STATUS_INACTIVE'),
            ])
                ->select(
                    $this->fillable
                )
                ->get();
            if ($result) {
                return successResponse(Response::HTTP_OK, Lang::get('users.user.get_profile'), $result);

            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.user.get_profile_error'));

            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /* Update Profile */
    public function updateProfile($request, $id)
    {
        try {
            /*  update data into users table */
            $updateData = $request->except(['plain_email', 'email', 'password', 'slug', 'status_id']);
            $updateData['updated_by'] = $id;
            if (!empty($updateData['email'])) {
                $updateData['username'] = $updateData['email'];
            }
            if (!empty($updateData['full_name'])) {
                $updateData['full_name'] = EncryptDecryptTrait::encryptData($updateData['full_name']);
            }
            if (!empty($updateData['phone'])) {
                $updateData['phone'] = EncryptDecryptTrait::encryptData($updateData['phone']);
            }
            /* slug remove from array */
            if (!empty($updateData['slug']) || empty($updateData['slug'])) {
                unset($updateData['slug']);
            }
            $userResult = User::where('user_id', $id)->first();
            $result = $userResult->update($updateData);
            if ($result) {
                return successResponse(Response::HTTP_OK, Lang::get('users.user.update_profile_success'));
            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('users.user.update_profile_error'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * return : upload profile image and return uploaded image name.
     * developer : Ravi Tewatia
     **********************************************************/
    public function uploadProfileImage($request)
    {
        try {
            $apiModel = new ApiModel;
            return $apiModel->uploadFile($request, 'profile_thumb', $this->imagePath);
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * return : get pending user list for admin when status is draft
     * developer : Ravi Tewatia
     **********************************************************/
    public function pendingUserList($request)
    {
        try {
            $perPage = !empty($request->per_page) ? $request->per_page : env("PER_PAGE");
            $sortColumn = !empty($request->sort_column) ? $request->sort_column : "created_at";
            $sortOrder = !empty($request->sort_order) ? $request->sort_order : "DESC";
            $query = User::where('status_id', config('constants.STATUS_DRAFT'))
                ->select($this->pendingUserSelect);
            $query = User::appendSelectQuery($query);
            $result = $query->orderBy($sortColumn, $sortOrder)->paginate($perPage);
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $result);
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * return : admin approved or reject vendor or buyer and send activation mail
     * developer : Ravi Tewatia
     **********************************************************/
    public function pendingUserApproveAction($request)
    {
        try {
            if (!empty($request->note)) {
                $statusId = config('constants.STATUS_REJECTED');
                $note = $request->note;
            } else {
                $statusId = config('constants.PENDING_ACTIVATION');
                $note = null;
            }
            $updated = User::where('slug', $request->activation_token)->update([
                'status_id' => $statusId,
                'rejection_note' => $note,
                'updated_by' => Auth::id(),
                'updated_at' => now(),
            ]);
            $user = User::where('slug', $request->activation_token)->first();
            if ($updated && !empty($request->note)) {
                $details = [
                    'title' => Str::replaceArray(':value', [$user->full_name, $request->note], Lang::get('users.user_rejection.title')),
                    "subject" => Lang::get('users.user_rejection.subject'),
                    'url' => "",
                    'buttonname' => "",
                    'note' => Lang::get('users.user_rejection.note'),
                ];
                $apiModel = new ApiModel;
                return $apiModel->sendInstantMail($user->email, $details, "user rejection", "users.user_rejection");
            } else if ($updated && empty($request->note)) {
                $response = PasswordReset::generateForgotToken($user, config('constants.TOKEN_TYPES.NEW_USER'));
                if ($response['status'] == Response::HTTP_OK) {
                    $token = $response['result']['password_token'];
                    $details = [
                        'title' => Str::replaceArray(':value', [$user->full_name], Lang::get('users.register.title')),
                        "subject" => Lang::get('users.register.subject'),
                        'url' => url(Str::replaceArray(':value', [$token], Lang::get('users.register.link'))),
                        'buttonname' => Lang::get('users.register.buttonname'),
                        'note' => Lang::get('users.register.note'),
                    ];
                    $apiModel = new ApiModel;
                    return $apiModel->sendInstantMail($user->email, $details, "register", "users.register");
                }
            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('messages.INSERT_ERROR'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }
    /**********************************************************
     * return : logout and delete current access token from personal access token table
     * developer : Ravi Tewatia
     **********************************************************/
    public function userLogout($request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return successResponse(Response::HTTP_OK, Lang::get('users.user.user_logout_success'));
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /* export function */

    /**********************************************************
     * return : export buyer seller data
     * developer : Ravi Tewatia
     **********************************************************/
    public function exportUserList($request)
    {
        $exportData = [];
        try {
            $result = $footer = $subHeader = [];
            $inputAll['is_export'] = 1;
            $usersData = $this->getUserList($request)['result'];
            if (empty($usersData)) {
                return errorResponse(Response::HTTP_NOT_FOUND, Lang::get('messages.HTTP_NOT_FOUND'));
            } else {
                foreach ($usersData as $key => $value) {
                    $exportData[$key][] = $value['full_name'];
                    $exportData[$key][] = $value['email'];
                    $exportData[$key][] = $value['phone'];
                    $exportData[$key][] = strip_tags($value['full_address']);
                    $exportData[$key][] = $value['status_id_disp'];
                }
                $exportStaffFolder = config('path.USERS_EXPORT_PATH');
                if (!Storage::exists($exportStaffFolder)) {
                    $file = Storage::makeDirectory($exportStaffFolder, 0777, true);
                }
                $fileName = 'usersData_' . uniqid() . '.xlsx';
                $header = [
                    ['name' => 'FULL NAME', 'rowSpan' => 1, 'colSpan' => 1],
                    ['name' => 'EMAIL', 'rowSpan' => 1, 'colSpan' => 1],
                    ['name' => 'PHONE', 'rowSpan' => 1, 'colSpan' => 1],
                    ['name' => 'ADDRESS', 'rowSpan' => 1, 'colSpan' => 1],
                    ['name' => 'STATUS', 'rowSpan' => 1, 'colSpan' => 1],
                ];
                $sheetTitle = 'usersData - ' . date('d-m-Y');
                $file = Excel::store(
                    new CommonExport($exportData, $header, $subHeader, $footer, $sheetTitle),
                    $exportStaffFolder . $fileName,
                    'public'
                );
                if ($file == true) {
                    $result['fileName'] = $fileName;
                    $result['filePath'] = Storage::url($exportStaffFolder . $fileName);
                    return successResponse(Response::HTTP_OK, Lang::get('messages.EXPORT'), $result);
                } else {
                    return errorResponse(Response::HTTP_EXPECTATION_FAILED, Lang::get('messages.EXPORT_ERROR'));
                }
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
     * @todo : generate pdf from view or manually create html variable.
     * @function : getUserListPdf
     * @param : $int|array
     * @return : message with filename and filepath
     * @developer : Ravi Tewatia
     */
    public function getUserListPdf($request)
    {
        $response = [];
        try {
            $viewName = config('constants.DEMO_PDF_VIEW_NAME');
            $fileName = "userData" . uniqid() . ".pdf";
            $filePath = config('path.USERS_EXPORT_PDF_PATH');
            $resultData = $this->getUserList()['result'];
            // return view('pdf.demo-pdf-export')->with(["result" => $returnResponse])->render();
            $apiModel = new ApiModel;
            $resultPdf = $apiModel->generatePdfReport($viewName, $filePath, $fileName, $resultData->toArray());
            $result['report'][] = [
                'fileName' => $fileName,
                'filePath' => Storage::url($filePath . $fileName),
            ];
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $result);
        } catch (\Exception $ex) {
            $response = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $ex->getMessage(),
                'result' => [],
            ];
        }
        return $this->response($response['status'], $response['message'], $response['result']);
    }
}
