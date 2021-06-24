<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseSuccessResource;
use App\Http\Resources\ResponseFailureResource;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

class UserController extends Controller
{
    /**
    * Return list of users
    *
    * @return \Illuminate\Http\Response

    @OA\Get(
        path="/api/v1/users",
        operationId="getListOfUsers",
        tags={"User"},
        summary="Get list of users",
        description="Returns list of users",
        @OA\Response(
            response=200,
            description="Successfull Retrieval",
            @OA\JsonContent(
                @OA\Property(
                    property = "success",
                    description = "Shows operation status",
                    type = "boolean",
                    default = true,
                ),
                @OA\Property(
                    property = "message",
                    description = "Shows operation message",
                    type = "string",
                    default = "Users retrieved successfully",
                ),
                @OA\Property(
                    property="data",
                    description="Array of data of all Users",
                    @OA\Items(
                        ref="#/components/schemas/User",
                    ),
                ),
            ),
        ),
        @OA\Response(
            response = "5XX",
            description ="Server side error.",
            @OA\JsonContent(ref="#/components/schemas/response5xx"),
        ),
         @OA\Response(
            response = "4XX",
            description ="Client side error.",
            @OA\JsonContent(ref="#/components/schemas/response5xx"),
        ),

    ),

    */
    public function index()
    {
        $response  = new ResponseSuccessResource(
            [
                'users' => UserResource::collection(User::all()),
            ],
            "Users retrieved successfully"
        );

        return response($response, Response::HTTP_OK);
    }
}
