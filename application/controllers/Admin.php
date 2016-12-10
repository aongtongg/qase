<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->_init();
    }

    private function _init()
    {
        $method = $this->uri->segment(2);
        if ($method != '' && $method != 'logout') {
            //qase_redirect
            $getParam = true;
            $i = 1;
            $url = '';
            while ($getParam == true) {
                if ($this->uri->segment($i)) {
                    $url .= $this->uri->segment($i).'/';
                } else {
                    $getParam = false;
                }
                // Max
                if ($i == 10) {
                    $getParam = false;
                }
                ++$i;
            }
            $this->session->set_userdata('qase_redirect', $url);

            if (!isset($_SESSION['members_class'])) {
                redirect(base_url(), 'refresh');
            } elseif ($_SESSION['members_class'] != 2) {
                if ($method != 'sars' && $method != 'sar') {
                    redirect(base_url(), 'refresh');
                }
            }
        }
        $this->output->set_template('qase');
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Quality Assurance', '/admin/');

        $this->load->js('assets/themes/qase/js/validator.min.js');
    }

    /* Index page */
    public function index()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $view['message'] = '';
        if ($this->form_validation->run() == false) {
            //if (isset($this->session->userdata['members_class'])) {
            if (isset($_SESSION['members_class'])) {
                $this->load->view('admin/index', $view);
            } else {
                $this->load->view('admin/login', $view);
            }
        } else {
            $this->load->model('Members_model');
            $data = $this->Members_model->login($this->input->post('username'), $this->input->post('password'));
            if ($data) {
                $this->session->set_userdata('members_id', $data->members_id);
                $this->session->set_userdata('members_class', $data->members_class);
                $this->session->set_userdata('members_email', $data->members_email);
                $this->session->set_userdata('members_status', $data->members_status);
                $this->session->set_userdata('members_first_name', $data->members_first_name);
                $this->session->set_userdata('members_last_name', $data->members_last_name);

                $this->load->view('admin/index', $view);
            } else {
                $view['message'] = 'กรุณากรอกชื่อผู้ใช้หรือรหัสผ่านให้ถูกต้อง';
                $this->load->view('admin/login', $view);
            }
        }
        $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
    }

    public function logout()
    {
        //$this->session->unset_userdata('members_class');
        $user_data = $this->session->all_userdata();
        foreach ($user_data as $key => $value) {
            $this->session->unset_userdata($key);
        }
        redirect('/admin', 'refresh');
    }

    /* Courses page */
    public function courses()
    {
        $this->load->model('Courses_model');

        $data = $this->Courses_model->find_all();
        $view['data'] = $data;
        $view['controller'] = $this;

        $this->breadcrumbs->push('หลักสูตรที่เปิดสอน', '/admin/courses');
        $view['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('admin/courses', $view);
        $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
    }

    /* Add courses page */
    public function course_add()
    {
        $this->load->model('Courses_model');
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
            //if ($this->input->post('course_name') && $this->input->post('course_start_date') && $this->input->post('course_estimate_date')) {
            if ($this->input->post('course_name') && $this->input->post('course_estimate_date')) {
                if ($this->input->post('course_start_date') < $this->input->post('course_estimate_date')) {
                    $this->course_save();
                } else {
                    $view['message'] = 'ไม่อนุญาตให้วันที่ประเมินก่อนวันที่เริ่มหลักสูตร';
                }
            }
        }

        // Course Year lists
        $courseYearLists = array();
        for ($i = date('Y') + 3; $i > 2011; --$i) {
            $courseYearLists[$i] = $i + 543;
        }
        $view['courseYearLists'] = $courseYearLists;

        // Course Name Lists
        $courseNameLists = array();
        $courseNameLists[] = array('title' => 'ป.โท ภาคปกติ', 'code' => 'MSCS');
        $courseNameLists[] = array('title' => 'ป.โท ภาคพิเศษ', 'code' => 'MSCS(SP)');
        $view['courseNameLists'] = $courseNameLists;

        $this->breadcrumbs->push('หลักสูตรที่เปิดสอน', '/admin/courses');
        $this->breadcrumbs->push('เพิ่มหลักสูตร', '/admin/course_add/');
        $view['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('admin/course_add', $view);
        $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
    }

    /* Edit courses page */
    public function course_edit()
    {
        $this->load->model('Courses_model');
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
            //if ($this->input->post('course_name') && $this->input->post('course_start_date') && $this->input->post('course_estimate_date')) {
            if ($this->input->post('course_name') && $this->input->post('course_estimate_date')) {
                if ($this->input->post('course_start_date') < $this->input->post('course_estimate_date')) {
                    $this->course_save('update');
                } else {
                    $view['message'] = 'ไม่อนุญาตให้วันที่ประเมินก่อนวันที่เริ่มหลักสูตร';
                }
            }
        }
        $data = $this->Courses_model->find($this->uri->segment(3));
        if ($data) {
            $view['data'] = $data;

            // Course Year lists
            $courseYearLists = array();
            for ($i = date('Y') + 3; $i > 2011; --$i) {
                $courseYearLists[$i] = $i + 543;
            }
            $view['courseYearLists'] = $courseYearLists;

            // Course Name Lists
            $courseNameLists = array();
            $courseNameLists[] = array('title' => 'ป.โท ภาคปกติ', 'code' => 'MSCS');
            $courseNameLists[] = array('title' => 'ป.โท ภาคพิเศษ', 'code' => 'MSCS(SP)');
            $view['courseNameLists'] = $courseNameLists;

            $this->breadcrumbs->push('หลักสูตรที่เปิดสอน', '/admin/courses');
            $this->breadcrumbs->push('แก้ไขหลักสูตร', '/admin/course_add/');
            $view['breadcrumbs'] = $this->breadcrumbs->show();
            $this->load->view('admin/course_edit', $view);
            $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
        } else {
            redirect('/admin/courses', 'refresh');
        }
    }

    /* Insert/Update course */
    private function course_save($type = 'insert')
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        // Course Name Lists
        $courseNameLists = array();
        $courseNameLists['MSCS'] = 'ป.โท ภาคปกติ';
        $courseNameLists['MSCS(SP)'] = 'ป.โท ภาคพิเศษ';
        if (isset($courseNameLists[$_POST['course_name']])) {
            $_POST['course_code'] = $_POST['course_name'];
            $_POST['course_name'] = $courseNameLists[$_POST['course_name']];
        }

        $this->form_validation->set_rules('course_name', 'course_name', 'trim|required');
        $this->form_validation->set_rules('course_code', 'course_code', 'trim|required');
        //$this->form_validation->set_rules('course_start_date', 'course_start_date', 'trim|required');
        $this->form_validation->set_rules('course_estimate_date', 'course_estimate_date', 'trim|required');

        if ($this->form_validation->run()) {
            if ($type = 'update') {
                $id = $this->uri->segment(3);
                $data = $this->Courses_model->save($_POST, $id);
            } else {
                $data = $this->Courses_model->save($_POST);
            }

            if ($data) {
                redirect('/admin/courses', 'refresh');
            }
        }
    }

    /* Delete courses */
    public function course_delete()
    {
        $this->load->model('Courses_model');
        $data = $this->Courses_model->delete($this->uri->segment(3));
        redirect('/admin/courses', 'refresh');
    }

    /* Teacher has courses page */
    public function teacher_has_courses()
    {
        $course_id = $this->uri->segment(3);

        $this->load->model('Teacher_has_courses_model');

        if ($course_id) {
            $data = $this->Teacher_has_courses_model->find_course($course_id);
            if ($data) {
                $view['course_year'] = $data;
            } else {
                redirect('/admin/teacher_has_courses', 'refresh');
            }
        } else {
            $data = $this->Teacher_has_courses_model->find_all_course();
            $view['data'] = $data;
        }

        $this->breadcrumbs->push('หลักสูตร และบทบาท', '/admin/teacher_has_courses');
        if ($course_id) {
            $this->breadcrumbs->push('หลักสูตร และบทบาท', '/admin/teacher_has_courses/'.$course_id);
        }
        $view['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('admin/teacher_has_courses', $view);
        $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
    }

    /* Add teacher has courses page */
    public function teacher_has_course_add()
    {
        $course_id = $this->uri->segment(3);

        $this->load->model('Teacher_has_courses_model');
        $this->load->model('Courses_model');

        // Teacher lists
        $this->load->model('Teachers_model');
        $teachers = $this->Teachers_model->find_all();
        $teacherLists = array();
        if ($teachers) {
            foreach ($teachers as $value) {
                $teacherLists[$value->teacher_id] = $value->first_name.' '.$value->last_name;
            }
        }

        // Role lists
        $this->load->model('Roles_model');
        $roles = $this->Roles_model->find_all();
        $roleLists = array();
        if ($roles) {
            foreach ($roles as $value) {
                $roleLists[$value->role_id] = $value->role_name;
            }
        }

        if ($course_id) {
            $data = $this->Teacher_has_courses_model->find_course($course_id);
            if (!$data) {
                redirect('/admin/teacher_has_courses', 'refresh');
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
                $this->load->helper('form');
                $this->load->library('form_validation');
                $this->form_validation->set_rules('teacher_id', 'teacher_id', 'trim|required');
                $this->form_validation->set_rules('role_id', 'role_id', 'trim|required');
                if ($this->form_validation->run()) {
                    $check_before_save = $this->Teacher_has_courses_model->check_before_save($course_id, $_POST['role_id'], $_POST['teacher_id']);
                    if (!$check_before_save) {
                        $data = $this->Teacher_has_courses_model->save_course($course_id, $_POST);
                        if ($data) {
                            redirect('/admin/teacher_has_courses/'.$course_id, 'refresh');
                        }
                    } else {
                        $view['message'] = 'มีอาจารย์ และบทบาทนี้ในหลักสูตรแล้ว';
                    }
                }
            }
        } else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
                $this->load->helper('form');
                $this->load->library('form_validation');
                $this->form_validation->set_rules('course_year', 'course_year', 'trim|required');
                $this->form_validation->set_rules('course_id', 'course_id', 'trim|required');
                $this->form_validation->set_rules('teacher_id', 'teacher_id', 'trim|required');
                $this->form_validation->set_rules('role_id', 'role_id', 'trim|required');
                if ($this->form_validation->run()) {
                    $check_before_save = $this->Teacher_has_courses_model->check_before_save($_POST['course_id'], $_POST['role_id'], $_POST['teacher_id']);
                    if (!$check_before_save) {
                        $data = $this->Teacher_has_courses_model->save_course($_POST['course_id'], $_POST);
                        if ($data) {
                            redirect('/admin/teacher_has_courses/'.$course_id, 'refresh');
                        }
                    } else {
                        $view['message'] = 'มีอาจารย์ และบทบาทนี้ในหลักสูตรแล้ว';
                    }
                }
            }

            // Course Year lists
            $courseYearLists = array();
            $listYear = $this->Courses_model->getYear();
            foreach ($listYear as $value) {
                $courseYearLists[$value->course_year] = $value->course_year + 543;
            }
            $view['courseYearLists'] = $courseYearLists;
        }
        $view['course_id'] = $course_id;
        $view['teacherLists'] = $teacherLists;
        $view['roleLists'] = $roleLists;

        $this->breadcrumbs->push('หลักสูตร และบทบาท', '/admin/teacher_has_courses');
        if ($course_id) {
            $this->breadcrumbs->push('หลักสูตร และบทบาท', '/admin/teacher_has_courses/'.$course_id);
        }
        $this->breadcrumbs->push('เพิ่มหลักสูตร และบทบาท', '/admin/teacher_has_course_add');
        $view['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('admin/teacher_has_course_add', $view);
        $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
    }

    /* Edit teacher has courses page */
    public function teacher_has_course_edit()
    {
        $course_id = $this->uri->segment(3);
        $role_id = $this->uri->segment(4);
        $teacher_id = $this->uri->segment(5);

        if ($course_id && $role_id && $teacher_id) {
            $this->load->model('Teacher_has_courses_model');
            $data = $this->Teacher_has_courses_model->find_course($course_id);
            if (!$data) {
                redirect('/admin/teacher_has_courses', 'refresh');
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
                if (isset($_POST['delete'])) {
                    $data = $this->Teacher_has_courses_model->delete_course($course_id, $role_id, $teacher_id);
                    if ($data) {
                        redirect('/admin/teacher_has_courses/'.$course_id, 'refresh');
                    }
                } else {
                    $this->load->helper('form');
                    $this->load->library('form_validation');
                    $this->form_validation->set_rules('teacher_id', 'teacher_id', 'trim|required');
                    $this->form_validation->set_rules('role_id', 'role_id', 'trim|required');
                    if ($this->form_validation->run()) {
                        $check_before_save = $this->Teacher_has_courses_model->check_before_save($course_id, $_POST['role_id'], $_POST['teacher_id']);
                        if (!$check_before_save) {
                            $old_data = array('role_id' => $role_id, 'teacher_id' => $teacher_id);
                            $data = $this->Teacher_has_courses_model->save_course($course_id, $_POST, $old_data);
                            if ($data) {
                                redirect('/admin/teacher_has_courses/'.$course_id, 'refresh');
                            }
                        } else {
                            $view['message'] = 'มีอาจารย์ และบทบาทนี้ในหลักสูตรแล้ว';
                        }
                    }
                }
            }

            // Teacher lists
            $this->load->model('Teachers_model');
            $teachers = $this->Teachers_model->find_all();
            $teacherLists = array();
            if ($teachers) {
                foreach ($teachers as $value) {
                    $teacherLists[$value->teacher_id] = $value->first_name.' '.$value->last_name;
                }
            }

            // Role lists
            $this->load->model('Roles_model');
            $roles = $this->Roles_model->find_all();
            $roleLists = array();
            if ($roles) {
                foreach ($roles as $value) {
                    $roleLists[$value->role_id] = $value->role_name;
                }
            }

            $view['course_id'] = $course_id;
            $view['role_id'] = $role_id;
            $view['teacher_id'] = $teacher_id;
            $view['teacherLists'] = $teacherLists;
            $view['roleLists'] = $roleLists;

            $this->breadcrumbs->push('หลักสูตร และบทบาท', '/admin/teacher_has_courses');
            if ($course_id) {
                $this->breadcrumbs->push('หลักสูตร และบทบาท', '/admin/teacher_has_courses/'.$course_id);
            }
            $this->breadcrumbs->push('แก้ไขหลักสูตร และบทบาท', '/admin/teacher_has_course_edit');
            $view['breadcrumbs'] = $this->breadcrumbs->show();
            $this->load->view('admin/teacher_has_course_edit', $view);
            $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
        } else {
            redirect('/admin/teacher_has_courses', 'refresh');
        }
    }

    /* Roles page */
    public function roles()
    {
        $this->load->model('Roles_model');

        $data = $this->Roles_model->find_all();
        $view['data'] = $data;

        $this->breadcrumbs->push('กำหนดเกณฑ์', '/admin/roles');
        $view['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('admin/roles', $view);
        $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
    }

    /* Edit roles page */
    public function role_edit()
    {
        $role_id = $this->uri->segment(3);
        $this->load->model('Roles_model');
        $this->load->model('Role_has_rules_model');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['rules']) && is_array($_POST['rules'])) {
                $delete = $this->Role_has_rules_model->delete($role_id);
                foreach ($_POST['rules'] as $value) {
                    $data = $this->Role_has_rules_model->save($value, $role_id);
                }
                redirect('/admin/roles', 'refresh');
            } else {
                $view['message'] = 'กรุณาเลือกเกณฑ์อย่างน้อย 1 เกณฑ์';
            }
        }

        $data = $this->Roles_model->find($role_id);
        if ($data) {
            $this->load->model('Rules_model');
            $rules = $this->Rules_model->find_all();
            $ruleSelect = $this->Role_has_rules_model->find($role_id);
            $ruleSelected = array();
            if ($ruleSelect) {
                foreach ($ruleSelect as $value) {
                    $ruleSelected[$value->rule_id] = true;
                }
            }
            $view['data'] = $data;
            $view['rules'] = $rules;
            $view['ruleSelected'] = $ruleSelected;

            $this->breadcrumbs->push('กำหนดเกณฑ์', '/admin/roles');
            $this->breadcrumbs->push('แก้ไขเกณฑ์', '/admin/role_edit/');
            $view['breadcrumbs'] = $this->breadcrumbs->show();
            $this->load->view('admin/role_edit', $view);
            $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
        } else {
            redirect('/admin/roles', 'refresh');
        }
    }

    /* Schedules page */
    public function schedules()
    {
        $this->load->model('Schedules_model');

        $data = $this->Schedules_model->find_all();
        $view['data'] = $data;
        $view['controller'] = $this;

        $this->breadcrumbs->push('การตรวจสอบ', '/admin/schedules');
        $view['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('admin/schedules', $view);
        $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
    }

    /* Add schedules page */
    public function schedule_add()
    {
        $this->load->model('Schedules_model');
        $this->load->model('Courses_model');
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
            if (!$this->input->post('execute_day')) {
                $view['message'] = 'กรุณาเลือกวันที่ต้องการตรวจสอบ';
            }
            if (!$this->input->post('execute_month')) {
                $view['message'] = 'กรุณาเลือกเดือนที่ต้องการตรวจสอบ';
            }
            if ($this->input->post('course_year') && $this->input->post('course_id') && $this->input->post('execute_day') && $this->input->post('execute_month') && $this->input->post('execute_time')) {
                $this->schedule_save();
            }
        }
        // Course Year lists
        $courseYearLists = array();
        $listYear = $this->Courses_model->getYear();
        foreach ($listYear as $value) {
            $courseYearLists[$value->course_year] = $value->course_year + 543;
        }
        $view['courseYearLists'] = $courseYearLists;

        $executeDays = array();
        $executeDays['1'] = 'อาทิตย์';
        $executeDays['2'] = 'จันทร์';
        $executeDays['3'] = 'อังคาร';
        $executeDays['4'] = 'พุธ';
        $executeDays['5'] = 'พฤหัสบดี';
        $executeDays['6'] = 'ศุกร์';
        $executeDays['7'] = 'เสาร์';
        $view['executeDays'] = $executeDays;

        $executeMonths = array();
        $executeMonths['1'] = 'มกราคม';
        $executeMonths['2'] = 'กุมภาพันธ์';
        $executeMonths['3'] = 'มีนาคม';
        $executeMonths['4'] = 'เมษายน';
        $executeMonths['5'] = 'พฤษภาคม';
        $executeMonths['6'] = 'มิถุนายน';
        $executeMonths['7'] = 'กรกฎาคม';
        $executeMonths['8'] = 'สิงหาคม';
        $executeMonths['9'] = 'กันยายน';
        $executeMonths['10'] = 'ตุลาคม';
        $executeMonths['11'] = 'พฤศจิกายน';
        $executeMonths['12'] = 'ธันวาคม';
        $view['executeMonths'] = $executeMonths;

        $this->breadcrumbs->push('การตรวจสอบ', '/admin/schedules');
        $this->breadcrumbs->push('เพิ่มการตรวจสอบ', '/admin/schedule_add/');
        $view['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('admin/schedule_add', $view);
        $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
    }

    /* Edit schedules page */
    public function schedule_edit()
    {
        $this->load->model('Schedules_model');
        $this->load->model('Courses_model');
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
            if (!$this->input->post('execute_day')) {
                $view['message'] = 'กรุณาเลือกวันที่ต้องการตรวจสอบ';
            }
            if (!$this->input->post('execute_month')) {
                $view['message'] = 'กรุณาเลือกเดือนที่ต้องการตรวจสอบ';
            }
            if ($this->input->post('course_year') && $this->input->post('course_id') && $this->input->post('execute_day') && $this->input->post('execute_month') && $this->input->post('execute_time')) {
                $this->schedule_save();
            }
        }
        $data = $this->Schedules_model->find($this->uri->segment(3));
        if ($data) {
            $view['data'] = $data;

            // Course Year lists
            $courseYearLists = array();
            $listYear = $this->Courses_model->getYear();
            foreach ($listYear as $value) {
                $courseYearLists[$value->course_year] = $value->course_year + 543;
            }
            $view['courseYearLists'] = $courseYearLists;

            // Course lists
            $courseLists = array();
            $listYear = $this->Courses_model->getCourseYear($data->course_year);
            foreach ($listYear as $value) {
                $courseLists[$value->course_id] = $value->course_name;
            }
            $view['courseLists'] = $courseLists;

            // Select days
            $daySelects = explode(',', $data->execute_day);
            $daySelects = array_flip($daySelects);
            $view['daySelects'] = $daySelects;

            // Select months
            $monthSelects = explode(',', $data->execute_month);
            $monthSelects = array_flip($monthSelects);
            $view['monthSelects'] = $monthSelects;

            $executeDays = array();
            $executeDays['1'] = 'อาทิตย์';
            $executeDays['2'] = 'จันทร์';
            $executeDays['3'] = 'อังคาร';
            $executeDays['4'] = 'พุธ';
            $executeDays['5'] = 'พฤหัสบดี';
            $executeDays['6'] = 'ศุกร์';
            $executeDays['7'] = 'เสาร์';
            $view['executeDays'] = $executeDays;

            $executeMonths = array();
            $executeMonths['1'] = 'มกราคม';
            $executeMonths['2'] = 'กุมภาพันธ์';
            $executeMonths['3'] = 'มีนาคม';
            $executeMonths['4'] = 'เมษายน';
            $executeMonths['5'] = 'พฤษภาคม';
            $executeMonths['6'] = 'มิถุนายน';
            $executeMonths['7'] = 'กรกฎาคม';
            $executeMonths['8'] = 'สิงหาคม';
            $executeMonths['9'] = 'กันยายน';
            $executeMonths['10'] = 'ตุลาคม';
            $executeMonths['11'] = 'พฤศจิกายน';
            $executeMonths['12'] = 'ธันวาคม';
            $view['executeMonths'] = $executeMonths;

            $this->breadcrumbs->push('การตรวจสอบ', '/admin/schedules');
            $this->breadcrumbs->push('แก้ไขการตรวจสอบ', '/admin/schedule_edit/');
            $view['breadcrumbs'] = $this->breadcrumbs->show();
            $this->load->view('admin/schedule_edit', $view);
            $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
        } else {
            redirect('/admin/schedules', 'refresh');
        }
    }

    /* Insert/Update schedule */
    private function schedule_save($type = 'insert')
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        if (isset($_POST['execute_day']) && $_POST['execute_day']) {
            $_POST['execute_day'] = implode(',', $_POST['execute_day']);
        }
        if (isset($_POST['execute_month']) && $_POST['execute_month']) {
            $_POST['execute_month'] = implode(',', $_POST['execute_month']);
        }
        $this->form_validation->set_rules('course_year', 'course_year', 'trim|required');
        $this->form_validation->set_rules('course_id', 'course_id', 'trim|required');
        $this->form_validation->set_rules('execute_day', 'execute_day', 'trim|required');
        $this->form_validation->set_rules('execute_month', 'execute_month', 'trim|required');
        $this->form_validation->set_rules('execute_time', 'execute_time', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim');

        if ($this->form_validation->run()) {
            if ($type = 'update') {
                $id = $this->uri->segment(3);
                $data = $this->Schedules_model->save($_POST, $id);
            } else {
                $data = $this->Schedules_model->save($_POST);
            }

            if ($data) {
                redirect('/admin/schedules', 'refresh');
            }
        }
    }

    /* Delete schedule */
    public function schedule_delete()
    {
        $this->load->model('Schedules_model');
        $data = $this->Schedules_model->delete($this->uri->segment(3));
        redirect('/admin/schedules', 'refresh');
    }

    /* Sars page */
    public function sars()
    {
        $this->load->model('Sars_model');
        $course_id = $this->uri->segment(3);
        if ($course_id) {
            $data = $this->Sars_model->find_course($course_id, false);
        } else {
            $data = $this->Sars_model->find_all_course();
        }

        if ($data) {
            $view['data'] = $data;
            $view['course_id'] = $course_id;
            $view['controller'] = $this;

            $this->breadcrumbs->push('ผลประเมินคุณภาพ', '/admin/sars');
            if ($course_id) {
                $view['title'] = 'หลักสูตร'.$data[0]->course_name.' ประจำปีการศึกษาที่ '.($data[0]->course_year + 543);
                $this->breadcrumbs->push($view['title'], '/admin/sars/'.$course_id);
            }
            $view['breadcrumbs'] = $this->breadcrumbs->show();
            $this->load->view('admin/sars', $view);
            $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
        } else {
            if ($course_id) {
                redirect('/admin/sars', 'refresh');
            } else {
                redirect('/admin/', 'refresh');
            }
        }
    }

    /* Sar page */
    public function sar()
    {
        $this->load->model('Sars_model');
        $course_id = $this->uri->segment(3);
        $sar_id = $this->uri->segment(4);
        if ($sar_id) {
            $data = $this->Sars_model->get_sar_course($course_id, $sar_id);
        } else {
            $data = $this->Sars_model->get_sar_course($course_id);
        }
        if ($data) {
            $view['data'] = $data;
            $view['controller'] = $this;

            $year = substr($data->course_year + 543, -2);
            $view['code'] = $data->course_code.$year.'-'.$data->sar_id;

            $this->breadcrumbs->push('ผลประเมินคุณภาพ', '/admin/sars');
            $this->breadcrumbs->push('หลักสูตรที่เปิดสอน'.$data->course_name.' ประจำปีการศึกษาที่ '.($data->course_year + 543), '/admin/sars/'.$course_id);
            if ($sar_id) {
                $this->breadcrumbs->push($view['code'], '/admin/sar/'.$course_id.'/'.$sar_id);
            } else {
                $this->breadcrumbs->push($view['code'], '/admin/sar/'.$course_id);
            }
            $view['breadcrumbs'] = $this->breadcrumbs->show();
            $this->load->view('admin/sar', $view);
            $this->output->set_common_meta('Quality Assurance - ภาควิชาวิทยาการคอมพิวเตอร์ คณะวิทยาศาสตร์ มหาวิทยาลัยเชียงใหม่', '', '');
        } else {
            if ($course_id) {
                redirect('/admin/sars/'.$course_id, 'refresh');
            } else {
                redirect('/admin/sars', 'refresh');
            }
        }
    }

    /* Sars detail */
    public function _SarDetails($pass, $fail)
    {
        $this->load->model('Teachers_model');
        $this->load->model('Rules_model');
        $pass = explode(',', $pass);
        $data = array();
        $rules = $this->Rules_model->find_all();
        $ruleLists = array();
        if ($rules) {
            foreach ($rules as $key => $value) {
                $ruleLists[$value->rule_id] = $value->rule_name;
            }
        }
        if ($pass) {
            foreach ($pass as $value) {
                if ($value) {
                    $value = explode(':', $value);
                    $teacher_id = $value[0];
                    $rule_id = $value[1];
                    if (!isset($data[$teacher_id])) {
                        $teacher = $this->Teachers_model->find($teacher_id);
                        if ($teacher) {
                            $data[$teacher_id]['teacher_id'] = $teacher->teacher_id;
                            $data[$teacher_id]['name'] = $teacher->first_name.' '.$teacher->last_name;
                            $data[$teacher_id]['pass'] = array();
                            $data[$teacher_id]['fail'] = array();
                        }
                    }
                    if (isset($ruleLists[$rule_id])) {
                        $data[$teacher_id]['pass']['rule_id'][$rule_id]['rule_id'] = $rule_id;
                        $data[$teacher_id]['pass']['rule_id'][$rule_id]['rule_name'] = $ruleLists[$rule_id];
                    }
                }
            }
        }
        $fail = explode(',', $fail);
        if ($fail) {
            foreach ($fail as $value) {
                if ($value) {
                    $value = explode(':', $value);
                    $teacher_id = $value[0];
                    $rule_id = $value[1];
                    if (!isset($data[$teacher_id])) {
                        $teacher = $this->Teachers_model->find($teacher_id);
                        if ($teacher) {
                            $data[$teacher_id]['teacher_id'] = $teacher->teacher_id;
                            $data[$teacher_id]['name'] = $teacher->first_name.' '.$teacher->last_name;
                            $data[$teacher_id]['pass'] = array();
                        }
                    }
                    if (isset($ruleLists[$rule_id])) {
                        $data[$teacher_id]['fail']['rule_id'][$rule_id]['rule_id'] = $rule_id;
                        $data[$teacher_id]['fail']['rule_id'][$rule_id]['rule_name'] = $ruleLists[$rule_id];
                    }
                }
            }
        }

        return $data;
    }

    /* Date Thai */
    public function _DateThai($date, $short = false)
    {
        $arrTime = explode(' ', $date);
        $arr = explode('-', $arrTime[0]);
        $year = $arr[0] + 543;
        $month = intval($arr[1]);
        $day = intval($arr[2]);
        if ($short) {
            $monthList = array('1' => 'มกราคม', '2' => 'กุมภาพันธ์', '3' => 'มีนาคม', '4' => 'เมษายน', '5' => 'พฤษภาคม', '6' => 'มิถุนายน',
                              '7' => 'กรกฎาคม', '8' => 'สิงหาคม', '9' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม', );
        } else {
            $monthList = array('1' => 'ม.ค.', '2' => 'ก.พ.', '3' => 'มี.ค.', '4' => 'เม.ษ.', '5' => 'พ.ค.', '6' => 'มิ.ย.',
                              '7' => 'ก.ค.', '8' => 'ส.ค.', '9' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.', );
        }
        $month = isset($monthList[$month]) ? $monthList[$month] : '';
        if ($month) {
            $date = $day.' '.$month.' '.$year;
            if (isset($arrTime[1])) {
                $time = explode(':', $arrTime[1]);
                $date .= ' '.$time[0].':'.$time[1];
            }
        }

        return $date;
    }

    /* Generate Days */
    public function _GenDays($days)
    {
        $dayList = explode(',', $days);
        $data = array();
        $list = array('1' => 'อา', '2' => 'จ', '3' => 'อ', '4' => 'พ', '5' => 'พฤ', '6' => 'ศ', '7' => 'ส');
        foreach ($dayList as $value) {
            if (isset($list[$value])) {
                $data[] = $list[$value];
            }
        }
        if (count($data) == 7) {
            $result = 'ทุกวัน';
        } else {
            $result = implode(', ', $data);
        }

        return $result;
    }

    /* Generate Month */
    public function _GenMonth($months)
    {
        $monthList = explode(',', $months);
        $data = array();
        $list = array('1' => 'ม.ค.', '2' => 'ก.พ.', '3' => 'มี.ค.', '4' => 'เม.ษ.', '5' => 'พ.ค.', '6' => 'มิ.ย.', '7' => 'ก.ค.', '8' => 'ส.ค.', '9' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.');
        foreach ($monthList as $value) {
            if (isset($list[$value])) {
                $data[] = $list[$value];
            }
        }
        if (count($data) == 12) {
            $result = 'ทุกเดือน';
        } else {
            $result = implode(', ', $data);
        }

        return $result;
    }
}
