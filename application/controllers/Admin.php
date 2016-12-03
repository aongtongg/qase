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
        if (!isset($_SESSION['members_class'])) {
            //redirect(root_url(), 'refresh');
        }
        $method = $this->uri->segment(2);
        if ($method != '' && $method != 'logout') {
            //if (!isset($this->session->userdata['members_class'])) {
            if (!isset($_SESSION['members_class'])) {
                redirect(root_url(), 'refresh');
            }
        }
        $this->output->set_template('qase');
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('ระบบหลังบ้าน', '/admin/');

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
        $this->output->set_common_meta('QASE', '', '');
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

        $this->breadcrumbs->push('หลักสูตร', '/admin/courses');
        $view['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('admin/courses', $view);
        $this->output->set_common_meta('QASE', '', '');
    }

    /* Add courses page */
    public function course_add()
    {
        $this->load->model('Courses_model');
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
            if ($_POST['course_name'] != '' && $_POST['course_start_date'] != '' && $_POST['course_estimate_date'] != '') {
                $this->course_save();
            }
        }

        $this->breadcrumbs->push('หลักสูตร', '/admin/courses');
        $this->breadcrumbs->push('เพิ่มหลักสูตร', '/admin/course_add/');
        $view['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('admin/course_add', $view);
        $this->output->set_common_meta('QASE', '', '');
    }

    /* Edit courses page */
    public function course_edit()
    {
        $this->load->model('Courses_model');
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
            if ($_POST['course_name'] != '' && $_POST['course_start_date'] != '' && $_POST['course_estimate_date'] != '') {
                $this->course_save('update');
            }
        }
        $data = $this->Courses_model->find($this->uri->segment(3));
        if ($data) {
            $view['data'] = $data;

            $this->breadcrumbs->push('หลักสูตร', '/admin/courses');
            $this->breadcrumbs->push('แก้ไขหลักสูตร', '/admin/course_add/');
            $view['breadcrumbs'] = $this->breadcrumbs->show();
            $this->load->view('admin/course_edit', $view);
            $this->output->set_common_meta('QASE', '', '');
        } else {
            redirect('/admin/courses', 'refresh');
        }
    }

    /* Insert/Update course */
    private function course_save($type = 'insert')
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('course_name', 'course_name', 'trim|required');
        $this->form_validation->set_rules('course_start_date', 'course_start_date', 'trim|required');
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
        $course_year = $this->uri->segment(3);
        $course_id = $this->uri->segment(4);

        $this->load->model('Teacher_has_courses_model');

        if ($course_year && $course_id) {
            $data = $this->Teacher_has_courses_model->find_course_year($course_year, $course_id);
            if ($data) {
                $view['course_year'] = $data;
            } else {
                redirect('/admin/teacher_has_courses', 'refresh');
            }
        } else {
            $data = $this->Teacher_has_courses_model->find_all_course_year();
            $view['data'] = $data;
        }

        $this->breadcrumbs->push('ภาพรวมหลักสูตร', '/admin/teacher_has_courses');
        if ($course_year && $course_id) {
            $this->breadcrumbs->push('หลักสูตร และบทบาท', '/admin/teacher_has_courses/'.$course_year.'/'.$course_id);
        }
        $view['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('admin/teacher_has_courses', $view);
        $this->output->set_common_meta('QASE', '', '');
    }

    /* Add teacher has courses page */
    public function teacher_has_course_add()
    {
        $course_year = $this->uri->segment(3);
        $course_id = $this->uri->segment(4);

        $this->load->model('Teacher_has_courses_model');

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

        if ($course_year && $course_id) {
            $data = $this->Teacher_has_courses_model->find_course_year($course_year, $course_id);
            if (!$data) {
                redirect('/admin/teacher_has_courses', 'refresh');
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
                $this->load->helper('form');
                $this->load->library('form_validation');
                $this->form_validation->set_rules('teacher_id', 'teacher_id', 'trim|required');
                $this->form_validation->set_rules('role_id', 'role_id', 'trim|required');
                if ($this->form_validation->run()) {
                    $data = $this->Teacher_has_courses_model->save_course_year($course_year, $course_id, $_POST);

                    if ($data) {
                        redirect('/admin/teacher_has_courses/'.$course_year.'/'.$course_id, 'refresh');
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
                    $data = $this->Teacher_has_courses_model->save_course_year($_POST['course_year'], $_POST['course_id'], $_POST);

                    if ($data) {
                        redirect('/admin/teacher_has_courses/'.$course_year.'/'.$course_id, 'refresh');
                    }
                }
            }
            // Course Year lists
            $courseYearLists = array();
            for ($i = date('Y') + 3; $i > 2011; --$i) {
                $courseYearLists[$i] = $i + 543;
            }
            $view['courseYearLists'] = $courseYearLists;

            // Course lists
            $this->load->model('Courses_model');
            $courses = $this->Courses_model->find_all();
            $courseLists = array();
            if ($courses) {
                foreach ($courses as $value) {
                    $courseLists[$value->course_id] = $value->course_name;
                }
            }
            $view['courseLists'] = $courseLists;
        }
        $view['course_year'] = $course_year;
        $view['course_id'] = $course_id;
        $view['teacherLists'] = $teacherLists;
        $view['roleLists'] = $roleLists;

        $this->breadcrumbs->push('ภาพรวมหลักสูตร และบทบาท', '/admin/teacher_has_courses');
        if ($course_year && $course_id) {
            $this->breadcrumbs->push('หลักสูตร และบทบาท', '/admin/teacher_has_courses/'.$course_year.'/'.$course_id);
        }
        $this->breadcrumbs->push('เพิ่มหลักสูตร และบทบาท', '/admin/teacher_has_course_add');
        $view['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('admin/teacher_has_course_add', $view);
        $this->output->set_common_meta('QASE', '', '');
    }

    /* Edit teacher has courses page */
    public function teacher_has_course_edit()
    {
        $course_year = $this->uri->segment(3);
        $course_id = $this->uri->segment(4);
        $role_id = $this->uri->segment(5);
        $teacher_id = $this->uri->segment(6);

        if ($course_year && $course_id && $role_id && $teacher_id) {
            $this->load->model('Teacher_has_courses_model');
            $data = $this->Teacher_has_courses_model->find_course_year($course_year, $course_id);
            if (!$data) {
                redirect('/admin/teacher_has_courses', 'refresh');
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
                if (isset($_POST['delete'])) {
                    $data = $this->Teacher_has_courses_model->delete_course_year($course_year, $course_id, $role_id, $teacher_id);
                } else {
                    $this->load->helper('form');
                    $this->load->library('form_validation');
                    $this->form_validation->set_rules('teacher_id', 'teacher_id', 'trim|required');
                    $this->form_validation->set_rules('role_id', 'role_id', 'trim|required');
                    if ($this->form_validation->run()) {
                        $old_data = array('role_id' => $role_id, 'teacher_id' => $teacher_id);
                        $data = $this->Teacher_has_courses_model->save_course_year($course_year, $course_id, $_POST, $old_data);
                    }
                }

                if ($data) {
                    redirect('/admin/teacher_has_courses/'.$course_year.'/'.$course_id, 'refresh');
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

            $view['course_year'] = $course_year;
            $view['course_id'] = $course_id;
            $view['role_id'] = $role_id;
            $view['teacher_id'] = $teacher_id;
            $view['teacherLists'] = $teacherLists;
            $view['roleLists'] = $roleLists;

            $this->breadcrumbs->push('ภาพรวมหลักสูตร', '/admin/teacher_has_courses');
            if ($course_year && $course_id) {
                $this->breadcrumbs->push('หลักสูตร และบทบาท', '/admin/teacher_has_courses/'.$course_year.'/'.$course_id);
            }
            $this->breadcrumbs->push('แก้ไขหลักสูตร และบทบาท', '/admin/teacher_has_course_edit');
            $view['breadcrumbs'] = $this->breadcrumbs->show();
            $this->load->view('admin/teacher_has_course_edit', $view);
            $this->output->set_common_meta('QASE', '', '');
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

        $this->breadcrumbs->push('บทบาท', '/admin/roles');
        $view['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('admin/roles', $view);
        $this->output->set_common_meta('QASE', '', '');
    }

    /* Edit roles page */
    public function role_edit()
    {
        $role_id = $this->uri->segment(3);
        $this->load->model('Roles_model');
        $this->load->model('Role_has_rules_model');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {
            if (isset($_POST['rules']) && is_array($_POST['rules'])) {
                $delete = $this->Role_has_rules_model->delete($role_id);
                foreach ($_POST['rules'] as $value) {
                    $data = $this->Role_has_rules_model->save($value, $role_id);
                }
                redirect('/admin/roles', 'refresh');
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

            $this->breadcrumbs->push('บทบาท', '/admin/roles');
            $this->breadcrumbs->push('แก้ไขกฎ', '/admin/role_edit/');
            $view['breadcrumbs'] = $this->breadcrumbs->show();
            $this->load->view('admin/role_edit', $view);
            $this->output->set_common_meta('QASE', '', '');
        } else {
            redirect('/admin/roles', 'refresh');
        }
    }
}
