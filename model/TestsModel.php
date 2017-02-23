<?php
/**
 * @deprecated 3.2.0
 */
class TestsModel extends BaseLessonsModel implements ISyncronizableModel {

    public function init()
    {
        $this->unit_type =  "test";

        parent::init();

        $this->selectSql = "SELECT
            l.`id`,
            l.`class_id`,
            c.`name` as class,
            l.`name`,
            l.`info`,
            l.`active`,
            l.`type`,
            COUNT(tq.question_id) AS total_questions,
            /*
            l.`has_text_content`,
            l.`text_content`,
            l.`text_content_language_id`,
            l.`has_video_content`,
            */
            IFNULL(t.`grade_id`, 0) as grade_id,
            IFNULL(l.`instructor_id`, c.`instructor_id`) as instructor_id,
            IFNULL(t.`time_limit`, 0) as time_limit,
            IFNULL(t.`allow_pause`, 0) as allow_pause,
            IFNULL(t.`test_repetition`, 1) as test_repetition,
            IFNULL(t.`show_question_weight`, 0) as show_question_weight,
            IFNULL(t.`show_question_difficulty`, 0) as show_question_difficulty,
            IFNULL(t.`show_question_type`, 1) as show_question_type,
            IFNULL(t.`show_one_by_one`, 0) as show_one_by_one,
            IFNULL(t.`can_navigate_through`, 0) as can_navigate_through,
            IFNULL(t.`show_correct_answers`, 0) as show_correct_answers,
            IFNULL(t.`randomize_questions`, 0) as randomize_questions,
            IFNULL(t.`randomize_answers`, 0) as randomize_answers
        FROM mod_units l
        LEFT JOIN mod_classes c ON (c.id = l.class_id)
        LEFT JOIN mod_tests t ON (l.id = t.id)
        LEFT JOIN mod_tests_to_questions tq ON (l.id = tq.unit_id)";

        $this->group_by = array("l.`id`");
    }

    public function addItem($data)
    {
        $unit_id = parent::addItem($data);

        $old_table_name = $this->table_name;
        $this->table_name = "mod_tests";

        $data['id'] = $unit_id;
        $test_settings = parent::addItem($data);

        $this->table_name = $old_table_name;

        return $unit_id;
    }

    public function setItem($data, $identifier)
    {
        $result = parent::setItem($data, $identifier);

        $exists = $this->db->GetOne("SELECT COUNT(id) FROM mod_tests WHERE id = '{$identifier}'");

        $old_table_name = $this->table_name;
        $this->table_name = "mod_tests";

        if ($exists == "1") {
            $test_settings = parent::setItem($data, $identifier);
        } else {
            $data['id'] = $identifier;
            $test_settings = parent::addItem($data);
        }

        $this->table_name = $old_table_name;

        return $result;
    }
}
