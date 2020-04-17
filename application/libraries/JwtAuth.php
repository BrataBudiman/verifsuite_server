<?php

/**
 * Jwt Controller
 * 
 * @author Adi
 * @author Brata
 * @package Rest Controller
 * @copyright 2020 PT Asia Sekuriti Indonesia
 */

defined('BASEPATH') or exit('No direct script access allowed');

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtAuth
{
    private $CI;

    /**
     * Jwt Secret
     *
     * @var string
     */
    protected $jwtSecret;

    /**
     * construct
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->jwtSecret = "PTASIASEKURITIINDONESIA";
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    }

    /**
     * Checking token JWT
     *
     * @return void
     */
    public function check()
    {
        // $token = $this->CI->input->get_request_header('Authorization');
        $token = $this->CI->input->get_request_header('X-API-KEY');

        if (!$token) {
            // Unauthorized response if token not there
            $this->CI->output
                ->set_status_header(401)
                ->set_output(json_encode([
                    'error'     => true,
                    'authError' => true,
                    'message'   => 'Token not provided.'
                ], JSON_PRETTY_PRINT))->_display();
            exit;
        }

        try {
            $credentials = JWT::decode($token, $this->jwtSecret, ['HS256']);
        } catch (ExpiredException $e) {

            $this->CI->output
                ->set_status_header(401)
                ->set_output(json_encode([
                    'error'     => true,
                    'authError' => true,
                    'message'   => 'Provided token is expired.'
                ], JSON_PRETTY_PRINT))->_display();
            exit;

        } catch (Exception $e) {
            $this->CI->output
                ->set_status_header(401)
                ->set_output(json_encode([
                    'error'     => true,
                    'authError' => true,
                    'message'   => 'An error while decoding token.'
                ], JSON_PRETTY_PRINT))->_display();
            exit;
        }

        return $credentials;
    }
}

/* End of file Jwtauth.php */
