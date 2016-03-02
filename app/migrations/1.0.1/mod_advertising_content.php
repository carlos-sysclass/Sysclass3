<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ModAdvertisingContentMigration_101
 */
class ModAdvertisingContentMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('mod_advertising_content', array(
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
                        'advertising_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 8,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'content_type',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'advertising_id'
                        )
                    ),
                    new Column(
                        'title',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 150,
                            'after' => 'content_type'
                        )
                    ),
                    new Column(
                        'info',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'title'
                        )
                    ),
                    new Column(
                        'language_code',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "en",
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'info'
                        )
                    ),
                    new Column(
                        'position',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'language_code'
                        )
                    ),
                    new Column(
                        'active',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'position'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY'),
                    new Index('advertising_id', array('advertising_id'), null)
                ),
                'references' => array(
                    new Reference(
                        'mod_advertising_content_ibfk_1',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'mod_advertising',
                            'columns' => array('advertising_id'),
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
        $this->batchInsert('mod_advertising_content', array(
                'id',
                'advertising_id',
                'content_type',
                'title',
                'info',
                'language_code',
                'position',
                'active'
            )
        );
     }
}
