<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ModCoursesMigration_101
 */
class ModCoursesMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('mod_courses', array(
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
                        'permission_access_mode',
                        array(
                            'type' => Column::TYPE_CHAR,
                            'default' => "4",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'area_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'size' => 8,
                            'after' => 'permission_access_mode'
                        )
                    ),
                    new Column(
                        'ies_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'size' => 8,
                            'after' => 'area_id'
                        )
                    ),
                    new Column(
                        'name',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 150,
                            'after' => 'ies_id'
                        )
                    ),
                    new Column(
                        'active',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'name'
                        )
                    ),
                    new Column(
                        'archive',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'unsigned' => true,
                            'size' => 10,
                            'after' => 'active'
                        )
                    ),
                    new Column(
                        'created',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 10,
                            'after' => 'archive'
                        )
                    ),
                    new Column(
                        'start_date',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 10,
                            'after' => 'created'
                        )
                    ),
                    new Column(
                        'end_date',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 10,
                            'after' => 'start_date'
                        )
                    ),
                    new Column(
                        'options',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'end_date'
                        )
                    ),
                    new Column(
                        'metadata',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'options'
                        )
                    ),
                    new Column(
                        'description',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'metadata'
                        )
                    ),
                    new Column(
                        'info',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'description'
                        )
                    ),
                    new Column(
                        'price',
                        array(
                            'type' => Column::TYPE_FLOAT,
                            'default' => "0",
                            'size' => 1,
                            'after' => 'info'
                        )
                    ),
                    new Column(
                        'enable_registration',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'price'
                        )
                    ),
                    new Column(
                        'price_registration',
                        array(
                            'type' => Column::TYPE_FLOAT,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'enable_registration'
                        )
                    ),
                    new Column(
                        'enable_presencial',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'size' => 1,
                            'after' => 'price_registration'
                        )
                    ),
                    new Column(
                        'price_presencial',
                        array(
                            'type' => Column::TYPE_FLOAT,
                            'default' => "0",
                            'size' => 1,
                            'after' => 'enable_presencial'
                        )
                    ),
                    new Column(
                        'enable_web',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'size' => 1,
                            'after' => 'price_presencial'
                        )
                    ),
                    new Column(
                        'price_web',
                        array(
                            'type' => Column::TYPE_FLOAT,
                            'default' => "0",
                            'size' => 1,
                            'after' => 'enable_web'
                        )
                    ),
                    new Column(
                        'show_catalog',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'price_web'
                        )
                    ),
                    new Column(
                        'publish',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'size' => 1,
                            'after' => 'show_catalog'
                        )
                    ),
                    new Column(
                        'directions_ID',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 8,
                            'after' => 'publish'
                        )
                    ),
                    new Column(
                        'reset',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'directions_ID'
                        )
                    ),
                    new Column(
                        'certificate_expiration',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 10,
                            'after' => 'reset'
                        )
                    ),
                    new Column(
                        'max_users',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 10,
                            'after' => 'certificate_expiration'
                        )
                    ),
                    new Column(
                        'rules',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'max_users'
                        )
                    ),
                    new Column(
                        'terms',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'rules'
                        )
                    ),
                    new Column(
                        'instance_source',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'unsigned' => true,
                            'size' => 8,
                            'after' => 'terms'
                        )
                    ),
                    new Column(
                        'supervisor_LOGIN',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 100,
                            'after' => 'instance_source'
                        )
                    ),
                    new Column(
                        'currency',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "pt-br",
                            'size' => 10,
                            'after' => 'supervisor_LOGIN'
                        )
                    ),
                    new Column(
                        'has_grouping',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'currency'
                        )
                    ),
                    new Column(
                        'has_student_selection',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'has_grouping'
                        )
                    ),
                    new Column(
                        'has_periods',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'has_student_selection'
                        )
                    ),
                    new Column(
                        'coordinator_id',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'has_periods'
                        )
                    ),
                    new Column(
                        'duration_units',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 8,
                            'after' => 'coordinator_id'
                        )
                    ),
                    new Column(
                        'duration_type',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "year",
                            'notNull' => true,
                            'size' => 45,
                            'after' => 'duration_units'
                        )
                    ),
                    new Column(
                        'price_total',
                        array(
                            'type' => Column::TYPE_DECIMAL,
                            'default' => "0.00",
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 12,
                            'scale' => 2,
                            'after' => 'duration_type'
                        )
                    ),
                    new Column(
                        'price_step_units',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "10",
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 8,
                            'after' => 'price_total'
                        )
                    ),
                    new Column(
                        'price_step_type',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "month",
                            'notNull' => true,
                            'size' => 45,
                            'after' => 'price_step_units'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY'),
                    new Index('instance_source', array('instance_source'), null)
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
        $this->batchInsert('mod_courses', array(
                'id',
                'permission_access_mode',
                'area_id',
                'ies_id',
                'name',
                'active',
                'archive',
                'created',
                'start_date',
                'end_date',
                'options',
                'metadata',
                'description',
                'info',
                'price',
                'enable_registration',
                'price_registration',
                'enable_presencial',
                'price_presencial',
                'enable_web',
                'price_web',
                'show_catalog',
                'publish',
                'directions_ID',
                'reset',
                'certificate_expiration',
                'max_users',
                'rules',
                'terms',
                'instance_source',
                'supervisor_LOGIN',
                'currency',
                'has_grouping',
                'has_student_selection',
                'has_periods',
                'coordinator_id',
                'duration_units',
                'duration_type',
                'price_total',
                'price_step_units',
                'price_step_type'
            )
        );
     }
}
