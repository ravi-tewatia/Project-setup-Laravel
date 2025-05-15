<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\AttemptFailed;
use App\Models\User;
use App\Services\UserService;
use App\Traits\EncryptDecryptTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class AuthController extends ApiController
{
    protected $user;
    protected $userService;

    public function __construct()
    {
        $this->user = new User;
        $this->userService = new UserService;
    }

    /**
     * @todo : validate email
     * @function : validateUserEmail
     * @param : $request | object
     * @return : message with validate email
     * @flow : verify email with db
     * @developer : Ravi Tewatia
     */
    public function validateUserEmail(Request $request)
    {
        try {
            $request->merge([
                'plain_email' => strtolower($request->email),
                'email' => EncryptDecryptTrait::encryptData(strtolower($request->email)),
            ]);
            $unique = Rule::unique('users')->where(function ($query) {
                $query->where('status_id', '<>', config('constants.STATUS_DELETE'))
                    ->where('status_id', '<>', config('constants.STATUS_REJECTED'));;
            });
            $requiredFields = [
                'email' => ['required', $unique],
                'plain_email' => ['email'],
            ];
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                return finalResponse(successResponse(Response::HTTP_ACCEPTED, Lang::get('messages.HTTP_ACCEPTED')));
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
     * method : Post
     * api : register
     * return : message of data save and in pending activation
     * developer : Ravi Tewatia
     **********************************************************/
    public function register(Request $request)
    {
        $response = [];
        try {
            /* require validation fields  */
            $request->merge([
                'plain_email' => $request->email,
                'email' => EncryptDecryptTrait::encryptData($request->email),
            ]);
            $unique = Rule::unique('users')->where(function ($query) {
                $query->where('status_id', '<>', config('constants.STATUS_DELETE'));
            });
            $requiredFields = [
                'full_name' => 'required',
                'phone' => ['required'],
                'state' => ['required'],
                'postal_code' => ['required'],
                'email' => ['required', $unique],
                'password' => 'required|required_with:password_confirmation|same:password_confirmation',
            ];
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            }
            $returnResponse = $this->user->register($request);
            return finalResponse($returnResponse);
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * method : Get
     * api : pending-user-list
     * return : pending activation link where user status is in draft
     * developer : Ravi Tewatia
     **********************************************************/
    public function pendingUserList(Request $request)
    {
        try {
            $return = $this->user->pendingUserList($request);
            return finalResponse($return);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * method : Post
     * api : pending-user-approve-action
     * return : send activation link mail whose status is updated to 4 other wise rejected with note.
     * developer : Ravi Tewatia
     **********************************************************/
    public function pendingUserApproveAction(Request $request)
    {
        try {
            $requiredFields = [
                'activation_token' => 'required',
            ];
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            }
            $return = $this->user->pendingUserApproveAction($request);
            return finalResponse($return);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * method : Post
     * api : account-activation
     * return : return activation message if valid activation token
     * developer : Ravi Tewatia
     **********************************************************/
    public function accountActivation($activationToken)
    {
        try {
            $activationResponse = $this->user->accountActivation($activationToken);
            return view('auth.account-activate')->with('result', $activationResponse);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * method : Post
     * api : login
     * return : retuen header token if credentials match other wise throw error
     * developer : Ravi Tewatia
     **********************************************************/
    public function login(Request $request)
    {
        try {
            /* validation */
            $requiredFields = [
                'email' => 'required',
                'password' => 'required',
            ];
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            }
            $encryptedEmail = EncryptDecryptTrait::encryptData(strtolower($request->plain_email));
            $credential = ["email" => $encryptedEmail, "password" => $request->password];
            if (!Auth::attempt($credential)) {
                $attemptFailed = new AttemptFailed;
                $return = $attemptFailed->attemptFaild($request->email);
                return finalResponse($return);
            }
            $returnResponse = $this->userService->login($request);
            return finalResponse($returnResponse);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * method : Post
     * api : forgot-password
     * return : return message when mail send with activation link.
     * developer : Ravi Tewatia
     **********************************************************/
    public function forgotPassword(Request $request)
    {
        $requiredFields = [
            'email' => 'required',
        ];
        $response = [];
        try {
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            }
            $return = $this->user->forgotPassword($request);
            return finalResponse($return);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * method : Post
     * api : reset-password
     * return : chnage password if token is valid otherwise throw error
     * developer : Ravi Tewatia
     **********************************************************/
    public function resetPassword(Request $request)
    {
        $requiredFields = [
            'reset_token' => 'required',
            'new_password' => 'required',
        ];

        try {
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            }
            /* reset forgot password */
            $return = $this->user->resetPassword($request);
            return finalResponse($return);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    public function changePassword(Request $request)
    {

        try {
            $requiredFields = [
                'old_password' => 'required',
                'new_password' => 'required|different:old_password',
            ];
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            }
            /* reset forgot password */
            $return = $this->user->changePassword($request);
            return finalResponse($return);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * method : Get
     * api : get-profile
     * return : get currently login user profile details
     * developer : Ravi Tewatia
     **********************************************************/
    public function getProfile()
    {
        try {
            $return = $this->user->getProfile();
            return finalResponse($return);
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * method : Get
     * api : get-user-list
     * return : get user listing
     * developer : Ravi Tewatia
     **********************************************************/
    public function getUserList(Request $request)
    {
        try {
            if (!empty($request->is_export) && $request->is_export == config('constants.IS_EXPORT')) {
                $returnResponse = $this->user->exportUserList($request);
                return finalResponse($returnResponse);
            } else {
                $returnResponse = $this->user->getUserList($request);
                return finalResponse($returnResponse);
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
     * method : Get
     * api : get-user-list-pdf
     * return : get user listing pdf
     * developer : Ravi Tewatia
     **********************************************************/
    public function getUserListPdf(Request $request)
    {
        try {
            $returnResponse = $this->user->getUserListPdf($request);
            return finalResponse($returnResponse);
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $id = Auth::id();
            $unique = Rule::unique('users')->where(function ($query) use ($id) {
                $query->where('status_id', '<>', config('constants.STATUS_DELETE'));
                $query->where('user_id', '<>', $id);
            });
            $request->merge([
                'email' => EncryptDecryptTrait::encryptData(strtolower($request->email)),
                'plain_email' => EncryptDecryptTrait::encryptData(strtolower($request->email)),
            ]);
            /* require validation fields  */
            $requiredFields = [
                'full_name' => 'required',
                'phone' => ['required'],
                'email' => ['required'],
            ];
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            }
            $return = $this->user->updateProfile($request, $id);
            return finalResponse($return);
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * method : Post
     * api : upload-profile-image
     * return : upload image when selected and return name of that image.
     * developer : Ravi Tewatia
     **********************************************************/
    public function uploadProfileImage(Request $request)
    {
        try {
            $requiredFields = [
                'profile_thumb' => 'required|mimes:jpeg,png,jpg',
            ];
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            }
            $return = $this->user->uploadProfileImage($request);
            return finalResponse($return);
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * method : Post
     * api : logout
     * return : delete auth token from personal_access_tokens table and logout.
     * developer : Ravi Tewatia
     **********************************************************/
    public function logout(Request $request)
    {
        try {
            $return = $this->user->userLogout($request);
            return finalResponse($return);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /**********************************************************
     * method : get
     * api : get-blocked-user-list
     * return : get blocked user list.
     * developer : Ravi Tewatia
     **********************************************************/
    public function getBlockedUserList(Request $request)
    {
        try {
            $blockedUserList = AttemptFailed::getBlockedUserList($request);
            return finalResponse($blockedUserList);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }
    /**********************************************************
     * method : Post
     * api : unblock-user
     * return : unblocked user immidiatly
     * developer : Ravi Tewatia
     **********************************************************/
    public function unblockedUser(Request $request)
    {
        try {
            $attemptFailed = new AttemptFailed;
            $unblockUser = $attemptFailed->unblockedUser($request);
            return finalResponse($unblockUser);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }
}
