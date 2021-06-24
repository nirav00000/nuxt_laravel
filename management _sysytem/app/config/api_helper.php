<?php

return [

    /*
        |--------------------------------------------------------------------------
        | Prometheus
        |--------------------------------------------------------------------------
        | log_stats if you want to use api stats using prometheus
        |
        | log_stats = false (default)
        |
        | Set config of prometheus like app name, client etc.
        |
        |
        | You can any variables to export on histogram and counter of prometheus
    */

    'log_stats'               => true,

    'prometheus'              => [
        'labels'                => [
            'client_id' => 10,
            'app'       => 'api-helper',
            'source'    => 'core',
        ],
        'histogram_bucket_name' => 'lde_http_response_time_seconds_bucket',
        'histogram_bucket'      => [0.1, 0.25, 0.5, 0.75, 1.0, 2.5, 3.0, 3.5, 4.0, 4.5, 5.0, 7.5, 10.0],
    ],

    /*
        |--------------------------------------------------------------------------
        | Retries
        |--------------------------------------------------------------------------
        |
        | Sets the number of retries to attempt before giving up.
        |
        | 0 = Off
        |
    */

    'number_of_retries'       => 3,

    /*
        |--------------------------------------------------------------------------
        | Default Guzzle Request Options
        |--------------------------------------------------------------------------
        |
        | Sets the default Guzzle request options.
        |
        | http://docs.guzzlephp.org/en/stable/request-options.html
        |
        | 0 = Off
        |
    */

    'default_request_options' => [
        'http_errors'     => true,
        'connect_timeout' => 10,
        'timeout'         => 30,
        'headers'         => [
            "Accept"       => "application/json",
            "Content-Type" => "application/json",
        ],
    ],

    // If no connection provided, use default
    'default'                 => 'httpbin',

    // Sensitive fields will be masked on the return value and logs
    'sensitive_fields'        => [
        'auth.0',
        'auth.1',
        'headers.apikey',
    ],

    // Define all the APIs here
    'connections'             => [

        // HTTPBin
        'httpbin' => [
            'root'                    => '',

            // Set method name for custom escape characters
            'character_escape_method' => '',

            // API type: json or xml or view
            'type'                    => 'json',

            // API base URL
            'base_url'                => 'https://httpbin.org',

            // Default Request options. these are included with all calls unless overwritten
            'default_request_options' => [
                'http_errors'     => true,
                'connect_timeout' => 10,
                'timeout'         => 30,
                'headers'         => [
                    "Accept"       => "application/json",
                    "Content-Type" => "application/json",
                ],
            ],

            // number of retries to override global settings.
            'number_of_retries'       => 0,
            // 0 = off
            // status not to retry, define status code for which api will not retried
            'status_not_to_retry'     => [517],

            // List of API routes we are integrating with
            'routes'                  => [

                // Sample API to test GET
                'get'        => [
                    'method'   => 'GET',
                    'uri'      => '/get',
                    'mappings' => [
                        'path'  => [],
                        'query' => [
                            'name'    => 'person.name',
                            'surname' => 'person.surname',
                            'foo'     => 'foo',
                        ],
                        'body'  => [],
                    ],
                ],

                // Sample API to test POST
                'post'       => [
                    'name'     => 'httpbin',
                    'method'   => 'POST',
                    'uri'      => '/post',
                    'body'     => [
                        'first_name' => '{name}',
                        'last_name'  => '{surname}',
                        'nested'     => [
                            'foo' => '{foo}',
                        ],
                    ],
                    'mappings' => [
                        'query' => [
                            'test' => 'person.name',
                        ],
                        'body'  => [
                            'name'    => 'person.name',
                            'surname' => 'person.surname',
                            'foo'     => 'foo',
                        ],
                    ],
                ],
                // Sample API to test Form_params
                'formParams' => [
                    'name'         => 'httpbin',
                    'method'       => 'POST',
                    'uri'          => '/post',
                    'request_type' => 'form_data',
                    'form_params'  => [
                        'parameters' => [
                            'revision' => '{revision}',
                        ],
                    ],
                    'mappings'     => [
                        'form_params' => [
                            'revision' => 'param.revision',
                        ],
                    ],
                ],
                'delete'     => [
                    'name'     => 'httpbin',
                    'method'   => 'DELETE',
                    'uri'      => '/delete',
                    'mappings' => [
                        'query' => [
                            'id' => 'person.id',
                        ],
                    ],
                ],

            ],
        ],

        'mockbin' => [
            'root'                    => 'request',

            // Set method name for custom escape characters
            'character_escape_method' => '',

            // API type: json or xml or view
            'type'                    => 'xml',

            // API base URL
            'base_url'                => 'http://mockbin.org',

            // Default Request options. these are included with all calls unless overwritten
            'default_request_options' => [
                'http_errors'     => true,
                'connect_timeout' => 10,
                'timeout'         => 30,
                'headers'         => [
                    "Accept"       => "application/xml",
                    "Content-Type" => "application/xml",
                ],
            ],

            // number of retries to override global settings.
            'number_of_retries'       => 0,
            // 0 = off
            'routes'                  => [
                // Sample API to test POST using xml as the body
                'echo' => [
                    'method'          => 'POST',
                    'uri'             => '/echo',
                    'request_options' => [
            // we can override request_options per API
                        'http_errors'     => true,
                        'connect_timeout' => 10,
                        'timeout'         => 30,
                        'headers'         => [
            // these headers will be set automatically for XML apis. No need to specify them
                            "Accept"       => "application/xml",
                            "Content-Type" => "application/xml",
                        ],
                    ],
                    'xml_config'      => [
                        'root_element_name' => 'request',
                    // defaults to root if left out
                        'attributes'        => [
                            'xmlns' => 'https://github.com/spatie/array-to-xml',
                        ],
                        'use_underscores'   => true,
                        'encoding'          => 'UTF8',
                    ],
                    'body'            => [
                        'request' => [
                            'attributes' => ['class' => '{class}'],
                            'name'       => '{name}',
                            'weapon'     => '{weapon}',
                        ],
                    ],
                    'mappings'        => [
                        'body' => [
                            'class'  => 'request.class',
                            'name'   => 'request.name',
                            'weapon' => 'request.weapon',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
