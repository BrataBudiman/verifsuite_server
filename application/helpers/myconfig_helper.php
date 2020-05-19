<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('ip_server')) {

    /**
     * Define IP Server ZK
     *
     * @param array $level
     * @param boolean $redirect
     * @return void
     */
    function ip_server()
    {
        // $IpServer = "http://192.168.100.177:8098";
        $IpServer = "http://localhost:8098";

        return $IpServer;
    }
}


if (!function_exists('baseurl_ttlock')) {

    /**
     * Define base url TTLOCK CLOUD API
     *
     * @return void
     */
    function baseurl_ttlock()
    {
        return "https://api.ttlock.com";
    }
}

if (!function_exists('app_secret')) {

    /**
     * Define app secret ttlock
     *
     * @return void
     */
    function app_secret()
    {
        return "8316e54bbc0f4507a1c535cde1d58d54";
    }
}

if (!function_exists('id_secret')) {

    /**
     * Define id secret ttlock
     *
     * @return void
     */
    function id_secret()
    {
        return "5850bc53daf0eb8f430975ffdc8f83e4";
    }
}
