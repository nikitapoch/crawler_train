<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Train_dealership_negative_model extends CI_Model {

    private $table = "train_dealership_negative";

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
    
    function get_count()
    {
        $sql = "SELECT count(*) AS total FROM `train_dealership_negative`";
        // $sql = "SELECT count(*) AS total FROM `train_dealership_positive` WHERE ";
        // $sql .= " tb_title LIKE '%" . $search . "%' ";
        // $sql .= " OR tb_desc LIKE '%" . $search . "%' ";
        // $sql .= " OR tb_url LIKE '%" . $search . "%' ";
        // $sql .= " OR tb_image LIKE '%" . $search . "%' ";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result[0]->{'total'};
    }

    function get_nrows($count)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->limit($count);
        return $query = $this->db->get()->result();
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

    function remove_already_trained() 
    {
        $rows = $this->get_rows();
        foreach ($rows as $row) {
            if ($row->{'domain'}=='' || $this->is_already_trained($row->{'domain'})) {
                $this->delete($row->{'id'});
            }
        }
    }

    function is_already_trained($domain) {
        $sql = "SELECT * FROM trained_dealership_domains WHERE domain = " . $this->db->escape($domain);
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            return true;
        }
        return false;
    }

}