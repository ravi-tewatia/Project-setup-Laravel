<?php
use Illuminate\Http\Response;

/**
 * @OA\Info(title="Laravel Base", version="1.0",description="V1")
 * basePath="/api",
 *
 * tags={"Ver1"},
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 * @OA\Tag(
 *     name="Authentication",
 *     description="Auth Testing for LOGIN/LOGOUT/VERIFY-OTP/RESET-FORGET-PASSWORD/RESET_PASSWORD/RESEND-OTP/LOGOUT"
 * )
 * @OA\Tag(
 *     name="Product Api",
 *     description="Product Api for ADD/UPDATE/DELETE/SELECT"
 * )
 *  @OA\Tag(
 *     name="Order Api",
 *     description="Order's Api for ADD/UPDATE/DELETE/SELECT"
 * )
 */

/**
 * register Profile API
 *
 * @OA\Post(
 *   path="/api/register",
 *   operationId="register",
 *   tags={"Authentication"},
 *   summary="User register Profile",
 *   description="User register Profile",
 *   @OA\RequestBody(
 *      required=true,
 *      @OA\MediaType(
 *          example = {
 *                      "full_name": "gaurangkumar patel",
 *                      "email": "gaurang@e2logy.com",
 *                      "phone": "1321385552",
 *                      "password": "12345678",
 *                      "password_confirmation": "12345678",
 *                      "profile_thumb": "mk.png",
 *                      "street_address": "nadiad",
 *                      "city": "nadiad",
 *                      "postal_code": "380001",
 *                      "state": "gujarat",
 *                      "custom_data": "",
 *                      "created_by": "1"
 *                   },
 *          mediaType="application/json",
 *          @OA\Schema(
 *              @OA\RequestBody(
 *              required=true,
 *              description="JSON of staff",
 *                  @OA\MediaType(
 *                      mediaType="application/json",
 *                      @OA\Schema(type="string", default="")
 *                  )
 *              ),
 *          )
 *      )
 *  ),
 *  @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=201,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=400,
 *      description="Bad Request",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=401,
 *      description="Unauthenticated",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=403,
 *      description="Forbidden",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=404,
 *      description="Not Found",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=405,
 *      description="Method Not Allowed",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=422,
 *      description="Validation Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=500,
 *      description="Internal Server Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *)
 **/

/**
 * @OA\Get(
 ** path="/api/user-approve-list",
 *   tags={"Authentication"},
 *   summary="get user-approve-list",
 *   security={{"bearer_token":{}}},
 *   operationId="user-approve-list",
 *   description="get user-approve-list",
 *
 *   @OA\Response(
 *      response=200,
 *       description="Success",
 *      @OA\MediaType(
 *           mediaType="application/json",
 *      )
 *   ),
 *   @OA\Response(
 *      response=401,
 *      description="Unauthenticated"
 *   ),
 *   @OA\Response(
 *      response=400,
 *      description="Bad Request"
 *   ),
 *   @OA\Response(
 *      response=404,
 *      description="not found"
 *   ),
 *   @OA\Response(
 *      response=403,
 *      description="Forbidden"
 *   ),
 *
 *)
 **/
/**
 * @OA\Post(
 ** path="/api/user-approve-action",
 *  tags={"Authentication"},
 *  summary="user-approve-action",
 *  operationId="user-approve-action",
 *  @OA\RequestBody(
 *      required=true,
 *      @OA\MediaType(
 *          example = {
 *              "slug": "XCZBMQRGD9S4"
 *          },
 *          mediaType="application/json",
 *          @OA\Schema(
 *              @OA\RequestBody(
 *              required=true,
 *              description="JSON of login credentials",
 *                  @OA\MediaType(
 *                      mediaType="application/json",
 *                      @OA\Schema(type="string", default="")
 *                  )
 *              ),
 *          )
 *      )
 *  ),
 *  @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=201,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=400,
 *      description="Bad Request",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=401,
 *      description="Unauthenticated",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=403,
 *      description="Forbidden",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=404,
 *      description="Not Found",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=405,
 *      description="Method Not Allowed",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=422,
 *      description="Validation Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=500,
 *      description="Internal Server Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *)
 **/

