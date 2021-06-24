<!DOCTYPE html>
<html>
  <head>
    <title>{{ env('APP_NAME') }} API Documentation</title>
    <!-- needed for adaptive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    @if ( App::environment() == 'staging' || App::environment() == 'production' )
        <link rel="icon" href="{{ config('app.url') . app('url')->assetFrom('','favicon.png') }}">
    @else
        <link rel="icon" href="{{ asset('favicon.png') }}">
    @endif

    <!--
    ReDoc doesn't change outer page styles
    -->
    <style>
      body {
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>

    <!-- Scripts -->
    @if ( App::environment() == 'staging' || App::environment() == 'production' )
        <redoc spec-url="{{ config('app.url') . app('url')->assetFrom('','swagger/api-docs.json') }}"></redoc>
        <script type="text/javascript" src="{{ config('app.url') . app('url')->assetFrom('','js/redoc.min.js') }}"></script>
    @else
        <redoc spec-url="{{ asset('swagger/api-docs.json') }}"></redoc>
        <script type="text/javascript" src="{{ asset('js/redoc.min.js') }}"></script>
    @endif
  </body>
</html>
