<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class AclRolesToResourcesMigration_100
 */
class AclRolesToResourcesMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('acl_roles_to_resources', array(
                'columns' => array(
                    new Column(
                        'role_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 8,
                            'first' => true
                        )
                    ),
                    new Column(
                        'resource_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 8,
                            'after' => 'role_id'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('role_id', 'resource_id'), 'PRIMARY'),
                    new Index('resource_id', array('resource_id'), null)
                ),
                'references' => array(
                    new Reference(
                        'acl_roles_to_resources_ibfk_1',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'acl_roles',
                            'columns' => array('role_id'),
                            'referencedColumns' => array('id'),
                            'onUpdate' => '',
                            'onDelete' => ''
                        )
                    ),
                    new Reference(
                        'acl_roles_to_resources_ibfk_2',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'acl_resources',
                            'columns' => array('resource_id'),
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
        $this->batchInsert('acl_roles_to_resources', array(
                'role_id',
                'resource_id'
            )
        );
     }
}
