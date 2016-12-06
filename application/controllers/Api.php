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

    public function getCourseYear()
    {
        $this->load->model('Courses_model');
        $data = array('result' => 0, 'data' => null);
        if ($this->input->post('course_year')) {
            $data = $this->Courses_model->getCourseYear($this->input->post('course_year'));
            if ($data) {
                $data = array('result' => 1, 'data' => $data);
            } else {
                $data = array('result' => 1, 'data' => null);
            }
        }

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

    public function execute()
    {
        $this->load->model('Schedules_model');
        $this->load->model('Teacher_has_courses_model');
        $this->load->model('Role_has_rules_model');
        $this->load->model('Kpis_model');

        $schedules = $this->Schedules_model->find_all();

        $rules = $this->Role_has_rules_model->find_all();
        $ruleList = array();
        if ($rules) {
            foreach ($rules as $value) {
                $ruleList[$value->role_id][] = $value->rule_id;
            }
        }

        echo '<pre>';
        print_r($ruleList);
        if ($schedules) {
            $this->_check_rule(10, 1972, 6);
            foreach ($schedules as $value_1) {
                $teacher_has_courses = $this->Teacher_has_courses_model->find_course($value_1->course_id);
                $checkRoles = array();
                echo '<hr>';
                foreach ($teacher_has_courses as $value_2) {
                    print_r($value_2);
                    $role_id = $value_2->role_id;
                    if ($role_id == 6) {
                        //$checkRoles[$role_id][] = '';
                    }
                }
                //print_r($teacher_has_courses);
                //break;
            }
            echo '<hr>';
        }
        print_r($schedules);
        die();
    }
    private function _check_rule($teacher_id, $researcher_id, $role_id)
    {
        $rules = $this->Role_has_rules_model->find($role_id);
        if ($rules) {
            $ruleList = array();
            foreach ($rules as $value) {
                switch ($value->rule_id) {
                    case 1:

                    break;
                    case 2:
                        $checked = $this->Kpis_model->check_rule_2($researcher_id);
                        if ($checked) {

                        } else {

                        }
                    break;
                    case 3:

                    break;
                    case 4:

                    break;
                    case 5:

                    break;
                    case 6:

                    break;
                    case 7:

                    break;
                    case 8:

                    break;
                    case 9:

                    break;
                    case 10:

                    break;
                    default:
                    break;
                }
            }
        }

        return false;
    }
}
