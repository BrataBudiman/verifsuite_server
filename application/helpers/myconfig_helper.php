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
