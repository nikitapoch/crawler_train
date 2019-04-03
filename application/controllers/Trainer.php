<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Trainer extends CI_Controller {

    var $session_user;

    function __construct() {
        parent::__construct();

        Utils::no_cache();
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('auth/login'));
            exit;
        }
        $this->session_user = $this->session->userdata('logged_in');
        
    }

    /*
     * 
     */

    public function index() {
        $data['title'] = 'Trainer';
        $data['active_menu'] = 'trainer';
        $data['class_options'] = array(
                        'positive'   => 'Positive Dealerships',
                        'negative'   => 'Negative Dealerships'
                        // 'test'       => 'Test Dealerships',
                        // 'unknown'    => 'Unknown Dealerships'
                    );
        $data['selected_class'] = 'positive';
        $data['class_style'] = 'style="height: 34px; float: right;"';
        $data['session_user'] = $this->session_user;
        $data['notif'] = array();

        $this->load->model('train_dealership_by_trainers_model');

        if (@$_POST['submit_dealership_add']) {
            $domain = $_POST['domain'];
            $class = $_POST['class'];

            $this->train_dealership_by_trainers_model->add($domain, $class);

            $notif = array();
            $notif['message'] = 'Saved successfully !';
            $notif['type'] = 'success';
            $data['notif']['dealership_add'] = $notif;
        }
        else if (@$_POST['submit_dealership_csv']) {
            $class = $_POST['class'];
            $config = array(
                'upload_path' => "./uploads/",
                'allowed_types' => "csv",
                'overwrite' => TRUE,
                'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
            );
            $this->load->library('upload', $config);
            if($this->upload->do_upload())
            {
                $uploaded_data = array('upload_data' => $this->upload->data());
                $orig_file = $uploaded_data['upload_data']['full_path'];

                $ext = strtolower(pathinfo($orig_file, PATHINFO_EXTENSION));
                if ($ext == 'csv') 
                {
                    $file = fopen($orig_file, 'r');
                    while (($line = fgetcsv($file)) !== FALSE) {
                        $domain = $line[0];
                        $this->train_dealership_by_trainers_model->add($domain, $class);
                    }
                }
                $notif = array();
                $notif['message'] = 'Uploaded successfully !';
                $notif['type'] = 'success';
                $data['notif']['dealership_csv'] = $notif;
                if (file_exists($orig_file)) {
                    unlink($orig_file);
                }
            }
            else
            {
                $notif = array();
                $notif['message'] = 'Upload error !';
                $notif['type'] = 'danger';
                $data['notif']['dealership_csv'] = $notif;
            }
        }

        $data['status_positive_count'] = $this->train_dealership_by_trainers_model->get_count_positive();
        $data['status_negative_count'] = $this->train_dealership_by_trainers_model->get_count_negative();
        /*
         * Load view
         */
        $this->load->view('includes/header', $data);
        $this->load->view('includes/navbar');
        $this->load->view('trainer/index');
        $this->load->view('includes/footer');
    }

}
