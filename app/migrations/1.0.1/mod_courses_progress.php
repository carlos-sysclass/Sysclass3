<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ModCoursesProgressMigration_101
 */
class ModCoursesProgressMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('mod_courses_progress', array(
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
                        'course_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 8,
                            'after' => 'user_id'
                        )
                    ),
                    new Column(
                        'factor',
                        array(
                            'type' => Column::TYPE_DECIMAL,
                            'default' => "0.000",
                            'notNull' => true,
                            'size' => 4,
                            'scale' => 3,
                            'after' => 'course_id'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY'),
                    new Index('user_id', array('user_id'), null),
                    new Index('course_id', array('course_id'), null)
                ),
                'references' => array(
                    new Reference(
                        'mod_courses_progress_ibfk_1',
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
                        'mod_courses_progress_ibfk_2',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'mod_courses',
                            'columns' => array('course_id'),
                            'referencedColumns' => array('id'),
                            'onUpdate' => '',
                            'onDelete' => ''
                        )
                    )
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '2',
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
        $this->batchInsert('mod_courses_progress', array(
                'id',
                'user_id',
                'course_id',
                'factor'
            )
        );
     }
}
