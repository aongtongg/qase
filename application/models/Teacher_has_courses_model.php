<?php

class Teacher_has_courses_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* Select all data course */
    public function find_all_course()
    {
        $data = $this->db->query('SELECT
                                    courses.course_year,
                                    teacher_has_courses.course_id,
                                    courses.course_name,
                                    COUNT(teacher_has_courses.teacher_id) AS teacher_no
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

    /* Select data course year by course_id */
    public function find_course($course_id)
    {
        $data = $this->db->query('SELECT
                                    teacher_has_courses.teacher_id,
                                    members.members_fname AS first_name,
                                    members.members_sname AS last_name,
                                    members.members_relation AS researcher_id,
                                    teacher_has_courses.course_id,
                                    courses.course_name,
                                    courses.course_start_date,
                                    courses.course_estimate_date,
                                    teacher_has_courses.role_id,
                                    roles.role_name,
                                    courses.course_year
                                  FROM
                                    teacher_has_courses
                                  LEFT JOIN
                                    roles ON roles.role_id = teacher_has_courses.role_id
                                  LEFT JOIN
                                    courses ON courses.course_id = teacher_has_courses.course_id
                                  LEFT JOIN
                                    seminar.members ON members.members_id = teacher_has_courses.teacher_id
                                  WHERE
                                    teacher_has_courses.course_id = "'.$course_id.'"
                                  ORDER BY teacher_has_courses.role_id, members.members_fname');

        if ($data->result()) {
            return $data->result();
        } else {
            return false;
        }
    }

    /* Check data course */
    public function check_before_save($course_id, $role_id, $teacher_id)
    {
        $data = $this->db->query('SELECT
                                    teacher_has_courses.course_id,
                                    teacher_has_courses.role_id,
                                    teacher_has_courses.teacher_id
                                  FROM
                                    teacher_has_courses
                                  WHERE
                                    teacher_has_courses.course_id = "'.$course_id.'" AND
                                    teacher_has_courses.role_id = "'.$role_id.'" AND
                                    teacher_has_courses.teacher_id = "'.$teacher_id.'"');
        if ($data->result()) {
            return $data->result();
        } else {
            return false;
        }
    }

    /* Insert/Update data course */
    public function save_course($course_id, $data, $old_data = null)
    {
        $result = false;

        if ($course_id && $data) {
            if ($old_data) {
                $delete = $this->db->query('DELETE FROM teacher_has_courses WHERE course_id = "'.$course_id.'" AND role_id = "'.$old_data['role_id'].'" AND teacher_id = "'.$old_data['teacher_id'].'"');
            } else {
                $delete = $this->db->query('DELETE FROM teacher_has_courses WHERE course_id = "'.$course_id.'" AND role_id = "'.$data['role_id'].'" AND teacher_id = "'.$data['teacher_id'].'"');
            }
            $data = $this->db->query('INSERT INTO teacher_has_courses (
                                        course_id,
                                        role_id,
                                        teacher_id)
                                      VALUES (
                                        "'.$course_id.'",
                                        "'.$data['role_id'].'",
                                        "'.$data['teacher_id'].'")');
            if ($data) {
                $result = true;
            }
        }

        return $result;
    }

    /* Insert/Update data course */
    public function delete_course($course_id, $role_id, $teacher_id)
    {
        $result = false;

        if ($course_id && $role_id && $teacher_id) {
            $delete = $this->db->query('DELETE FROM teacher_has_courses WHERE course_id = "'.$course_id.'" AND role_id = "'.$role_id.'" AND teacher_id = "'.$teacher_id.'"');
            if ($delete) {
                $result = true;
            }
        }

        return $result;
    }
}
