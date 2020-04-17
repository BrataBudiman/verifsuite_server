<?php

/**
 * User Model
 * 
 * @author Adi
 * @author Brata
 * @package Rest Controller
 * @copyright 2020 PT Asia Sekuriti Indonesia
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model
{

    /**
     * Show data user
     *
     * @param integer $start
     * @param integer $length
     * @param string $search
     * @param boolean $count
     * @return void
     */
    public function showUser(int $start, int $length, string $search, bool $count = false)
    {
        $this->db->from('users');

        if ($search) {
            $this->db->like('name', $search);
            $this->db->or_like('username', $search);
        }

        if ($count === false) {
            return $this->db->limit($length, $start)->get();
        }

        return $this->db->count_all_results();
    }

    /**
     * Find data user by id
     *
     * @param string $field
     * @param string $value
     * @return void
     */
    public function getUser(string $field, string $value)
    {
        $this->db->from('users');
        $this->db->where($field, $value);
        return $this->db->get();
    }

    /**
     * Find unique by param
     *
     * @param string $where
     * @param string $value
     * @return void
     */
    public function findUnique(string $field, string $value)
    {
        $this->db->from('users');
        $this->db->where($field, $value);
        return $this->db->get()->row();
    }    

}

/* End of file UserModel.php */
