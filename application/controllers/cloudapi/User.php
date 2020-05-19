<?php

/**
 * Users Controller
 * 
 * @author Adi
 * @author Brata
 * @package Rest Controller
 * @copyright 2020 PT Asia Sekuriti Indonesia
 */

defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;
use chriskacerguis\RestServer\RestController;

class User extends RestController
{
    /** 
     * Credentials
     *
     * @var object
     */
    protected $credentials;

    /** 
     * Credentials
     *
     * @var string
     */
    protected $baseUrlTTLOCK;

    /** 
     * Credentials
     *
     * @var string
     */
    protected $appSecret;

    /** 
     * Credentials
     *
     * @var string
     */
    protected $idSecret;

    /**
     * Init construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('JwtAuth');

        $this->baseUrlTTLOCK = baseurl_ttlock();
        $this->appSecret     = app_secret();
        $this->idSecret      = id_secret();
    }

    /**
     * Show all data list users
     *
     * @method POST
     * @return void
     */
    public function show_post()
    {
        $apiUrl = $this->baseUrlTTLOCK . "/v3/user/list";

        try {
            $client  = new Client();
            $request = $client->request('POST', $apiUrl, [
                'form_params' => [
                    'clientId'     => $this->appSecret,
                    'clientSecret' => $this->idSecret,
                    'startDate'    => '0',
                    'endDate'      => '0',
                    'pageNo'       => '1',
                    'pageSize'     => '100', // max is 100
                    'date'         => intval(microtime(true) * 1000),
                ],
            ]);
            $body     = $request->getBody();
            $response = json_decode($body);

            $this->response($response, 200);
        } catch (\Exception $ex) {
            $this->response([
                'error'   => true,
                'message' => $ex->getMessage(),
            ], 200);
        }
    }

    /**
     * Create a new user 
     * to ttlock cloud api
     *
     * @return void
     */
    public function store_post()
    {
        $apiUrl = $this->baseUrlTTLOCK . "/v3/user/register";
        $username = $this->post('username');
        $password = $this->post('password');

        try {
            $client  = new Client();
            $request = $client->request('POST', $apiUrl, [
                'form_params' => [
                    'clientId'     => $this->appSecret,
                    'clientSecret' => $this->idSecret,
                    'username'     => $username,
                    'password'     => md5($password),
                    'date'         => intval(microtime(true) * 1000),
                ],
            ]);
            $body     = $request->getBody();
            $response = json_decode($body);

            $this->response($response, 200);
        } catch (\Exception $ex) {
            $this->response([
                'error'   => true,
                'message' => $ex->getMessage(),
            ], 200);
        }
    }
}

/* End of file User.php */