/**
 * @OA\Post(
 ** path="/api/account-activation",
 *  tags={"Authentication"},
 *  summary="account-activation",
 *  operationId="account-activation",
 *  @OA\RequestBody(
 *      required=true,
 *      @OA\MediaType(
 *          example = {
 *              "activation_token": "ODjv3eCyp6O2qfEpCzeIBigrPG74CZvm"
 *          },
 *          mediaType="application/json",
 *          @OA\Schema(
 *              @OA\RequestBody(
 *              required=true,
 *              description="JSON of login credentials",
 *                  @OA\MediaType(
 *                      mediaType="application/json",
 *                      @OA\Schema(type="string", default="")
 *                  )
 *              ),
 *          )
 *      )
 *  ),
 *  @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=201,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=400,
 *      description="Bad Request",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=401,
 *      description="Unauthenticated",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=403,
 *      description="Forbidden",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=404,
 *      description="Not Found",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=405,
 *      description="Method Not Allowed",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=422,
 *      description="Validation Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=500,
 *      description="Internal Server Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *)
 **/

/**
 * @OA\Post(
 ** path="/api/login",
 *  tags={"Authentication"},
 *  summary="User Login",
 *  operationId="login",
 *  @OA\RequestBody(
 *      required=true,
 *      @OA\MediaType(
 *          example = {
 *              "email" : "gaurang@e2logy.com",
 *              "password" : "12345678"
 *          },
 *          mediaType="application/json",
 *          @OA\Schema(
 *              @OA\RequestBody(
 *              required=true,
 *              description="JSON of login credentials",
 *                  @OA\MediaType(
 *                      mediaType="application/json",
 *                      @OA\Schema(type="string", default="")
 *                  )
 *              ),
 *          )
 *      )
 *  ),
 *  @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=201,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=400,
 *      description="Bad Request",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=401,
 *      description="Unauthenticated",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=403,
 *      description="Forbidden",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=404,
 *      description="Not Found",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=405,
 *      description="Method Not Allowed",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=422,
 *      description="Validation Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=500,
 *      description="Internal Server Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *)
 **/
/**
 * @OA\Post(
 ** path="/api/forgot-password",
 *   tags={"Authentication"},
 *   summary="user forgot password",
 *   operationId="forgotPassword",
 *  @OA\RequestBody(
 *      required=true,
 *      @OA\MediaType(
 *          example = {
 *              "email" : "gaurang@e2logy.com"
 *          },
 *          mediaType="application/json",
 *          @OA\Schema(
 *              @OA\RequestBody(
 *              required=true,
 *              description="JSON of user forgot password",
 *                  @OA\MediaType(
 *                      mediaType="application/json",
 *                      @OA\Schema(type="string", default="")
 *                  )
 *              ),
 *          )
 *      )
 *  ),
 *  @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=201,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=400,
 *      description="Bad Request",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=401,
 *      description="Unauthenticated",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=403,
 *      description="Forbidden",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=404,
 *      description="Not Found",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=405,
 *      description="Method Not Allowed",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=422,
 *      description="Validation Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=500,
 *      description="Internal Server Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *)
 **/
/**
 * @OA\Post(
 ** path="/api/reset-forgot-password",
 *   tags={"Authentication"},
 *   summary="user reset forgot password",
 *   operationId="resetForgotPassword",
 *  @OA\RequestBody(
 *      required=true,
 *      @OA\MediaType(
 *          example = {
 *                      "reset_token": "RCJQwCVerYSCUC3QZUt0tENKogYi7hJc",
 *                      "new_password": "123456789",
 *                      "password_confirmation": "123456789"
 *                      },
 *          mediaType="application/json",
 *          @OA\Schema(
 *              @OA\RequestBody(
 *              required=true,
 *              description="JSON of user reset forgot password",
 *                  @OA\MediaType(
 *                      mediaType="application/json",
 *                      @OA\Schema(type="string", default="")
 *                  )
 *              ),
 *          )
 *      )
 *  ),
 *  @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=201,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=400,
 *      description="Bad Request",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=401,
 *      description="Unauthenticated",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=403,
 *      description="Forbidden",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=404,
 *      description="Not Found",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=405,
 *      description="Method Not Allowed",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=422,
 *      description="Validation Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=500,
 *      description="Internal Server Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *)
 **/

