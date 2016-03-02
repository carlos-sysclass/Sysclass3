<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ModChatMigration_101
 */
class ModChatMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('mod_chat', array(
                'columns' => array(
                    new Column(
                        'id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 8,
                            'first' => true
                        )
                    ),
                    new Column(
                        'websocket_token',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'type',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 30,
                            'after' => 'websocket_token'
                        )
                    ),
                    new Column(
                        'topic',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 100,
                            'after' => 'type'
                        )
                    ),
                    new Column(
                        'subject',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 250,
                            'after' => 'topic'
                        )
                    ),
                    new Column(
                        'requester_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 8,
                            'after' => 'subject'
                        )
                    ),
                    new Column(
                        'started',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'requester_id'
                        )
                    ),
                    new Column(
                        'ping',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'started'
                        )
                    ),
                    new Column(
                        'closed',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'ping'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY'),
                    new Index('requester_id', array('requester_id'), null)
                ),
                'references' => array(
                    new Reference(
                        'mod_chat_ibfk_1',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'users',
                            'columns' => array('requester_id'),
                            'referencedColumns' => array('id'),
                            'onUpdate' => '',
                            'onDelete' => ''
                        )
                    )
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
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
        $this->batchInsert('mod_chat', array(
                'id',
                'websocket_token',
                'type',
                'topic',
                'subject',
                'requester_id',
                'started',
                'ping',
                'closed'
            )
        );
     }
}
