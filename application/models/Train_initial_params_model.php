<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Train_initial_params_model extends CI_Model {

    private $table = "train_initial_params";

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

    function set_portion($positive_portion=0.05, $negative_portion=0.05)
    {
        $rows = $this->get_rows();
        if (count($rows) == 0) {
            $data = array(
                'test_positive_portion' => $positive_portion,
                'test_negative_portion' => $negative_portion
            );
            $this->insert($data);
        } else {
            $id = $rows[0] -> {'id'};
            $sql = "UPDATE train_initial_params SET test_positive_portion=" . $this->db->escape($positive_portion);
            $sql .= ", test_negative_portion=" . $this->db->escape($negative_portion);
            $sql .= " WHERE id=" . $this->db->escape($id);
            $this->db->query($sql);
        }
    }
}