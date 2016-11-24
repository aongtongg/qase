<?php

class Users_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getUser($username, $password)
    {
        $data = $this->db->query('SELECT
                                      users.user_id,
                                      users.username
                                    FROM
                                      users
                                    WHERE
                                      users.username = "'.$username.'" AND
                                      users.password = "'.sha1('QASE'.$password).'"');

        if ($data->result()) {
            return $data->first_row();
        } else {
            return false;
        }
    }
}
