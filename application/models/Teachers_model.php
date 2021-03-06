<?php

class Teachers_model extends CI_Model
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
                                      teacher.tid AS teacher_id,
                                      teacher.tname AS first_name,
                                      teacher.tsname AS last_name
                                    FROM
                                      seminar.teacher');

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
                                    teacher.tid AS teacher_id,
                                    teacher.tname AS first_name,
                                    teacher.tsname AS last_name
                                  FROM
                                    seminar.teacher
                                  WHERE
                                    teacher.tid = "'.$id.'"');

        if ($data->result()) {
            return $data->first_row();
        } else {
            return false;
        }
    }
}
