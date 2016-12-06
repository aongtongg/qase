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
}
