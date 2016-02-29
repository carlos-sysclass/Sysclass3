<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class GroupsMigration_101
 */
class GroupsMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('groups', array(
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
                        'name',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 150,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'description',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'name'
                        )
                    ),
                    new Column(
                        'active',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'description'
                        )
                    ),
                    new Column(
                        'dynamic',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'active'
                        )
                    ),
                    new Column(
                        'created',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 10,
                            'after' => 'dynamic'
                        )
                    ),
                    new Column(
                        'user_types_ID',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "0",
                            'size' => 50,
                            'after' => 'created'
                        )
                    ),
                    new Column(
                        'languages_NAME',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 50,
                            'after' => 'user_types_ID'
                        )
                    ),
                    new Column(
                        'users_active',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'size' => 1,
                            'after' => 'languages_NAME'
                        )
                    ),
                    new Column(
                        'assign_profile_to_new',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'size' => 1,
                            'after' => 'users_active'
                        )
                    ),
                    new Column(
                        'unique_key',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'assign_profile_to_new'
                        )
                    ),
                    new Column(
                        'is_default',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'size' => 1,
                            'after' => 'unique_key'
                        )
                    ),
                    new Column(
                        'key_max_usage',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'unsigned' => true,
                            'size' => 8,
                            'after' => 'is_default'
                        )
                    ),
                    new Column(
                        'key_current_usage',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'unsigned' => true,
                            'size' => 8,
                            'after' => 'key_max_usage'
                        )
                    ),
                    new Column(
                        'behaviour_allow_messages',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'size' => 1,
                            'after' => 'key_current_usage'
                        )
                    ),
                    new Column(
                        'image',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "group",
                            'size' => 20,
                            'after' => 'behaviour_allow_messages'
                        )
                    ),
                    new Column(
                        'image_type',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "primary",
                            'size' => 20,
                            'after' => 'image'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY')
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '3',
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
        $this->batchInsert('groups', array(
                'id',
                'name',
                'description',
                'active',
                'dynamic',
                'created',
                'user_types_ID',
                'languages_NAME',
                'users_active',
                'assign_profile_to_new',
                'unique_key',
                'is_default',
                'key_max_usage',
                'key_current_usage',
                'behaviour_allow_messages',
                'image',
                'image_type'
            )
        );
     }
}
