<?php
use Illuminate\Http\Response;
/**
 * register Profile API
 *
 * @OA\Post(
 *   path="/api/product",
 *   operationId="register",
 *   tags={"Product Api"},
 *   summary="User register Profile",
 *   description="User register Profile",
 *   @OA\RequestBody(
 *      required=true,
 *      @OA\MediaType(
 *          example = {
 *              "emp_code" : "",
 *              "email" : "testuser@e2logy.com",
 *              "mobile_phone" : "81560847551",
 *              "initials" : "tu",
 *              "first_name" : " test ",
 *              "last_name" : "user",
 *              "gender" : "",
 *              "dob" : "",
 *              "job_title" : "",
 *              "staff_type" : "php dev",
 *              "role_id" : "2",
 *              "work_phone" : "",
 *              "start_date" : "01-01-2021",
 *              "end_date" : "31-12-2022",
 *              "is_proper_authority" : "",
 *              "profile_image" : "123.jpg",
 *              "home_address": {
 *                  "line1": "Level 38",
 *                  "line2": "Exchange Plaza 8",
 *                  "line3": "2 The Esplanade 8",
 *                  "suburb": "Perth 8",
 *                  "state": "WA 8",
 *                  "postcode": "6000",
 *                  "country": "AUSTRALIA"
 *              },
 *              "post_address": {
 *                  "line1": "Level 38",
 *                  "line2": "255 George Street 8",
 *                  "line3": "Street 8",
 *                  "suburb": "SYDNEY 8",
 *                  "state": "NSW 8",
 *                  "postcode": "2001",
 *                  "country": "AUSTRALIA"
 *              }
 *          },
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
