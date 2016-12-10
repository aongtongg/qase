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
                                courses.course_code,
                                courses.course_year,
                                courses.course_start_date,
                                courses.course_estimate_date,
                                COUNT(teacher_has_courses.course_id) AS teacher_has_courses
                              FROM
                                courses
                              LEFT JOIN teacher_has_courses ON teacher_has_courses.course_id = courses.course_id
                              GROUP BY courses.course_id
                              ORDER BY courses.course_year DESC, courses.course_name');

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
                                    courses.course_code,
                                    courses.course_year,
                                    courses.course_start_date,
                                    courses.course_estimate_date,
                                    COUNT(teacher_has_courses.course_id) AS teacher_has_courses
                                  FROM
                                    courses
                                  LEFT JOIN teacher_has_courses ON teacher_has_courses.course_id = courses.course_id
                                  WHERE
                                    courses.course_id = "'.$id.'"
                                  GROUP BY courses.course_id');

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
                                   course_code = "'.$data['course_code'].'",
                                   course_year = "'.$data['course_year'].'",
                                   course_estimate_date = "'.$data['course_estimate_date'].'"
                                  WHERE
                                    course_id = "'.$id.'"');
            if ($data) {
                $result = $id;
            }
        } else {
            $data = $this->db->query('INSERT INTO courses (
                                    course_name,
                                    course_code,
                                    course_year,
                                    course_estimate_date)
                                  VALUES (
                                    "'.$data['course_name'].'",
                                    "'.$data['course_code'].'",
                                    "'.$data['course_year'].'",
                                    "'.$data['course_estimate_date'].'"
                                  )');
            if ($data) {
                $result = $this->db->insert_id();
            }
        }

        return $result;
    }

    /* Delete data */
    public function delete($id = null)
    {
        $result = false;

        if ($id) {
            $data = $this->find($id);
            if ($data && !$data->teacher_has_courses) {
                $delete = $this->db->query('DELETE FROM courses WHERE course_id = "'.$id.'"');

                if ($data) {
                    $result = true;
                }
            }
        }

        return $result;
    }

    /* Select data course year */
    public function getYear()
    {
        $data = $this->db->query('SELECT
                                    courses.course_year
                                  FROM
                                    courses
                                  GROUP BY courses.course_year
                                  ORDER BY courses.course_year DESC');

        if ($data->result()) {
            return $data->result();
        } else {
            return false;
        }
    }

    /* Select data course year by course_year */
    public function getCourseYear($course_year)
    {
        $data = $this->db->query('SELECT
                                    courses.course_id,
                                    courses.course_name,
                                    courses.course_start_date,
                                    courses.course_estimate_date,
                                    courses.course_year
                                  FROM
                                    courses
                                  WHERE
                                    courses.course_year = "'.$course_year.'"
                                  GROUP BY courses.course_id
                                  ORDER BY courses.course_name');

        if ($data->result()) {
            return $data->result();
        } else {
            return false;
        }
    }
}
