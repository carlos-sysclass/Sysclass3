<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ModTestsExecutionMigration_100
 */
class ModTestsExecutionMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('mod_tests_execution', array(
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
                        'user_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 8,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'test_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 8,
                            'after' => 'user_id'
                        )
                    ),
                    new Column(
                        'try_index',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 4,
                            'after' => 'test_id'
                        )
                    ),
                    new Column(
                        'start_timestamp',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'try_index'
                        )
                    ),
                    new Column(
                        'paused',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'start_timestamp'
                        )
                    ),
                    new Column(
                        'pending',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'paused'
                        )
                    ),
                    new Column(
                        'completed',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'pending'
                        )
                    ),
                    new Column(
                        'answers',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'completed'
                        )
                    ),
                    new Column(
                        'user_score',
                        array(
                            'type' => Column::TYPE_DECIMAL,
                            'size' => 15,
                            'scale' => 4,
                            'after' => 'answers'
                        )
                    ),
                    new Column(
                        'user_points',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'user_score'
                        )
                    ),
                    new Column(
                        'user_grade',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 100,
                            'after' => 'user_points'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY'),
                    new Index('test_id', array('test_id'), null)
                ),
                'references' => array(
                    new Reference(
                        'mod_tests_execution_ibfk_1',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'mod_lessons',
                            'columns' => array('test_id'),
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
        $this->batchInsert('mod_tests_execution', array(
                'id',
                'user_id',
                'test_id',
                'try_index',
                'start_timestamp',
                'paused',
                'pending',
                'completed',
                'answers',
                'user_score',
                'user_points',
                'user_grade'
            )
        );
     }
}
