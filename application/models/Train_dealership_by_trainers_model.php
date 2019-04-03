<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class train_dealership_by_trainers_model extends CI_Model {

    private $table = "train_dealership_by_trainers";

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

    function get_count_positive()
    {
        $sql = "SELECT count(*) AS total FROM `train_dealership_by_trainers` WHERE ";
        $sql .= " class LIKE " . $this->db->escape('positive');
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result[0]->{'total'};
    }

    function get_count_negative()
    {
        $sql = "SELECT count(*) AS total FROM `train_dealership_by_trainers` WHERE ";
        $sql .= " class LIKE " . $this->db->escape('negative');
        // $sql .= " OR tb_desc LIKE '%" . $search . "%' ";
        // $sql .= " OR tb_url LIKE '%" . $search . "%' ";
        // $sql .= " OR tb_image LIKE '%" . $search . "%' ";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result[0]->{'total'};
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
                'domain'    => $domain,
                'class'     => $class
            );
            $this->insert($data);
        }
    }
}