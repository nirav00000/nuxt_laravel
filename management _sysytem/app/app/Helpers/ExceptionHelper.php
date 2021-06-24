<?php

namespace App\Helpers;

class ExceptionHelper
{


    /**
     * Function to turn exception into an array
     *
     * @param \Exception $ex
     *
     * @param array      $extra
     *
     * @return array
     */
    public static function toArray(\Exception $ex, $extra = [])
    {
        return array_merge(
            [
                'message' => $ex->getMessage(),
                'code'    => $ex->getCode(),
                'file'    => $ex->getFile(),
                'line'    => $ex->getLine(),
                'trace'   => $ex->getTraceAsString(),
            ],
            $extra
        );
    }
}
