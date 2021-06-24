<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title=L5_SWAGGER_CONST_TITLE,
 *      x={
 *          "logo": {
 *              "url":L5_SWAGGER_CONST_LOGO,
 *              "backgroundColor":"#FFFFFF",
 *              "altText":"LDE"
 *          }
 *      },
 *      description="
This API blueprint is subject to change due to technology restrictions, performance optimizations or changing requirements.

## Environments
Our Production API is located at:

```
http://laravel-project-template.test
```

Our Staging API is located at:
```
http://staging-laravel-project-template.test
```

## Versioning
This API uses versioning as identified in the URI. Newer versions MAY introduce breaking changes.

`/api/v1/users`

## HTTP Methods
This API uses HTTP verbs (methods) as following:

- `GET` - *Read* - used to **read** (or retrieve) a representation of a resource,
- `POST` - *Create* - used to **create** new resources.
- `PUT` - *Update* - used to **update** an existing resource identified by a URI.
- `DELETE` - *Delete* - used to **delete** a resource identified by a URI.

## Media Type
Where applicable this API MUST use the `JSON` media-type. Requests with a message-body are using plain `JSON` to set or update resource states.

`Content-type: application/json` and `Accept: application/json headers` SHOULD be set on all requests if not stated otherwise.

## Notational Conventions
The key words `MUST`, `MUST NOT`, `REQUIRED`, `SHALL`, `SHALL NOT`, `SHOULD`, `SHOULD NOT`, `RECOMMENDED`, `MAY`, and `OPTIONAL` in this document are to be interpreted as described in [RFC2119](https://www.ietf.org/rfc/rfc2119).

## Resource IDs
This API uses short non-sequential url-friendly unique ids. Every resource id `MUST` consists of 6 url-friendly characters: `A-Z` and `0-9`.

Resource Ids `MUST` be `UPPERCASE`. Resource Ids are still `CASE SENSITIVE` but should no longer cause duplicate key issues in databases with case insensitive collations.

```
Example: A84LNOE
```

## Representation of Date and Time
All exchange of date and time-related data `MUST` be done according to `ISO 8601` standard and stored in UTC.

When returning date and time-related data `YYYY-MM-DDThh:mm:ss.SSSZ` format `MUST` be used.

## Status Codes and Errors
This API uses HTTP status codes to communicate with the API consumer.

- `200 OK` - Response to a successful GET, PUT, PATCH or DELETE.
- `201 Created` - Response to a POST that results in a creation.
- `202 Accepted` - Response to a request if an actioned will be queued for offline processing.
- `204 No Content` - Response to a successful request that won't be returning a body (like a DELETE request).
- `400 Bad Request` - Malformed request; form validation errors.
- `401 Unauthorized` - When no or invalid authentication details are provided.
- `403 Forbidden` - When authentication succeeded but authenticated user doesn't have access to the resource.
- `404 Not Found` - When a non-existent resource is requested.
- `405 Method Not Allowed` - Method not allowed.
- `406 Not Acceptable` - Could not satisfy the request.
- `415 Unsupported Media Type` - Unsupported media type in request.
- `422 Validation failed` - Supplied data did not pass validation.
- `500 Internal server error` - An uncaught exception occurred.
- `502 Bad Gateway` - An error occurred in downstream service.

 * "
 * )
 */

/**
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_PRODUCTION_HOST,
 *      description="Production Server"
 * )
 */

 /**
  * @OA\Server(
  *      url=L5_SWAGGER_CONST_STAGING_HOST,
  *      description="Staging Server"
  * )
  */

/**
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     description="This API uses TOKEN KEY authentication

Key MUST be provided for each request that requires authentication.",
 *     name="apikey",
 *     in="header",
 *     scheme="https",
 *     securityScheme="Header",
 * )
 */

/**
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     description="This API uses **TOKEN KEY** authentication

Key `MUST` be provided for each request that requires authentication.",
 *     name="apikey",
 *     in="query",
 *     scheme="https",
 *     securityScheme="Token",
 * )
 */

/**
 * @OA\ExternalDocumentation(
 *     description="Find out more about improwised",
 *     url="https://www.improwised.com/"
 * )
 */

/**
 * @OA\Get(
 *      path="/projects",
 *      operationId="getProjectsList",
 *      tags={"Projects"},
 *      summary="Get list of projects",
 *      description="Returns list of projects",
 * @OA\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 * @OA\Response(response=400, description="Bad request"),
 *     )
 *
 * Returns list of projects
 */

/**
 * @OA\Get(
 *      path="/projects/{id}",
 *      operationId="getProjectById",
 *      tags={"Projects"},
 *      summary="Get project information",
 *      description="Returns project data",
 * @OA\Parameter(
 *          name="id",
 *          description="Project id",
 *          required=true,
 *          in="path",
 * @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 * @OA\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 * @OA\Response(response=400, description="Bad request"),
 * @OA\Response(response=404, description="Resource Not Found"),
 *      security={
 *         {
 *             "oauth2_security_example": {"write:projects", "read:projects"}
 *         }
 *     },
 * )
 */

/**
 * @OA\Post(
 *     path="/users",
 *     summary="Adds a new user",
 * @OA\RequestBody(
 * @OA\MediaType(
 *             mediaType="application/json",
 * @OA\Schema(
 * @OA\Property(
 *                     property="id",
 *                     type="string"
 *                 ),
 * @OA\Property(
 *                     property="name",
 *                     type="string"
 *                 ),
 *                 example={"id": 10, "name": "Jessica Smith"}
 *             )
 *         )
 *     ),
 * @OA\Response(
 *         response=200,
 *         description="OK"
 *     )
 * )
 *
 */


/**
 * @OA\Schema(
 *   schema="response4xx",
 *   description="Response for 4xx type of errors",
 * @OA\Property(
 *        property = "success",
 *        description = "Shows status of operation",
 *        type = "boolean",
 *        default = false,
 *      ),
 * @OA\Property(
 *          property = "message",
 *          description = "Error message to be shown to user",
 *          type = "string",
 *          example = "Record not found.",
 *      ),
 * @OA\Property(
 *          property = "error",
 *          description = "Full error for debugging",
 *              example = {"key1":"data1","key2":"data2"},
 *      ),
 * ),
 */
/**
 * @OA\Schema(
 *     schema="response5xx",
 *     description="Response for 5xx type of errors",
 * @OA\Property(
 *         property = "success",
 *         description = "Shows status of operation",
 *         type = "boolean",
 *         default = false,
 *       ),
 * @OA\Property(
 *           property = "message",
 *           description = "Error message to be shown to user",
 *           type = "string",
 *           example = "Database error.",
 *       ),
 * @OA\Property(
 *           property = "error",
 *           description = "Full error for debugging",
 *           example = {"key1":"data1","key2":"data2"},
 *       ),
 * ),
 */


class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
