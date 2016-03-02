<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ModGradesRangesMigration_101
 */
class ModGradesRangesMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('mod_grades_ranges', array(
                'columns' => array(
                    new Column(
                        'id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true
                        )
                    ),
                    new Column(
                        'grade_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'grade',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 100,
                            'after' => 'grade_id'
                        )
                    ),
                    new Column(
                        'range_start',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 4,
                            'after' => 'grade'
                        )
                    ),
                    new Column(
                        'range_end',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "100",
                            'notNull' => true,
                            'size' => 4,
                            'after' => 'range_start'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY'),
                    new Index('grade_id', array('grade_id'), null)
                ),
                'references' => array(
                    new Reference(
                        'mod_grades_ranges_ibfk_1',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'mod_grades',
                            'columns' => array('grade_id'),
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
        $this->batchInsert('mod_grades_ranges', array(
                'id',
                'grade_id',
                'grade',
                'range_start',
                'range_end'
            )
        );
     }
}
