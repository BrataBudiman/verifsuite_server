<?php

/**
 * Home Controller
 * 
 * @author Adi
 * @author Brata
 * @package CI Controller
 * @license PT Asia Sekuriti Indonesia
 */

defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo "Its Work";
        // $this->render->view('layouts/default');
    }

}
        
/* End of file  Home.php */
        
                            