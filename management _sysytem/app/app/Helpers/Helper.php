<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class Helper
{


    /**
     * Return url with per_page param
     * @param Request $request
     * @return string
     */
    public static function buildPageUrl($request)
    {
        $url  = strpos($request->url(), config('app.url')) === false ? config('app.url') . '/' . $request->path() : $request->url();
        $url .= $request->has('per_page') ? '?' : '';
        $url .= http_build_query($request->only('per_page'));
        return $url;
    }


    /**
     * Return url with per_page param
     * @param string $key
     * @param string $value
     * @return string
     */
    public static function setToServer($key, $value)
    {
        $_SERVER[$key] = $value;
        return $_SERVER[$key];
    }


    /**
     * Return value of given key
     * @param string $key
     * @return string
     */
    public static function getFromServer($key)
    {
        return Arr::get($_SERVER, $key, '');
    }

    /**
     * Get pagination data(array of links) from filter Data (which is returned by $filter->getData)
     * @param object $filterData
     * @return array
     */
    public static function getPaginationData($filterData)
    {
        $filterData = $filterData->toArray();
        unset($filterData["data"]);
        $meta = $filterData;
        return $meta;
    }
}
