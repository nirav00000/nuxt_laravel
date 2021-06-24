<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\ResponseSuccessResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
    @OA\Post(
        path = "/api/v1/logout",
        operationId = "logout",
        tags = {"Logout"},
        summary = "Logs out current user",
        @OA\Response(
            response = 200,
            description = "Sucessfull Logout",
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
                    default = "Successfully logged out",
                ),
                @OA\Property(
                    property = "data",
                    example = {},
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
            @OA\JsonContent(ref="#/components/schemas/response4xx"),
        ),
    ),



    */
    public function logout()
    {
        Auth::logout();
        $response = new ResponseSuccessResource([], "Successfully logged out");
        return response($response, Response::HTTP_OK);
    }

     /**
    @OA\Post(
        path = "/api/v1/users/me",
        operationId = "me",
        tags = {"User"},
        summary = "Get name of logged in user",
        @OA\Response(
            response = 200,
            description = "Successfull Retrieval",
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
                    default = "Username retreived successfully",
                ),
                @OA\Property(
                    property = "data",
                    example = {
                          "user_name": "Asia Walter"
                    },
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
            @OA\JsonContent(ref="#/components/schemas/response4xx"),
        ),
    ),



    */
    public function me(Request $request)
    {
        $response = new ResponseSuccessResource([
            "user_name" => $request->oauth_name,
            "email"     => $request->oauth_email,
        ], "Username retreived successfully");

        return response($response, Response::HTTP_OK);
    }

        /**
    @OA\Get(
        path = "/api/v1/users/groups",
        operationId = "group",
        tags = {"User"},
        summary = "This API returns groups in which user has",
        @OA\Response(
            response = 200,
            description = "Group retrived successfully",
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
                    default = "Group retrived successfully",
                ),
                @OA\Property(
                    property = "data",
                        @OA\Property(
                            property = "groups",
                            @OA\Items(type="string",example="admin")
                        )

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
            @OA\JsonContent(ref="#/components/schemas/response4xx"),
        ),
    ),
    */
    public function getGroups(Request $request)
    {
        $response = new ResponseSuccessResource([
            "groups" => $request->oauth_groups
        ], "Group retrived successfully");

        return response($response, Response::HTTP_OK);
    }
}
