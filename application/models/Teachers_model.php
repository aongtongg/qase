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
                                    members.members_id AS teacher_id,
                                    members.members_fname AS first_name,
                                    members.members_sname AS last_name
                                  FROM
                                    seminar.members');

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
                                    members.members_id AS teacher_id,
                                    members.members_fname AS first_name,
                                    members.members_sname AS last_name
                                  FROM
                                    seminar.members
                                  WHERE
                                    members.tid = "'.$id.'"');

        if ($data->result()) {
            return $data->first_row();
        } else {
            return false;
        }
    }
}
