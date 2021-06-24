<?php

if (function_exists('debug') === false) {


    /**
     * Write debug to the log.
     *
     * @param  string $message
     * @param  array  $context
     * @return void
     */
    function debug($message, $context = [])
    {

        app('log')->debug($message, $context);
    }


}

if (function_exists('info') === false) {


    /**
     * Write info to the log.
     *
     * @param  string $message
     * @param  array  $context
     * @return void
     */
    function info($message, $context = [])
    {

        app('log')->info($message, $context);
    }


}

if (function_exists('notice') === false) {


    /**
     * Write notice to the log.
     *
     * @param  string $message
     * @param  array  $context
     * @return void
     */
    function notice($message, $context = [])
    {

        app('log')->notice($message, $context);
    }


}

if (function_exists('warning') === false) {


    /**
     * Write warning to the log.
     *
     * @param  string $message
     * @param  array  $context
     * @return void
     */
    function warning($message, $context = [])
    {

        app('log')->warning($message, $context);
    }


}

if (function_exists('error') === false) {


    /**
     * Write error to the log.
     *
     * @param  string $message
     * @param  array  $context
     * @return void
     */
    function error($message, $context = [])
    {

        app('log')->error($message, $context);
    }


}

if (function_exists('critical') === false) {


    /**
     * Write critical to the log.
     *
     * @param  string $message
     * @param  array  $context
     * @return void
     */
    function critical($message, $context = [])
    {

        app('log')->critical($message, $context);
    }


}

if (function_exists('alert') === false) {


    /**
     * Write alert to the log.
     *
     * @param  string $message
     * @param  array  $context
     * @return void
     */
    function alert($message, $context = [])
    {

        app('log')->alert($message, $context);
    }


}

if (function_exists('emergency') === false) {


    /**
     * Write emergency to the log.
     *
     * @param  string $message
     * @param  array  $context
     * @return void
     */
    function emergency($message, $context = [])
    {

        app('log')->emergency($message, $context);
    }


}
