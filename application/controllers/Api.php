<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
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
}
