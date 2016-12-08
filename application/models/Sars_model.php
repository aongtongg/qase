<?php

class Sars_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* Select data by ID */
    public function find($id)
    {
        $data = $this->db->query('SELECT
                                    sars.sar_id,
                                    sars.course_id,
                                    courses.course_name,
                                    courses.course_code,
                                    courses.course_year,
                                    sars.sar_date
                                  FROM
                                    sars
                                  JOIN courses ON courses.course_id = sars.course_id
                                  WHERE
                                    sars.sar_id = "'.$id.'"');

        if ($data->result()) {
            return $data->first_row();
        } else {
            return false;
        }
    }

    /* Select data course */
    public function find_all_course()
    {
        $data = $this->db->query('SELECT
                                    sars.sar_id,
                                    sars.course_id,
                                    courses.course_name,
                                    courses.course_code,
                                    courses.course_year,
                                    sars.sar_date
                                  FROM
                                    sars
                                  JOIN courses ON courses.course_id = sars.course_id
                                  GROUP BY
                                    sars.course_id
                                  ORDER BY
                                    sars.sar_date DESC');

        if ($data->result()) {
            return $data->result();
        } else {
            return false;
        }
    }

    /* Select data by course_id */
    public function find_course($course_id, $last = false)
    {
        if ($last) {
            $data = $this->db->query('SELECT
                                        sars.sar_id,
                                        sars.course_id,
                                        courses.course_name,
                                        courses.course_code,
                                        courses.course_year,
                                        sars.sar_date
                                      FROM
                                        sars
                                      JOIN courses ON courses.course_id = sars.course_id
                                      WHERE
                                        sars.course_id = "'.$course_id.'"
                                      ORDER BY
                                        sars.sar_date DESC');
        } else {
            $data = $this->db->query('SELECT
                                          sars.sar_id,
                                          sars.course_id,
                                          courses.course_name,
                                          courses.course_code,
                                          courses.course_year,
                                          sars.sar_date
                                        FROM
                                          sars
                                        JOIN courses ON courses.course_id = sars.course_id
                                        WHERE
                                          sars.course_id = "'.$course_id.'"
                                        ORDER BY
                                          sars.sar_date DESC
                                        LIMIT 1');
        }

        if ($data->result()) {
            if ($last) {
                $data = $data->first_row();
            } else {
                $data = $data->result();
            }

            return $data;
        } else {
            return false;
        }
    }

    /* Select SAR */
    public function get_sar_course($course_id, $id = '')
    {
        $data = false;
        if ($id) {
            $sar = $this->find($id);
        } else {
            $sar = $this->find_course($course_id, true);
        }
        if ($sar) {
            $this->load->model('Kpis_model');
            $this->load->model('Sar_has_kpis_model');
            $kpis = $this->Kpis_model->find_all();
            $sar_has_kpis = $this->Sar_has_kpis_model->find($sar->sar_id, true);
            if ($kpis) {
                $result = array();
                foreach ($kpis as $key_1 => $value_1) {
                    if (isset($sar_has_kpis[$key_1])) {
                        $result[$key_1]['title'] = $value_1['title'];
                        $result[$key_1]['pass'] = $sar_has_kpis[$key_1]['pass'];
                        $result[$key_1]['fail'] = $sar_has_kpis[$key_1]['fail'];
                    }
                    if (isset($value_1['child']) && $value_1['child']) {
                        if (!isset($result[$key_1]['title'])) {
                            $result[$key_1]['title'] = $value_1['title'];
                        }
                        foreach ($value_1['child'] as $key_2 => $value_2) {
                            if (isset($sar_has_kpis[$key_1])) {
                                $result[$key_1]['child'][$key_2]['title'] = $value_2['title'];
                                $result[$key_1]['child'][$key_2]['pass'] = $sar_has_kpis[$key_2]['pass'];
                                $result[$key_1]['child'][$key_2]['fail'] = $sar_has_kpis[$key_2]['fail'];
                            }
                            if (isset($value_2['child']) && $value_2['child']) {
                                if (!isset($result[$key_1]['child'][$key_2]['title'])) {
                                    $result[$key_1]['child'][$key_2]['title'] = $value_2['title'];
                                }
                                foreach ($value_2['child'] as $key_3 => $value_3) {
                                    if (isset($sar_has_kpis[$key_3])) {
                                        $result[$key_1]['child'][$key_2]['child'][$key_3]['title'] = $value_3['title'];
                                        $result[$key_1]['child'][$key_2]['child'][$key_3]['pass'] = $sar_has_kpis[$key_3]['pass'];
                                        $result[$key_1]['child'][$key_2]['child'][$key_3]['fail'] = $sar_has_kpis[$key_3]['fail'];
                                    }
                                }
                            }
                        }
                    }
                }
                $data = $sar;
                $data->kpi = $result;
            }
        }

        return $data;
    }

    /* Insert/Update data */
    public function save($course_id)
    {
        $result = false;

        $data = $this->db->query('INSERT INTO sars (course_id, sar_date) VALUES ("'.$course_id.'", "'.date('Y-m-d H:i:s').'")');
        if ($data) {
            $result = $this->db->insert_id();
            // Create KPI
            for ($i = 4; $i <= 9; ++$i) {
                $kpi = $this->db->query('INSERT INTO sar_has_kpis (sar_id, kpi_id) VALUES ("'.$result.'", "'.$i.'")');
            }
        }

        return $result;
    }
}
