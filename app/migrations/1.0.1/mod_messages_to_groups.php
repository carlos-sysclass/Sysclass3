<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ModMessagesToGroupsMigration_101
 */
class ModMessagesToGroupsMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('mod_messages_to_groups', array(
                'columns' => array(
                    new Column(
                        'message_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 8,
                            'first' => true
                        )
                    ),
                    new Column(
                        'group_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 8,
                            'after' => 'message_id'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('message_id', 'group_id'), 'PRIMARY'),
                    new Index('group_id', array('group_id'), null)
                ),
                'references' => array(
                    new Reference(
                        'mod_messages_to_groups_ibfk_1',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'mod_messages',
                            'columns' => array('message_id'),
                            'referencedColumns' => array('id'),
                            'onUpdate' => '',
                            'onDelete' => ''
                        )
                    ),
                    new Reference(
                        'mod_messages_to_groups_ibfk_2',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'groups',
                            'columns' => array('group_id'),
                            'referencedColumns' => array('id'),
                            'onUpdate' => '',
                            'onDelete' => ''
                        )
                    )
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ),
            )
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

    /**
     * This method is called after the table was created
     *
     * @return void
     */
     public function afterCreateTable()
     {
        $this->batchInsert('mod_messages_to_groups', array(
                'message_id',
                'group_id'
            )
        );
     }
}
