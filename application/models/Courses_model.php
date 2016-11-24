<?php

class Courses_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* Select all data */
    public function find_all()
    {
        $data = $this->db->query('SELECT
                                courses.course_id,
                                courses.course_name,
                                courses.course_start_date,
                                courses.course_estimate_date
                              FROM
                                courses');

        if ($data->result()) {
            return $data->result();
        } else {
            return false;
        }
    }

    /* Select data by ID */
    public function find($id)
    {
        $data = $this->db->query('SELECT
                                    courses.course_id,
                                    courses.course_name,
                                    courses.course_start_date,
                                    courses.course_estimate_date
                                  FROM
                                    courses
                                  WHERE
                                    courses.course_id = "'.$id.'"');

        if ($data->result()) {
            return $data->first_row();
        } else {
            return false;
        }
    }


    /* Insert/Update data */
    public function save($data, $id = null)
    {
        $result = false;

        if ($id) {
            $data = $this->db->query('UPDATE courses
                                  SET
                                   course_name = "'.$data['course_name'].'",
                                   course_start_date = "'.$data['course_start_date'].'",
                                   course_estimate_date = "'.$data['course_estimate_date'].'"
                                  WHERE
                                    course_id = "'.$id.'"');
            if ($data) {
                $result = $id;
            }
        } else {
            $data = $this->db->query('INSERT INTO courses (
                                    course_name,
                                    course_start_date,
                                    course_estimate_date)
                                  VALUES (
                                    "'.$data['course_name'].'",
                                    "'.$data['course_start_date'].'",
                                    "'.$data['course_estimate_date'].'"
                                  )');
            if ($data) {
                $result = $this->db->insert_id();
            }
        }

        return $result;
    }
}
