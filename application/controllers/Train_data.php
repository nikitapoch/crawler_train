<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Train_data extends CI_Controller {

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
        $data['title'] = 'Train Data';
        $data['active_menu'] = 'train_data';
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

        $this->load->model('train_dealership_positive_model');
        $this->load->model('train_dealership_negative_model');
        $this->load->model('train_dealership_test_model');
        $this->load->model('train_dealership_unknown_model');
        $this->load->model('train_initial_params_model');
        $this->load->model('train_dealership_by_trainers_model');

        if (@$_POST['submit_dealership_excel']) {
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
                    if ($class == 'positive') {
                        $file = fopen($orig_file, 'r');
                        while (($line = fgetcsv($file)) !== FALSE) {
                            $domain = $line[0];
                            $this->train_dealership_positive_model->add($domain);
                        }
                    }
                    else if ($class == 'negative') {
                        $file = fopen($orig_file, 'r');
                        while (($line = fgetcsv($file)) !== FALSE) {
                            $domain = $line[0];
                            $this->train_dealership_negative_model->add($domain);
                        }
                    }
                    else if ($class == 'test') {
                        $file = fopen($orig_file, 'r');
                        while (($line = fgetcsv($file)) !== FALSE) {
                            $domain = $line[0];
                            $this->train_dealership_test_model->add($domain);
                        }
                    }
                    else if ($class == 'unknown') {
                        $file = fopen($orig_file, 'r');
                        while (($line = fgetcsv($file)) !== FALSE) {
                            $domain = $line[0];
                            $this->train_dealership_unknown_model->add($domain);
                        }
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
        else if (@$_POST['submit_initial_params'])
        {
            $positive_portion = $_POST['positive_portion'];
            $negative_portion = $_POST['negative_portion'];

            if ($positive_portion <= 0 || $positive_portion >= 1 || $negative_portion <= 0 || $negative_portion >= 1) {
                $notif = array();
                $notif['message'] = 'Potion value is not valid, it should be in 0~0.1 !';
                $notif['type'] = 'danger';
                $data['notif']['initial_params'] = $notif;
            } else {
                $this->load->model('train_initial_params_model');
                $this->train_initial_params_model->set_portion($positive_portion, $negative_portion);

                $notif = array();
                $notif['message'] = 'Portions saved successfully !';
                $notif['type'] = 'success';
                $data['notif']['initial_params'] = $notif;
            }
        }
        else if (@$_POST['submit_move_train2test'])
        {
            $rows = $this->train_initial_params_model->get_rows();
            if (count($rows) > 0) {
                $row = $rows[0];
                $positive_portion = $row->{'test_positive_portion'};
                $negative_portion = $row->{'test_negative_portion'};

                $count = $this->train_dealership_positive_model->get_count();
                $count *= $positive_portion;
                if ($count > 0) {
                    $rows = $this->train_dealership_positive_model->get_nrows($count);
                    foreach ($rows as $row) {
                        $id = $row -> {'id'};
                        $domain = $row -> {'domain'};

                        $this->train_dealership_test_model->add($domain, 'positive');
                        $this->train_dealership_positive_model->delete($id);
                    }
                }

                $count = $this->train_dealership_negative_model->get_count();
                $count *= $negative_portion;
                if ($count > 0) {
                    $rows = $this->train_dealership_negative_model->get_nrows($count);
                    foreach ($rows as $row) {
                        $id = $row -> {'id'};
                        $domain = $row -> {'domain'};

                        $this->train_dealership_test_model->add($domain, 'positive');
                        $this->train_dealership_negative_model->delete($id);
                    }
                }
            }

            $notif = array();
            $notif['message'] = 'Training data moved to Test successfully !';
            $notif['type'] = 'success';
            $data['notif']['initial_params'] = $notif;
        }
        else if (@$_POST['submit_move_test2train'])
        {
            $rows = $this->train_dealership_test_model->get_rows();
            foreach ($rows as $row) {
                $id = $row -> {'id'};
                $domain = $row -> {'domain'};
                $class = $row -> {'class'};

                if ($class == 'positive') {
                    $this->train_dealership_positive_model->add($domain);
                } else if ($class == 'negative') {
                    $this->train_dealership_negative_model->add($domain);
                }
                $this->train_dealership_test_model->delete($id);
            }

            $notif = array();
            $notif['message'] = 'Test data moved to Train successfully !';
            $notif['type'] = 'success';
            $data['notif']['initial_params'] = $notif;
        }
        else if (@$_POST['submit_move_trainers_data'])
        {
            $rows = $this->train_dealership_by_trainers_model->get_rows();
            foreach ($rows as $row) {
                $id = $row -> {'id'};
                $domain = $row -> {'domain'};
                $class = $row -> {'class'};

                if ($class == 'positive') {
                    $this->train_dealership_positive_model->add($domain);
                } else if ($class == 'negative') {
                    $this->train_dealership_negative_model->add($domain);
                }
                $this->train_dealership_by_trainers_model->delete($id);
            }

            $notif = array();
            $notif['message'] = 'Trainers data moved to Main successfully !';
            $notif['type'] = 'success';
            $data['notif']['trainers_data'] = $notif;
        }
        else if (@$_POST['submit_remove_trained_domains'])
        {
            $this->train_dealership_positive_model->remove_already_trained();
            $this->train_dealership_negative_model->remove_already_trained();
        }

        $rows = $this->train_initial_params_model->get_rows();
        if (count($rows) == 0) {
            $data['positive_portion'] = 0;
            $data['negative_portion'] = 0;
            $data['is_running_train'] = false;
        } else {
            $row = $rows[0];
            $data['positive_portion'] = $row->{'test_positive_portion'};
            $data['negative_portion'] = $row->{'test_negative_portion'};
            $data['is_running_train'] = $row->{'is_runing_train'};
        }

        $data['status_positive_count'] = $this->train_dealership_positive_model->get_count();
        $data['status_negative_count'] = $this->train_dealership_negative_model->get_count();
        $data['status_test_positive_count'] = $this->train_dealership_test_model->get_count_positive();
        $data['status_test_negative_count'] = $this->train_dealership_test_model->get_count_negative();
        $data['trainers_positive_count'] = $this->train_dealership_by_trainers_model->get_count_positive();
        $data['trainers_negative_count'] = $this->train_dealership_by_trainers_model->get_count_negative();
        /*
         * Load view
         */
        $this->load->view('includes/header', $data);
        $this->load->view('includes/navbar');
        $this->load->view('train_data/index');
        $this->load->view('includes/footer');
    }

}
