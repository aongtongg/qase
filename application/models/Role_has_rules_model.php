<?php

class Role_has_rules_model extends CI_Model
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
                                    role_has_rules.role_id,
                                    role_has_rules.rule_id
                                  FROM
                                    role_has_rules');

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
                                    role_has_rules.role_id,
                                    role_has_rules.rule_id
                                  FROM
                                    role_has_rules
                                  WHERE
                                    role_has_rules.role_id = "'.$id.'"');

        if ($data->result()) {
            return $data->result();
        } else {
            return false;
        }
    }

    /* Insert/Update data */
    public function save($rule_id, $role_id)
    {
        $result = false;
        $data = $this->db->query('INSERT INTO role_has_rules (
                                    role_id,
                                    rule_id)
                                  VALUES (
                                    "'.$role_id.'",
                                    "'.$rule_id.'"
                                  )');
        if ($data) {
            $result = $this->db->insert_id();
        }

        return $result;
    }

    /* Delete data */
    public function delete($role_id)
    {
        $result = false;
        $delete = $this->db->query('DELETE FROM role_has_rules WHERE role_id = "'.$role_id.'"');
        if ($delete) {
            $result = true;
        }

        return $result;
    }
}