/**
 * @OA\Post(
 ** path="/api/reset-password",
 *   tags={"Authentication"},
 *   summary="user reset password",
 *   operationId="resetPassword",
 *   security={{"bearer_token":{}}},
 *  @OA\RequestBody(
 *      required=true,
 *      @OA\MediaType(
 *          example = {
 *              "old_password": "123456789",
 *              "new_password": "12345678",
 *              "password_confirmation": "12345678"
 *              },
 *          mediaType="application/json",
 *          @OA\Schema(
 *              @OA\RequestBody(
 *              required=true,
 *              description="JSON of user reset password",
 *                  @OA\MediaType(
 *                      mediaType="application/json",
 *                      @OA\Schema(type="string", default="")
 *                  )
 *              ),
 *          )
 *      )
 *  ),
 *  @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=201,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=400,
 *      description="Bad Request",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=401,
 *      description="Unauthenticated",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=403,
 *      description="Forbidden",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=404,
 *      description="Not Found",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=405,
 *      description="Method Not Allowed",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=422,
 *      description="Validation Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=500,
 *      description="Internal Server Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *)
 **/

/**
 * @OA\Get(
 ** path="/api/get-profile",
 *   tags={"Authentication"},
 *   summary="user Profile",
 *   security={{"bearer_token":{}}},
 *   operationId="getProfile",
 *
 *   @OA\Response(
 *      response=200,
 *       description="Success",
 *      @OA\MediaType(
 *           mediaType="application/json",
 *      )
 *   ),
 *   @OA\Response(
 *      response=401,
 *      description="Unauthenticated"
 *   ),
 *   @OA\Response(
 *      response=400,
 *      description="Bad Request"
 *   ),
 *   @OA\Response(
 *      response=404,
 *      description="not found"
 *   ),
 *   @OA\Response(
 *      response=403,
 *      description="Forbidden"
 *   ),
 *
 *)
 **/

/**
 * Update the specified staff in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
/**
 * Update Profile API
 *
 * @OA\Put(
 *   path="/api/update-profile",
 *   operationId="updateProfile",
 *   tags={"Authentication"},
 *   security={{"bearer_token":{}}},
 *   summary="Update Profile",
 *   description="Update Profile",
 *   @OA\RequestBody(
 *      required=true,
 *      @OA\MediaType(
 *          example = {
 *                      "full_name": "gaurangkumar patel",
 *                      "email": "gaurang@e2logy.com",
 *                      "phone": "8320195550",
 *                      "street_address": "nadiad",
 *                      "city": "nadiad",
 *                      "postal_code": "380001",
 *                      "state": "gujarat",
 *                      "custom_data": "",
 *                      "status_id": 1,
 *                      "created_by": "1"
 *                  },
 *          mediaType="application/json",
 *          @OA\Schema(
 *              @OA\RequestBody(
 *              required=true,
 *              description="JSON of staff",
 *                  @OA\MediaType(
 *                      mediaType="application/json",
 *                      @OA\Schema(type="string", default="")
 *                  )
 *              ),
 *          )
 *      )
 *  ),
 *  @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=201,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=400,
 *      description="Bad Request",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=401,
 *      description="Unauthenticated",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=403,
 *      description="Forbidden",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=404,
 *      description="Not Found",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=405,
 *      description="Method Not Allowed",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=422,
 *      description="Validation Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=500,
 *      description="Internal Server Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *)
 **/

/**
 * @OA\Post(
 ** path="/api/upload-profile-image",
 *  tags={"Authentication"},
 *  summary="Upload Profile Image",
 *  operationId="uploadProfileImage",
 *  security={{"bearer_token":{}}},
 *  @OA\RequestBody(
 *    required=true,
 *    @OA\MediaType(
 *        mediaType="multipart/form-data",
 *        @OA\Schema(
 *           @OA\Property(
 *             property="image",
 *             type="file",
 *             description="select Image",
 *             default="anyname",
 *          ),
 *        )
 *     )
 *   ),
 *  @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=201,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=400,
 *      description="Bad Request",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=401,
 *      description="Unauthenticated",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=403,
 *      description="Forbidden",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=404,
 *      description="Not Found",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=405,
 *      description="Method Not Allowed",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=422,
 *      description="Validation Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=500,
 *      description="Internal Server Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *)
 **/

/**
 * @OA\Post(
 ** path="/api/logout",
 *   tags={"Authentication"},
 *   summary="user logout",
 *   security={{"bearer_token":{}}},
 *   operationId="logout",
 *
 *  @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=201,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=400,
 *      description="Bad Request",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=401,
 *      description="Unauthenticated",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=403,
 *      description="Forbidden",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=404,
 *      description="Not Found",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=405,
 *      description="Method Not Allowed",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=422,
 *      description="Validation Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *  @OA\Response(
 *      response=500,
 *      description="Internal Server Error",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *        )
 *   ),
 *)
 **/
