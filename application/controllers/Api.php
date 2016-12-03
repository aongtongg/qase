<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('session');
    }

    private function json($data)
    {
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function getCourses()
    {
        $this->load->model('Courses_model');
        $data = $this->Courses_model->find_all();

        return $this->json($data);
    }
    public function login()
    {
        $data = array('result' => 0, 'data' => null);
        if ($this->input->post('username') && $this->input->post('password')) {
            $this->load->model('Members_model');
            $data = $this->Members_model->login($this->input->post('username'), $this->input->post('password'));
            if ($data) {
                $this->session->set_userdata('members_id', $data->members_id);
                $this->session->set_userdata('members_class', $data->members_class);
                $this->session->set_userdata('members_email', $data->members_email);
                $this->session->set_userdata('members_status', $data->members_status);
                $this->session->set_userdata('members_first_name', $data->members_first_name);
                $this->session->set_userdata('members_last_name', $data->members_last_name);
                $data = array('result' => 1, 'data' => 'success');
            } else {
                $data = array('result' => 0, 'data' => 'error', 'message' => 'กรุณากรอกชื่อผู้ใช้หรือรหัสผ่านให้ถูกต้อง');
            }
        }

        return $this->json($data);
    }
}
