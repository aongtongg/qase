<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Teacher extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->_init();
    }

    private function _init()
    {
        $this->output->set_template('qase');

        $this->load->js('assets/themes/qase/js/validator.min.js');

        $this->load->js('assets/themes/qase/js/bootstrap-select.min.js');
        $this->load->css('assets/themes/qase/css/bootstrap-select.min.css');
    }

    public function index()
    {
        $this->output->set_common_meta('QASE', '', '');
        $this->load->model('Teachers_model');
        $teachers = $this->Teachers_model->find_all();
        $view['teachers'] = $teachers;

        $this->load->view('teacher/index', $view);
    }
}
