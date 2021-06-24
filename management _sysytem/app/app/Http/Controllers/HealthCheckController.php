<?php

namespace App\Http\Controllers;

use App\Call;
use App\User;
use App\Services\EventPublishService;
use Illuminate\Http\Request;
use Illuminate\Queue\Connectors\SqsConnector;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class HealthCheckController extends Controller
{


    /**
     * Display a listing of the resource.
     * @OA\Get(
     * @OA\Server(
     *          url=L5_SWAGGER_CONST_WEB_PRODUCTION_HOST,
     *          description="Production Server"
     *      ),
     * @OA\Server(
     *          url=L5_SWAGGER_CONST_WEB_STAGING_HOST,
     *          description="Production Server"
     *      ),
     *      path="/healthz",
     *      operationId="get/healthz",
     *      tags={"Health Checks"},
     *      summary="Get Health Checks",
     *      description="Returns health check status.",
     * @OA\Response(
     *          response=200,
     *          description="Success",
     * @OA\JsonContent(
     * @OA\Property(
     *                  property="db",
     *                  title="Database health",
     *                  description="Database health",
     *                  type="boolean",
     *                  example=true,
     *              ),
     * @OA\Property(
     *                  property="cache",
     *                  title="Cache health",
     *                  description="Cache health",
     *                  type="boolean",
     *                  example=true,
     *              ),
     * @OA\Property(
     *                  property="aws-sqs-default",
     *                  title="Aws sqs health",
     *                  description="Aws sqs health",
     *                  type="boolean",
     *                  example=true,
     *              ),
     *          ),
     *      ),
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $response = [];

        // check DB
        if ($user = User::first() !== null) {
            // db is OK
            $response['db'] = true;
        } else {
            $response['db'] = false;
        }

        // Check cache
        $rnd = Str::random();
        Cache::put('cache_health', $rnd, 1);
        if (Cache::get('cache_health') === $rnd) {
            // cache is OK
            $response['cache'] = true;
        } else {
            $response['cache'] = false;
        }

        // Check AWS Queues
        foreach (config('queue.queues') as $name => $queue) {
            $response['aws-sqs-' . $name] = $this->sqsAvailable($queue);
        }

        // Check AWS SNS event
        // $snsService = new EventPublishService(new ActionWasCreated());
        // if ($snsService->isAvailable()) {
        // sns is OK
        // $response['aws-sns-events'] = true;
        // } else {
        // $response['aws-sns-events'] = false;
        // }
        return response($response, 200);
    }


    /**
     * Checks if API is up
     * @OA\Get(
     * @OA\Server(
     *          url=L5_SWAGGER_CONST_WEB_PRODUCTION_HOST,
     *          description="Production Server"
     *      ),
     * @OA\Server(
     *          url=L5_SWAGGER_CONST_WEB_STAGING_HOST,
     *          description="Production Server"
     *      ),
     *      path="/healthz/api",
     *      operationId="get/healthz-api",
     *      tags={"Health Checks"},
     *      summary="Get Health Checks Api",
     *      description="Returns health check api status.",
     * @OA\Response(
     *          response=200,
     *          description="Success",
     * @OA\JsonContent(
     * @OA\Property(
     *                  property="success",
     *                  title="Api health",
     *                  description="Api health",
     *                  type="boolean",
     *                  example=true,
     *              ),
     *          ),
     *      ),
     * )
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function api(Request $request)
    {
        // check DB
        if ($user = User::first() !== null) {
            $response = ['success' => true];

            return response($response, 200);
        } else {
            $response = [
                'success' => false,
                'error'   => 'Could not connect to DB',
            ];

            return response($response, 500);
        }
    }


    /**
     * Checks if AWS queue is available
     *
     * @param $queue
     *
     * @return bool
     */
    public function sqsAvailable($queue)
    {
        try {
            // Connect to AWS
            $queueConfig = config('queue.connections.sqs');
            $connector   = new SqsConnector();
            $sqsQueue    = $connector->connect($queueConfig);
            $attributes  = $sqsQueue->getSqs()->getQueueAttributes(
                [
                    'QueueUrl'       => $queue,
                    'AttributeNames' => ['ApproximateNumberOfMessages'],
                ]
            )->toArray();

            return Arr::get($attributes, '@metadata.statusCode', 0) === 200;
        } catch (\Exception $ex) {
            error('HealthCheckController->sqsAvailable - Caught an exception', ['exception' => $ex->getMessage(), 'queue' => $queue]);

            return false;
        }
    }
}
