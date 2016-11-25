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
            if (!isset($this->session->userdata['QASE_User'])) {
                redirect('/admin', 'refresh');
            }
        }
        $this->output->set_template('qase_admin');

        $this->load->js('assets/themes/qase/js/validator.min.js');
    }

    /* Index page */
    public function index()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $view['message'] = '';
        if ($this->form_validation->run() == false) {
            if (isset($this->session->userdata['QASE_User'])) {
                $this->load->view('admin/index', $view);
            } else {
                $this->load->view('admin/login', $view);
            }
        } else {
            $this->load->model('Users_model');
            $data = $this->Users_model->getUser($this->input->post('username'), $this->input->post('password'));
            if ($data) {
                $this->session->set_userdata('QASE_User', $data);
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
        $this->session->unset_userdata('QASE_User');
        redirect('/admin', 'refresh');
    }

    /* Courses page */
    public function courses()
    {
        $this->load->model('Courses_model');
        $data = $this->Courses_model->find_all();
        $view['data'] = $data;
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

        $this->load->view('admin/course_add');
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

        $this->load->view('admin/teacher_has_courses', $view);
        $this->output->set_common_meta('QASE', '', '');
    }

    /* Add teacher has courses page */
    public function teacher_has_course_add()
    {
        $course_year = $this->uri->segment(3);
        $course_id = $this->uri->segment(4);

        if ($course_year && $course_id) {
            $this->load->model('Teacher_has_courses_model');
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
            $view['teacherLists'] = $teacherLists;
            $view['roleLists'] = $roleLists;

            $this->load->view('admin/teacher_has_course_add', $view);
            $this->output->set_common_meta('QASE', '', '');
        } else {
            redirect('/admin/teacher_has_courses', 'refresh');
        }
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
                $this->load->helper('form');
                $this->load->library('form_validation');
                $this->form_validation->set_rules('teacher_id', 'teacher_id', 'trim|required');
                $this->form_validation->set_rules('role_id', 'role_id', 'trim|required');
                if ($this->form_validation->run()) {
                    $old_data = array('role_id' => $role_id, 'teacher_id' => $teacher_id);
                    $data = $this->Teacher_has_courses_model->save_course_year($course_year, $course_id, $_POST, $old_data);

                    if ($data) {
                        redirect('/admin/teacher_has_courses/'.$course_year.'/'.$course_id, 'refresh');
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

            $view['course_year'] = $course_year;
            $view['course_id'] = $course_id;
            $view['role_id'] = $role_id;
            $view['teacher_id'] = $teacher_id;
            $view['teacherLists'] = $teacherLists;
            $view['roleLists'] = $roleLists;

            $this->load->view('admin/teacher_has_course_edit', $view);
            $this->output->set_common_meta('QASE', '', '');
        } else {
            redirect('/admin/teacher_has_courses', 'refresh');
        }
    }
}
