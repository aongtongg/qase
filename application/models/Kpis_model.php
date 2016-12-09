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

    /* Check rule 1 */
    public function check_rule_1($teacher_id)
    {
        // อาจารย์ประจำ (เต็มเวลา) + จบโท
        // อาจารย์ประจำต้องจบการศึกษาปริญญาโทเป็นอย่างต่ำ
        $data = $this->db->query('SELECT
                                    degree.degree_id,
                                    degree.degree_name,
                                    members.members_type
                                  FROM
                                    seminar.members
                                  JOIN
                                    seminar.degree ON degree.degree_id = members.members_degree
                                  WHERE
                                    members.members_id = "'.$teacher_id.'"');

        $result = true;
        if ($data->result()) {
            $data = $data->first_row();
            if ($data->members_type == 1 && $data->degree_id < 2) {
                $result = false;
            }
        }

        return $result;
    }

    /* Check rule 2 */
    public function check_rule_2($researcher_id)
    {
        // มีผลงาน 3 เรื่องภายใน 5 ปี และ 1 ใน 3 เรื่องเป็นวิจัย
        // ต้องมีผลงาน 3 เรื่องภายใน 5 ปี โดยที่ 1 ในผลงานเป็นงานวิจัย
        $research_year = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))).'-5 year'));
        $data = $this->db->query('SELECT
                                    fact.frid,
                                    SUM(IF(fact.fdtype=1,1,0)) AS count_research,
                                    COUNT(fact.frid) AS count_all
                                  FROM
                                    seminar.fact
                                  JOIN
                                    seminar.research ON research.rid = fact.frid
                                  WHERE
                                    fact.frhid = "'.$researcher_id.'" AND
                                    research.ryear >= "'.$research_year.'"
                                  GROUP BY fact.frhid');

        $result = false;
        if ($data->result()) {
            $data = $data->first_row();
            if ($data->count_research >= 1 && $data->count_all >= 3) {
                $result = true;
            }
        }

        return $result;
    }

    /* Check rule 3 */
    public function check_rule_3($researcher_id)
    {
        // อาจารย์ใหม่ + จบ ดร. + 1 เรื่องภายใน 2 ปี or 2 เรื่องภายใน 4 ปี or 3 เรื่องภายใน 5 ปี
        // อาจารย์ใหม่ต้องจบการศึกษาปริญญาเอกและมีผลงาน 1 เรื่องภายใน 2 ปีหรือมีผลงาน 2 เรื่องภายใน 4 ปีหรือผลงาน 3 เรื่องภายใน 5 ปี
        $data = $this->db->query('SELECT
                                    degree.degree_id,
                                    degree.degree_name,
                                    members.members_type
                                  FROM
                                    seminar.members
                                  JOIN
                                    seminar.degree ON degree.degree_id = members.members_degree
                                  WHERE
                                    members.members_relation = "'.$researcher_id.'"');

        if ($data->result()) {
            $degree_id = $data->first_row()->degree_id;
        } else {
            $degree_id = '';
        }
        $research_year = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))).'-2 year'));
        $data = $this->db->query('SELECT
                                    fact.frid,
                                    COUNT(fact.frid) AS count_all
                                  FROM
                                    seminar.fact
                                  JOIN
                                    seminar.research ON research.rid = fact.frid
                                  WHERE
                                    fact.frhid = "'.$researcher_id.'" AND
                                    research.ryear >= "'.$research_year.'"
                                  GROUP BY fact.frhid');

        $result = false;
        if ($data->result()) {
            $data = $data->first_row();
            if ($degree_id == 3 && $data->count_all >= 1) {
                $result = true;
            }
        }
        if (!$result) {
            $research_year = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))).'-4 year'));
            $data = $this->db->query('SELECT
                                        fact.frid,
                                        COUNT(fact.frid) AS count_all
                                      FROM
                                        seminar.fact
                                      JOIN
                                        seminar.research ON research.rid = fact.frid
                                      WHERE
                                        fact.frhid = "'.$researcher_id.'" AND
                                        research.ryear >= "'.$research_year.'"
                                      GROUP BY fact.frhid');
            if ($data->result()) {
                $data = $data->first_row();
                if ($degree_id == 3 && $data->count_all >= 2) {
                    $result = true;
                }
            }
        }
        if (!$result) {
            $research_year = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))).'-5 year'));
            $data = $this->db->query('SELECT
                                        fact.frid,
                                        COUNT(fact.frid) AS count_all
                                      FROM
                                        seminar.fact
                                      JOIN
                                        seminar.research ON research.rid = fact.frid
                                      WHERE
                                        fact.frhid = "'.$researcher_id.'" AND
                                        research.ryear >= "'.$research_year.'"
                                      GROUP BY fact.frhid');
            if ($data->result()) {
                $data = $data->first_row();
                if ($degree_id == 3 && $data->count_all >= 3) {
                    $result = true;
                }
            }
        }

        return $result;
    }

    /* Check rule 4 */
    public function check_rule_4($teacher_id)
    {
        // อาจารย์ประจำ (เต็มเวลา) + จบ เอก Or โท+รศ
        // เป็นอาจารย์ประจำต้องจบปริญญาเอกหรือจบปริญญาโทและเป็นรองศาสตราจารย์
        $data = $this->db->query('SELECT
                                    degree.degree_id,
                                    degree.degree_name,
                                    members.members_type,
                                    position.position_id,
                                    position.position_name
                                  FROM
                                    seminar.members
                                  JOIN
                                    seminar.degree ON degree.degree_id = members.members_degree
                                  JOIN
                                    seminar.position ON position.position_id = members.members_position
                                  WHERE
                                    members.members_id = "'.$teacher_id.'"');

        $result = false;
        if ($data->result()) {
            $data = $data->first_row();
            $allow = array(5, 6, 7, 8);
            if ($data->members_type == 1 && $data->degree_id > 2 && in_array($data->position_id, $allow)) {
                $result = true;
            }
        }

        return $result;
    }

    /* Check rule 5 */
    public function check_rule_5($researcher_id)
    {
        // ผู้ทรงคุณวุฒิ + จบ ดร และมีงานวิจัย 10 เรื่อง
        // ผู้ทรงคุณวุฒิต้องจบปริญญาเอกและมีงานวิจัย 10 เรื่อง
        $data = $this->db->query('SELECT
                                    degree.degree_id,
                                    degree.degree_name,
                                    members.members_type
                                  FROM
                                    seminar.members
                                  JOIN
                                    seminar.degree ON degree.degree_id = members.members_degree
                                  WHERE
                                    members.members_relation = "'.$researcher_id.'"');

        if ($data->result()) {
            $member_type = $data->first_row()->members_type;
            $degree_id = $data->first_row()->degree_id;
        } else {
            $member_type = '';
            $degree_id = '';
        }
        $data = $this->db->query('SELECT
                                    fact.frid,
                                    COUNT(fact.frid) AS count_all
                                  FROM
                                    seminar.fact
                                  JOIN
                                    seminar.research ON research.rid = fact.frid
                                  WHERE
                                    fact.frhid = "'.$researcher_id.'"
                                  GROUP BY fact.frhid');

        $result = false;
        if ($data->result()) {
            $data = $data->first_row();
            if ($degree_id == 3 && $member_type == 3 && $data->count_all >= 10) {
                $result = true;
            }
        }

        return $result;
    }

    /* Check rule 6 */
    public function check_rule_6($teacher_id)
    {
        // อาจารย์ใหม่ + จบ ดร
        // อาจารย์ใหม่จบปริญญาเอก
        $data = $this->db->query('SELECT
                                    degree.degree_id,
                                    degree.degree_name,
                                    members.members_type
                                  FROM
                                    seminar.members
                                  JOIN
                                    seminar.degree ON degree.degree_id = members.members_degree
                                  WHERE
                                    members.members_id = "'.$teacher_id.'"');

        $result = false;
        if ($data->result()) {
            if ($data->first_row()->degree_id == 3) {
                $result = true;
            }
        }

        return $result;
    }

    /* Check rule 7 */
    public function check_rule_7($researcher_id)
    {
        // มีประสบการณ์ + มีผลงาน 1 เรื่องภายใน 5 ปี
        // มีประสบการณ์และผลงาน 1 เรื่องภายใน 5 ปี
        $research_year = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))).'-5 year'));
        $data = $this->db->query('SELECT
                                    fact.frid,
                                    COUNT(fact.frid) AS count_all
                                  FROM
                                    seminar.fact
                                  JOIN
                                    seminar.research ON research.rid = fact.frid
                                  WHERE
                                    fact.frhid = "'.$researcher_id.'" AND
                                    research.ryear >= "'.$research_year.'"
                                  GROUP BY fact.frhid');

        $result = false;
        if ($data->result()) {
            $data = $data->first_row();
            if ($data->count_all >= 1) {
                $result = true;
            }
        }

        return $result;
    }

    /* Check rule 8 */
    public function check_rule_8($course_id, $role_id)
    {
        // >= 3 ท่าน
        // ต้องมีอาจารย์ 3 ท่านขึ้น
        $data = $this->db->query('SELECT
                                    COUNT(teacher_id) AS count_all
                                  FROM
                                    teacher_has_courses
                                  WHERE
                                    course_id = "'.$course_id.'" AND
                                    role_id = "'.$role_id.'"');

        $result = false;
        if ($data->result()) {
            $data = $data->first_row();
            if ($data->count_all >= 3) {
                $result = true;
            }
        }

        return $result;
    }

    /* Check rule 9 */
    public function check_rule_9($course_id, $role_id)
    {
        // อ ประจำหลักสูตร + ผู้ทรงคุณวุฒิ >= 3 คน
        // ต้องมีอาจารย์ประจำหลักสูตร และผู้ทรงคุณวุฒิ 2 ท่านขึ้น
        $data = $this->db->query('SELECT
                                    COUNT(teacher_id) AS count_all
                                  FROM
                                    teacher_has_courses
                                  JOIN
                                    seminar.members ON members.members_id = teacher_has_courses.teacher_id
                                  WHERE
                                    members.members_type IN (1, 3) AND
                                    course_id = "'.$course_id.'" AND
                                    role_id = "'.$role_id.'"');

        $result = false;
        if ($data->result()) {
            $data = $data->first_row();
            if ($data->count_all >= 3) {
                $result = true;
            } else {
                $result = false;
            }
        }

        return $result;
    }

    /* Check rule 10 */
    public function check_rule_10($course_id, $teacher_id)
    {
        // ประธานธานไม่ใช่อ ที่ปรึกษา IS หลัก หรือ อ ที่ปรึกษา IS ร่วม
        // ประธานคณะกรรมการสอบต้องไม่ใช่อาจารย์ปรึกษาการค้นคว้าอิสระหลักหรือปรึกษาการค้นคว้าอิสระร่วม
        $data = $this->db->query('SELECT
                                    COUNT(teacher_id) AS count_all
                                  FROM
                                    teacher_has_courses
                                  WHERE
                                    teacher_id = "'.$teacher_id.'" AND
                                    course_id = "'.$course_id.'" AND
                                    role_id IN (4, 5)');

        $result = false;
        if ($data->result()) {
            $data = $data->first_row();
            if ($data->count_all == 0) {
                $result = true;
            }
        }

        return $result;
    }

    /* Check rule 11 */
    public function check_rule_11($course_id, $teacher_id)
    {
        // อ ประจำหลักสูตร
        // เป็นอาจารย์ประจำหลักสูตร
        $data = $this->db->query('SELECT
                                    COUNT(teacher_id) AS count_all
                                  FROM
                                    teacher_has_courses
                                  WHERE
                                    teacher_id = "'.$teacher_id.'" AND
                                    course_id = "'.$course_id.'" AND
                                    role_id = "2"');

        $result = false;
        if ($data->result()) {
            if ($data->first_row()->count_all) {
                $result = true;
            }
        }

        return $result;
    }

    /* Check rule 12 */
    public function check_rule_12($teacher_id)
    {
        // อาจารย์พิเศษต้องจบปริญญาโท
        $data = $this->db->query('SELECT
                                    degree.degree_id,
                                    degree.degree_name,
                                    members.members_type
                                  FROM
                                    seminar.members
                                  JOIN
                                    seminar.degree ON degree.degree_id = members.members_degree
                                  WHERE
                                    members.members_id = "'.$teacher_id.'"');

        $result = true;
        if ($data->result()) {
            if ($data->first_row()->members_type == 2) {
                if ($data->first_row()->degree_id < 2) {
                    $result = false;
                }
            }
        }

        return $result;
    }
}
