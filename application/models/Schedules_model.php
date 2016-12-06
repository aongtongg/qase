<?php

class Schedules_model extends CI_Model
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
                                    schedules.schedule_id,
                                    schedules.course_id,
                                    courses.course_name,
                                    courses.course_year,
                                    schedules.execute_day,
                                    schedules.execute_month,
                                    schedules.execute_time,
                                    schedules.schedule_active,
                                    schedules.schedule_last_execute
                                  FROM
                                    schedules
                                  JOIN courses ON courses.course_id = schedules.course_id');

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
                                    schedules.schedule_id,
                                    schedules.course_id,
                                    courses.course_name,
                                    courses.course_year,
                                    schedules.execute_day,
                                    schedules.execute_month,
                                    schedules.execute_time,
                                    schedules.schedule_active,
                                    schedules.schedule_last_execute
                                  FROM
                                    schedules
                                  JOIN courses ON courses.course_id = schedules.course_id
                                  WHERE
                                    schedules.schedule_id = "'.$id.'"');

        if ($data->result()) {
            return $data->first_row();
        } else {
            return false;
        }
    }

    /* Insert/Update data */
    public function save($data, $id = null)
    {
        $result = false;

        if ($id) {
            $data = $this->db->query('UPDATE schedules
                                  SET
                                   course_id = "'.$data['course_id'].'",
                                   execute_day = "'.$data['execute_day'].'",
                                   execute_month = "'.$data['execute_month'].'",
                                   execute_time = "'.$data['execute_time'].'",
                                   schedule_active = "'.$data['schedule_active'].'"
                                  WHERE
                                    schedule_id = "'.$id.'"');
            if ($data) {
                $result = $id;
            }
        } else {
            $data = $this->db->query('INSERT INTO schedules (
                                    course_id,
                                    execute_day,
                                    execute_month,
                                    execute_time,
                                    schedule_active)
                                  VALUES (
                                    "'.$data['course_id'].'",
                                    "'.$data['execute_day'].'",
                                    "'.$data['execute_month'].'",
                                    "'.$data['execute_time'].'",
                                    "'.$data['schedule_active'].'"
                                  )');
            if ($data) {
                $result = $this->db->insert_id();
            }
        }

        return $result;
    }

    /* Delete data */
    public function delete($id = null)
    {
        $result = false;

        if ($id) {
            $data = $this->find($id);
            if ($data) {
                $delete = $this->db->query('DELETE FROM schedules WHERE schedule_id = "'.$id.'"');

                if ($data) {
                    $result = true;
                }
            }
        }

        return $result;
    }
}
