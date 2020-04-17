<?php

/**
 * Global model
 * 
 * @package CI_Model
 * @author AdiStwn
 * @since 2017
 * @license https://opensource.org/licenses/MIT MIT License
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class GlobalModel extends CI_Model
{
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Store a new data to database
     * 
     * @param string $table
     * @param array $data
     * @return string Insert ID
     */
    public function addGlobal(string $table, string $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * Get first data
     *
     * @param string $table
     * @param string $field
     * @param string $id
     * @return void row
     */
    public function getGlobal(string $table, string $field, string $id)
    {
        $this->db->from($table);
        $this->db->where($field,$id);
        return $this->db->get()->row();
    }

    /**
     * Get all data with where array
     *
     * @param string $table
     * @param boolean $array default=false
     * @return void
     */
    public function getArrayGlobal(string $table, array $array = [])
    {
        $this->db->from($table);
        if(count($array) > 0):
            $this->db->where($array);
        endif;
        $query = $this->db->get();
        return $query;
    }

    /**
     * Get all data
     *
     * @param string $table
     * @param string $field
     * @param string $id
     * @return void
     */
    public function getAllGlobal(string $table, string $field='', string $id='')
    {
        $this->db->from($table);
        if ($field !== '') {
            $this->db->where($field,$id);
        }
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Update data
     *
     * @param string $table
     * @param array $data
     * @param string $id
     * @param string $field
     * @return int affected_rows()
     */
    public function updateGlobal(string $table, array $data, string $id, string $field)
    {
        $this->db->where($field, $id);
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }

    /**
     * Delete data
     *
     * @param string $table
     * @param string $field
     * @param string $id
     * @return int affected_rows()
     */
    public function deleteGlobal(string $table, string $field, string $id)
    {
        $this->db->where($field, $id);
        $this->db->delete($table);
        return $this->db->affected_rows();
    }

    /**
     * Counting all data
     *
     * @param string $table
     * @return int
     */
    public function countGlobal(string $table)
    {
        return $this->db->count_all_results($table);
    }

}

/* End of file GlobalModel.php */
/* Location: ./application/models/GlobalModel.php */