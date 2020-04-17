<?php

/**
 * Controller Request
 *
 * This controller for HTTP Client
 *
 * @package   CodeIgniter
 * @category  Controller CI
 * @author AdiStwn
 * @author Brata
 */

defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;
use chriskacerguis\RestServer\RestController;

class Request extends RestController
{
    /** 
     * Credentials
     *
     * @var object
     */
    protected $credentials;

    /**
     * My IP Server ZK
     *
     * @var [type]
     */
    protected $myServer;

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->credentials = $this->jwtauth->check();
        // $this->myServer = ip_server();
        $this->myServer = "http://192.168.100.177:8098";
    }

    /**
     * Login to system ZK 
     * it will return cookie
     *
     * @return void
     */
    public function loginZK_get()
    {
        try {

            $jar    = new \GuzzleHttp\Cookie\CookieJar();
            $client = new Client();
            $client->request('POST', $this->myServer . '/login.do', [
                'cookies'     => $jar,
                'form_params' => [
                    'username'  => 'admin',
                    'password'  => md5('admin'),
                    'loginType' => 'NORMAL'
                ],
            ]);

            return $jar;
        } catch (\Exception $e) {
            $this->response([
                'error' => true,
                'message' => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Function to add area
     *
     * @return void
     */
    public function addArea_post()
    {
        $code = $this->post('code');
        $name = $this->post('name');

        $this->form_validation->set_rules('code', 'Code', 'trim|required|callback_code_check');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|callback_name_check');

        if ($this->form_validation->run()) {

            try {

                $cookie  = $this->loginZK_get(); // need login
                $client  = new Client();
                $request = $client->request('POST', $this->myServer . '/link', [
                    'cookies'     => $cookie,
                    'form_params' => [
                        'code'     => (int) $code,
                        'name'     => $name,
                        'parentId' => '4028e430709eaa6f01709eab73ff0001',
                    ],
                ]);
                $body     = $request->getBody();
                $response = json_decode($body);

                // [ret] => ok
                // [msg] => The operation succeeded!
                // [data] => 
                // [i18nArgs] => 
                // [success] => 1

                $this->response([
                    'error'        => ($response->success == 1) ? false : true,
                    'message'      => $response->msg,
                ], 200);
            } catch (\Exception $e) {
                $this->response([
                    'error'   => true,
                    'message' => $e->getMessage(),
                ], 200);
            }
        } else {
            $this->response([
                'error'   => true,
                'message' => $this->form_validation->error_array(),
            ], 422);
        }
    }

}


/* End of file AccDevice.php */
/* Location: ./application/controllers/AccDevice.php */
