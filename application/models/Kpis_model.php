<?php

class Kpis_model extends CI_Model
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
                                    kpis.kpi_id,
                                    kpis.kpi_name,
                                    kpis.kpi_parent,
                                    kpis.kpi_level,
                                    kpis.kpi_order
                                  FROM
                                    kpis
                                  ORDER BY
                                    kpi_level, kpi_order');

        if ($data->result()) {
            $kpis = $data->result();
            $data = array();
            $tmp_parent = array();
            foreach ($kpis as $value) {
                $tmp_parent[$value->kpi_id] = $value->kpi_parent;
                if ($value->kpi_level == 1) {
                    $data[$value->kpi_id]['title'] = $value->kpi_name;
                } elseif ($value->kpi_level == 2) {
                    $data[$value->kpi_parent]['child'][$value->kpi_id]['title'] = $value->kpi_name;
                } elseif ($value->kpi_level == 3) {
                    $data[$tmp_parent[$value->kpi_parent]]['child'][$value->kpi_parent]['child'][$value->kpi_id]['title'] = $value->kpi_name;
                }
            }

            return $data;
        } else {
            return false;
        }
    }

    /* Check rule 2 */
    public function check_rule_2($researcher_id)
    {
        $research_id = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))).'-5 year'));
        // All in 5 year
        // More than 3 all
        // at least than 1 research
        $data = $this->db->query('SELECT
                                    fact.frid,
                                    SUM(IF(fact.fdtype=1,1,0)) AS count_research,
                                    COUNT(fact.frid) AS count_all
                                  FROM
                                    seminar.fact
                                  JOIN seminar.research ON research.rid = fact.frid
                                  WHERE
                                    fact.frhid = "'.$researcher_id.'" AND
                                    research.ryear >= "'.$research_id.'"
                                  GROUP BY fact.frid');

        if ($data->result()) {
            $data = $data->first_row();
            if ($data->count_research > 1 && $data->count_all > 3) {
                $result = true;
            } else {
                $result = false;
            }

            return $result;
        } else {
            return false;
        }
    }
}
