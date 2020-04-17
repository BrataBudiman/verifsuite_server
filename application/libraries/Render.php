<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package Template Render 
 * @author AdiStwn
 * @since 2017
 * @license https://opensource.org/licenses/MIT MIT License
 */

class Render {
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function view($directory, $data=NULL, $returnhtml=FALSE)
    {
        $data['content'] = $directory;
        $viewdata  = (empty($data)) ? $data : $data;
        $view_html = $this->CI->load->view('layouts/content', $viewdata, $returnhtml);;
        if (!$returnhtml) return $view_html;
    }

}

/* End of file Render.php */
/* Location: ./application/libraries/Render.php */
