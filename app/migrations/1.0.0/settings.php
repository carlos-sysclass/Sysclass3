<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class SettingsMigration_100
 */
class SettingsMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('settings', array(
                'columns' => array(
                    new Column(
                        'name',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 100,
                            'first' => true
                        )
                    ),
                    new Column(
                        'value',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'name'
                        )
                    ),
                    new Column(
                        'group',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 45,
                            'after' => 'value'
                        )
                    ),
                    new Column(
                        'label',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 45,
                            'after' => 'group'
                        )
                    ),
                    new Column(
                        'datatype',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "string",
                            'size' => 45,
                            'after' => 'label'
                        )
                    ),
                    new Column(
                        'description',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'datatype'
                        )
                    ),
                    new Column(
                        'changeable',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'description'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('name'), 'PRIMARY')
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
        $this->batchInsert('settings', array(
                'name',
                'value',
                'group',
                'label',
                'datatype',
                'description',
                'changeable'
            )
        );
     }
}
