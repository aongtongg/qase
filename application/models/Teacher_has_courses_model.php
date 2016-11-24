<?php

class Teacher_has_courses_model extends CI_Model
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
                                teacher_has_courses.teacher_id,
                                teacher.tname AS first_name,
                                teacher.tsname AS last_name,
                                teacher_has_courses.course_id,
                                courses.course_name,
                                courses.course_start_date,
                                courses.course_estimate_date,
                                teacher_has_courses.role_id,
                                roles.role_name,
                                teacher_has_courses.course_year
                              FROM
                                teacher_has_courses
                              LEFT JOIN
                                roles ON roles.role_id = teacher_has_courses.role_id
                              LEFT JOIN
                                courses ON courses.course_id = teacher_has_courses.course_id
                              LEFT JOIN
                                seminar.teacher ON teacher.tid = teacher_has_courses.teacher_id');

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

    /* Select all data course year */
    public function find_all_course_year()
    {
        $data = $this->db->query('SELECT
                                teacher_has_courses.course_year,
                                teacher_has_courses.course_id,
                                courses.course_name
                              FROM
                                teacher_has_courses
                              LEFT JOIN
                                courses ON courses.course_id = teacher_has_courses.course_id
                              GROUP BY
                                course_year, course_id');

        if ($data->result()) {
            return $data->result();
        } else {
            return false;
        }
    }

    /* Select data course year by course_year and course_id */
    public function find_course_year($course_year, $course_id)
    {
        $data = $this->db->query('SELECT
                                teacher_has_courses.teacher_id,
                                teacher.tname AS first_name,
                                teacher.tsname AS last_name,
                                teacher_has_courses.course_id,
                                courses.course_name,
                                courses.course_start_date,
                                courses.course_estimate_date,
                                teacher_has_courses.role_id,
                                roles.role_name,
                                teacher_has_courses.course_year
                              FROM
                                teacher_has_courses
                              LEFT JOIN
                                roles ON roles.role_id = teacher_has_courses.role_id
                              LEFT JOIN
                                courses ON courses.course_id = teacher_has_courses.course_id
                              LEFT JOIN
                                seminar.teacher ON teacher.tid = teacher_has_courses.teacher_id
                              WHERE
                                teacher_has_courses.course_year = "'.$course_year.'" AND
                                teacher_has_courses.course_id = "'.$course_id.'"');

        if ($data->result()) {
            return $data->result();
        } else {
            return false;
        }
    }
}
