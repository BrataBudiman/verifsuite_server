<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('is_level') )
{
    /**
     * Checking level
     *
     * @param array $level
     * @param boolean $redirect
     * @return void
     */
    function is_level(array $level, bool $redirect = true)
    {
        $CI =& get_instance();
        $session_level = $CI->session->userdata('level');
        
        if ( ! in_array($session_level, $level) )
        {
            if ($redirect === true)
            {
                redirect('home');
            }
            else
            {
                $res['message'] = 'Forbidden Access!';
                $res['error']   = true;
                echo json_encode($res); exit;
            }
        }
    }
}

if ( ! function_exists('is_login') )
{
    function is_login()
    {
        $CI =& get_instance();
        $id_account = $CI->session->userdata('id_account');

        if ( ! $id_account )
        {
            redirect('login');
        }
    }
}

if ( ! function_exists('is_login_api') )
{
    function is_login_api()
    {
        $CI =& get_instance();
        $id_account = $CI->session->userdata('id_account');

        if ( ! $id_account )
        {
            $res['message'] = 'You need login';
            $res['error']   = true;
            echo json_encode($res);
            exit;
        }
    }
}

/* End of file auth.php */
    
                        