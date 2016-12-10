<?php

class Members_model extends CI_Model
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
                                    *
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
                                    *
                                  FROM
                                    seminar.members
                                  WHERE
                                    members_id = "'.$id.'"');

        if ($data->result()) {
            return $data->first_row();
        } else {
            return false;
        }
    }

    public function login($username, $password)
    {
        $username = preg_replace('#[^a-zA-Z@0-9._]#', '', $username);
        $salt = '<G:y4_M:hr4Ge)-B';
        $md5 = md5($username.$password.$salt);
        $data = $this->db->query('SELECT
                                      members_id, members_class, members_email, members_status, members_fname AS members_first_name,  members_sname AS members_last_name
                                    FROM
                                      seminar.members
                                    WHERE
                                      members_email = "'.$username.'" AND
                                      members_password = "'.$md5.'"');

        if ($data->result()) {
            return $data->first_row();
        } else {
            return false;
        }
    }
}
