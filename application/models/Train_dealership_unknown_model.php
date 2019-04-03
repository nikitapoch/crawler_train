<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Train_dealership_unknown_model extends CI_Model {

    private $table = "train_dealership_unknown";

    function __construct() {
        parent::__construct();
    }

    /*
     * 
     */
    function get_row($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->result();
    }
    
    function get_rows()
    {
        $query = $this->db->get_where($this->table);
        return $query->result();
    }

    function insert($data) {
        // Inserting into your table
        $this->db->insert($this->table, $data);
        $idOfInsertedData = $this->db->insert_id();
        return $idOfInsertedData;
    }

    function delete($id){
        $this->db->where('id', $id);
        $this->db->delete($this->table); 
    }

    function get_row_by_domain($domain)
    {
        $query = $this->db->get_where($this->table, array('domain' => $domain));
        $result = $query->result();

        return count($result) > 0 ? $result[0] : null;
    }

    function add($domain, $class='positive') 
    {
        $row = $this->get_row_by_domain($domain);
        if (!$row) {
            $data = array(
                'domain'    => $domain
            );
            $this->insert($data);
            // $id = $row -> {'id'};
            // $sql = "UPDATE train_dealership_positive SET class =" . $this->db->escape($class) . " WHERE id=" . $this->db->escape($id);
            // $this->db->query($sql);
        }
    }
}