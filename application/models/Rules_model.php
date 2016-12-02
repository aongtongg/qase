<?php

class Rules_model extends CI_Model
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
                                    rules.rule_id,
                                    rules.rule_name
                                  FROM
                                    rules');

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
                                    rules.rule_id,
                                    rules.rule_name
                                  FROM
                                    rules
                                  WHERE
                                    rules.rule_id = "'.$id.'"');

        if ($data->result()) {
            return $data->first_row();
        } else {
            return false;
        }
    }
}
