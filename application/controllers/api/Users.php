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

use Firebase\JWT\JWT;
use chriskacerguis\RestServer\RestController;

class Users extends RestController
{
    /**
     * JWT SECRET
     *
     * @var string
     */
    protected $jwtSecret;

    /** 
     * Credentials
     *
     * @var object
     */
    protected $credentials;

    /**
     * Construct function
     */
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->exceptionRoute();
        
        $this->load->library('JwtAuth');
        $this->load->model('UserModel');
        $this->jwtSecret = "PTASIASEKURITIINDONESIA";
    }

    /**
     * Check the routes 
     * Exception for request of JWT
     *
     * @return void
     */
    private function exceptionRoute()
    {
        $route = $this->uri->segment(3);
        $exception = array(
            'check_login',
            'store'
        );

        if ( !in_array($route, $exception) ) {
            $this->credentials = $this->jwtauth->check();
        }
    }

    /**
     * Json Web Token
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    private function jwt($userId)
    {
        $user = $this->GlobalModel->getGlobal('users', 'id', $userId);
        $payload = [
            'iss'  => "verifsuite",
            'sub'  => base64_encode($user->id),
            'name' => $user->name,
            'iat'  => time(),
            // 'exp' => time() + 86400 // Expiration time 24 hours
        ];
        return JWT::encode($payload, $this->jwtSecret);
    }

    /**
     * Check login
     *
     * @method POST
     * @return void
     */
    public function check_login_post()
    {
        $username = $this->post('username');
        $password = $this->post('password');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

        if ($this->form_validation->run()) {
            $dataUser = $this->GlobalModel->getGlobal('users', 'username', $username);

            if ($dataUser) {

                // check password 
                if (password_verify($password, $dataUser->password)) {

                    if ($dataUser->status !== 'active') {
                        $this->response([
                            'error'   => true,
                            'message' => 'Your account is no longer active'
                        ], 200);
                    }

                    $token = $this->jwt($dataUser->id);
                    $this->response([
                        'error'   => false,
                        'message' => 'You have successfully logged in',
                        'data'    => [
                            'token' => $token,
                        ]
                    ], 200);
                } else {
                    $this->response([
                        'error'   => true,
                        'message' => 'Username or password is wrong',
                    ], 200);
                }
            } else {
                $this->response([
                    'error'   => true,
                    'message' => 'Username or password is wrong',
                ], 200);
            }
        } else {
            $this->response([
                'error'   => true,
                'message' => $this->form_validation->error_array(),
            ], 422);
        }
    }

    /**
     * Show data user
     *
     * @return void
     */
    public function show_post()
    {
        $draw   = $this->post('draw');
        $start  = $this->post('start');
        $length = $this->post('length');
        $search = $this->post("search")['value'];

        $data  = $this->UserModel->showUser($start, $length, $search)->result();
        $count = $this->UserModel->showUser($start, $length, $search, true);

        $dataRes = [
            "draw"            => $draw,
            "recordsTotal"    => $count,
            "recordsFiltered" => $count,
            "data"            => $data,
        ];

        $this->response($dataRes, 200);
    }

    /**
     * Store a new data user
     *
     * @method POST
     * @return void
     */
    public function store_post()
    {
        $username = trim($this->post('username'));
        $password = trim($this->post('password'));
        $email    = trim($this->post('email'));
        $phone    = trim($this->post('phone'));

        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|xss_clean|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|xss_clean|is_unique[users.email]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|numeric|xss_clean');

        if ($this->form_validation->run()) {

            $data = array(
                'name'       => $username,
                'username'   => $username,
                'password'   => password_hash($password,PASSWORD_DEFAULT),
                'email'      => $email,
                'phone'      => $phone,
                'status'     => 'active',
                'level'      => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('users', $data);

            if ($this->db->affected_rows()) {

                $this->response([
                    'error'   => false,
                    'message' => 'Successfully saved data user'
                ], 200);
            } else {

                $this->response([
                    'error'   => true,
                    'message' => 'Failed to add user data'
                ], 200);
            }
        } else {

            $this->response([
                'error'   => true,
                'message' => $this->form_validation->error_array(),
            ], 422);
        }
    }

    /**
     * Get data user spesific
     *
     * @method POST
     * @return void
     */
    public function edit_post()
    {
        $username = $this->post('username');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run()) {

            $data = $this->UserModel->getUser('username', $username)->row();

            if (!$data) {
                $this->response([
                    'error'   => true,
                    'message' => 'User not found'
                ], 200);
            }

            $this->response([
                'error' => false,
                'data'  => $data,
            ], 200);
        } else {

            $this->response([
                'error'   => true,
                'message' => $this->form_validation->error_array(),
            ], 422);
        }
    }

    /**
     * Update data user
     *
     * @method POST
     * @return void
     */
    public function update_post()
    {
        $id       = trim($this->post('id'));
        $name     = trim($this->post('name'));
        $username = trim($this->post('username'));
        $password = trim($this->post('password'));
        $email    = trim($this->post('email'));
        $phone    = trim($this->post('phone'));
        $status   = trim($this->post('status'));
        $level    = trim($this->post('level'));

        $this->form_validation->set_rules('id', 'ID', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|xss_clean|callback_username_check');
        $this->form_validation->set_rules('password', 'Password', 'trim|min_length[4]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|callback_email_check');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean');
        $this->form_validation->set_rules('level', 'Level', 'trim|required|xss_clean');

        if ($this->form_validation->run()) {

            $user = array(
                'name'       => $name,
                'username'   => $username,
                'email'      => $email,
                'phone'      => $phone,
                'status'     => ($status == 'on') ? 'active' : 'inactive',
                'level'      => $level,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($password != "") {
                $user = array_merge($user, ['password' => password_hash($password, PASSWORD_DEFAULT)]);
            }

            $this->db->where('id', $id);
            $this->db->update('users', $user);

            $this->response([
                'error'   => false,
                'message' => 'Successfully updated user data'
            ], 200);
        } else {

            $this->response([
                'error'   => true,
                'message' => $this->form_validation->error_array(),
            ], 422);
        }
    }

    /**
     * Call back for check username is unique
     *
     * @param string $str
     * @return void
     */
    public function username_check($str)
    {
        $dbUser = $this->UserModel->getUser('id', $this->post('id'))->row();

        if ( $str != trim($dbUser->username) ) {

            $find = $this->UserModel->findUnique('username', $str);

            if ($find) {
                $this->form_validation->set_message('username_check', '{field} already in use');
                return false;
            }
        }
        return true;
    }

    /**
     * Call back for check email is unique
     *
     * @param string $str
     * @return void
     */
    public function email_check($str)
    {
        $dbUser = $this->UserModel->getUser('id', $this->post('id'))->row();

        if ( $str != trim($dbUser->email) ) {

            $find = $this->UserModel->findUnique('email', $str);

            if ($find) {
                $this->form_validation->set_message('email_check', '{field} already in use');
                return false;
            }
        }
        return true;
    }

    /**
     * Delete user
     *
     * @method POST
     * @return void
     */
    public function delete_post()
    {
        $username = $this->post('username');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|strip_tags');

        
        if ($this->form_validation->run()) {
            
            $this->db->where('username', $username);
            $this->db->delete('users');

            if ($this->db->affected_rows()) {

                $this->response([
                    'error'   => false,
                    'message' => 'Successfully deleted user data',
                ], 200);
            } else {
                $this->response([
                    'error'   => true,
                    'message' => 'Failed to delete user data',
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
        
/* End of file  Users.php */
