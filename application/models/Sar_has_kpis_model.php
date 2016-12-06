<?php

class Sar_has_kpis_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* Select data by ID */
    public function find($id, $array = false)
    {
        $data = $this->db->query('SELECT
                                    sar_has_kpis.sar_id,
                                    sar_has_kpis.kpi_id,
                                    sar_has_kpis.kpi_pass,
                                    sar_has_kpis.kpi_fail
                                  FROM
                                    sar_has_kpis
                                  WHERE
                                    sar_id = "'.$id.'"');

        if ($data->result()) {
            $data = $data->result();
            if ($array) {
                $result = array();
                foreach ($data as $value) {
                    $result[$value->kpi_id]['pass'] = $value->kpi_pass;
                    $result[$value->kpi_id]['fail'] = $value->kpi_fail;
                }
            } else {
                $result = $data;
            }

            return $result;
        } else {
            return false;
        }
    }

    /* Update KPI PASS */
    public function update_pass_rule($sar_id, $kpi_id, $teacher_id, $rule_id)
    {
        $result = false;
        $data = $this->db->query('SELECT
                                    sar_has_kpis.sar_id,
                                    sar_has_kpis.kpi_id
                                  FROM
                                    sar_has_kpis
                                  WHERE
                                    sar_id = "'.$sar_id.'" AND
                                    kpi_id = "'.$kpi_id.'"');
        $kpi_pass = $teacher_id.':'.$rule_id;
        if ($data->result()) {
            $data = $this->db->query('UPDATE sar_has_kpis
                                      SET
                                       kpi_pass = CONCAT(kpi_pass,"'.$kpi_pass.',")
                                      WHERE
                                        sar_id = "'.$sar_id.'" AND
                                        kpi_id = "'.$kpi_id.'"');
            if ($data) {
                $result = true;
            }
        } else {
            $data = $this->db->query('INSERT INTO sar_has_kpis (
                                        sar_id,
                                        kpi_id,
                                        kpi_pass)
                                      VALUES (
                                        "'.$sar_id.'",
                                        "'.$kpi_id.'",
                                        "'.$kpi_pass.',")');
            if ($data) {
                $result = true;
            }
        }

        return $result;
    }

    /* Update KPI FAIL */
    public function update_fail_rule($sar_id, $kpi_id, $teacher_id, $rule_id)
    {
        $result = false;
        $data = $this->db->query('SELECT
                                    sar_has_kpis.sar_id,
                                    sar_has_kpis.kpi_id
                                  FROM
                                    sar_has_kpis
                                  WHERE
                                    sar_id = "'.$sar_id.'" AND
                                    kpi_id = "'.$kpi_id.'"');
        $kpi_fail = $teacher_id.':'.$rule_id;
        if ($data->result()) {
            $data = $this->db->query('UPDATE sar_has_kpis
                                      SET
                                       kpi_fail = CONCAT(kpi_fail,"'.$kpi_fail.',")
                                      WHERE
                                        sar_id = "'.$sar_id.'" AND
                                        kpi_id = "'.$kpi_id.'"');
            if ($data) {
                $result = true;
            }
        } else {
            $data = $this->db->query('INSERT INTO sar_has_kpis (
                                        sar_id,
                                        kpi_id,
                                        kpi_fail)
                                      VALUES (
                                        "'.$sar_id.'",
                                        "'.$kpi_id.'",
                                        "'.$kpi_fail.',")');
            if ($data) {
                $result = true;
            }
        }

        return $result;
    }
}
