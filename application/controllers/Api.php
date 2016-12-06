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
        $this->load->model('Sars_model');
        $this->load->model('Sar_has_kpis_model');

        $schedules = $this->Schedules_model->find_all();

        $rules = $this->Role_has_rules_model->find_all();
        $ruleList = array();
        if ($rules) {
            foreach ($rules as $value) {
                $ruleList[$value->role_id][] = $value->rule_id;
            }
        }

        //echo '<pre>';
        if ($schedules) {
            foreach ($schedules as $value_1) {
                // if
                //print_r($value_1);
                $course_id = $value_1->course_id;
                $sar_id = $this->Sars_model->save($course_id);
                //echo '<hr>';
                $teacher_has_courses = $this->Teacher_has_courses_model->find_course($course_id);
                //print_r($teacher_has_courses);
                $checkRoles = array();
                //echo '<hr>';
                foreach ($teacher_has_courses as $value_2) {
                    $this->_check_rule($course_id, $sar_id, $value_2->teacher_id, $value_2->researcher_id, $value_2->role_id);
                    //print_r($value_2);
                }
            }
        }

        $data = array('result' => 1, 'data' => 'success');

        return $this->json($data);
    }

    private function _check_rule($course_id, $sar_id, $teacher_id, $researcher_id, $role_id)
    {
        $rules = $this->Role_has_rules_model->find($role_id);
        // Map Role and KPI
        $mapRoleKpi = array();
        $mapRoleKpi[1] = 6;
        $mapRoleKpi[2] = 4;
        $mapRoleKpi[3] = 5;
        $mapRoleKpi[4] = 7;
        $mapRoleKpi[5] = 8;
        $mapRoleKpi[6] = 9;

        if ($rules && isset($mapRoleKpi[$role_id])) {
            $kpi_id = $mapRoleKpi[$role_id];
            $ruleList = array();
            $pass = true;
            foreach ($rules as $value) {
                $inCase = false;
                switch ($value->rule_id) {
                    case 1:
                        $inCase = true;
                        $checked = $this->Kpis_model->check_rule_1($researcher_id);
                    break;
                    case 2:
                        $inCase = true;
                        $checked = $this->Kpis_model->check_rule_2($researcher_id);
                    break;
                    case 3:
                        $inCase = true;
                        $checked = $this->Kpis_model->check_rule_3($researcher_id);
                    break;
                    case 4:
                        $inCase = true;
                        $checked = $this->Kpis_model->check_rule_4($researcher_id);
                    break;
                    case 5:
                        $inCase = true;
                        $checked = $this->Kpis_model->check_rule_5($researcher_id);
                    break;
                    case 6:
                        $inCase = true;
                        $checked = $this->Kpis_model->check_rule_6($researcher_id);
                    break;
                    case 7:
                        $inCase = true;
                        $checked = $this->Kpis_model->check_rule_7($researcher_id);
                    break;
                    case 8:
                        $inCase = true;
                        $checked = $this->Kpis_model->check_rule_8($course_id, $role_id);
                    break;
                    case 9:
                        $inCase = true;
                        $checked = $this->Kpis_model->check_rule_9($course_id, $role_id);
                    break;
                    case 10:
                        $inCase = true;
                        $checked = $this->Kpis_model->check_rule_10($course_id, $teacher_id);
                    break;
                    default:
                        //$checked = true;
                    break;
                }
                if ($inCase) {
                    if ($checked) {
                        $this->Sar_has_kpis_model->update_pass_rule($sar_id, $kpi_id, $teacher_id, $value->rule_id);
                    } else {
                        $pass = false;
                        $this->Sar_has_kpis_model->update_fail_rule($sar_id, $kpi_id, $teacher_id, $value->rule_id);
                    }
                }
            }
        }

        return $pass;
    }
}
