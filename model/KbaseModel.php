<?php
class KbaseModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_tutoria";
        $this->id_field = "id";
        $this->mainTablePrefix = "tt";
        //$this->fieldsMap = array();
        //

       // GET LAST MESSAGES FROM USER LESSONS
       /*
        $tutorias = $this->_getTableData("mod_tutoria tt
            LEFT OUTER JOIN lessons l ON (tt.lessons_ID = l.id)
            LEFT OUTER JOIN users u1 ON (tt.question_user_id = u1.id)
            LEFT OUTER JOIN users u2 ON (tt.answer_user_id = u2.id)",
            "tt.id, tt.lessons_ID, tt.unit_ID, tt.title,
            tt.question_timestamp,
            tt.question_user_id,
            u1.name as question_user_name,
            u1.surname as question_user_surname,
            u1.avatar as question_avatar_id,
            tt.question,
            tt.answer_timestamp,
            tt.answer_user_id,
            u2.name as answer_user_name,
            u2.surname as answer_user_surname,
            u2.avatar as answer_avatar_id,
            tt.answer,
            tt.approved",

            sprintf("tt.lessons_ID IN (0, %s) AND (tt.approved = 1 OR tt.question_user_id = %d)", implode(",", $lessonsIds), $currentUser->user['id']),
            "tt.question_timestamp DESC",
            "",
            sprintf("%d, %d", ($page - 1) * $per_page, $per_page)
        );
        */
        $this->selectSql =
            "SELECT
                tt.id,
                tt.lessons_ID,
                tt.unit_ID,
                tt.title,
                l.id as lesson_id,
                tt.question_user_id as 'question#user_id',
                tt.question_timestamp as 'question#timestamp',
                u1.name as 'question#user_name',
                u1.surname as 'question#user_surname',
                u1.avatar as 'question#avatar_id',
                tt.question,
                tt.answer_timestamp as 'answer#timestamp',
                tt.answer_user_id as 'answer#user_id',
                u2.name as 'answer#user_name',
                u2.surname as 'answer#user_surname',
                u2.avatar as 'answer#avatar_id',
                tt.answer as 'answer#answer',
                tt.approved
            FROM mod_tutoria tt
                LEFT OUTER JOIN mod_lessons l ON (tt.lessons_ID = l.id)
                LEFT OUTER JOIN users u1 ON (tt.question_user_id = u1.id)
                LEFT OUTER JOIN users u2 ON (tt.answer_user_id = u2.id)";

        $this->order = array("tt.question_timestamp DESC");

        $this->group_by = array("tt.`id`");

        parent::init();
    }


    protected function parseItem($item) {
         return $item;
    }

    public function getItems()
    {
        $data = parent::getItems();

        foreach($data as $key => $item) {
            $data[$key] = $this->parseItem($item);
        }
        return $data;
    }

    public function getItem($identifier)
    {
        $item = parent::getItem($identifier);
        return $this->parseItem($item);
    }
}
