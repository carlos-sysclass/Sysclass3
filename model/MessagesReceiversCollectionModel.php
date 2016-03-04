<?php
/**
 * @deprecated 3.2.0
 */
class MessagesReceiversCollectionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        //$this->table_name = "mod_messages_groups";
        //$this->id_field = "id";
        //$this->mainTablePrefix = "mg";
        //$this->fieldsMap = array();

        $this->selectSql =
            "SELECT
                g.id as recipient_id,
                g.name as title,
                g.image,
                g.image_type,
                mmg.id as group_id,
                mmg.name as group_name
            FROM groups g
            LEFT JOIN users_to_groups ug ON (g.id = ug.group_id)
            LEFT JOIN mod_messages_groups mmg ON (g.behaviour_allow_messages = mmg.id)
            LEFT OUTER JOIN users u ON (ug.user_id = u.id)
            WHERE behaviour_allow_messages > 0";

        parent::init();

    }
}
