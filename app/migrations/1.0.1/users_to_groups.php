<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class UsersToGroupsMigration_101
 */
class UsersToGroupsMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('users_to_groups', array(
                'columns' => array(
                    new Column(
                        'user_id',
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
                            'after' => 'user_id'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('user_id', 'group_id'), 'PRIMARY'),
                    new Index('group_id', array('group_id'), null)
                ),
                'references' => array(
                    new Reference(
                        'users_to_groups_ibfk_1',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'users',
                            'columns' => array('user_id'),
                            'referencedColumns' => array('id'),
                            'onUpdate' => '',
                            'onDelete' => ''
                        )
                    ),
                    new Reference(
                        'users_to_groups_ibfk_2',
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
        $this->batchInsert('users_to_groups', array(
                'user_id',
                'group_id'
            )
        );
     }
}
