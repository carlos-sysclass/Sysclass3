<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ModInstitutionMigration_101
 */
class ModInstitutionMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('mod_institution', array(
                'columns' => array(
                    new Column(
                        'id',
                        array(
                            'type' => Column::TYPE_INTEGER,
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
                        'name',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 250,
                            'after' => 'permission_access_mode'
                        )
                    ),
                    new Column(
                        'formal_name',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 250,
                            'after' => 'name'
                        )
                    ),
                    new Column(
                        'contact',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 250,
                            'after' => 'formal_name'
                        )
                    ),
                    new Column(
                        'observations',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'contact'
                        )
                    ),
                    new Column(
                        'zip',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 15,
                            'after' => 'observations'
                        )
                    ),
                    new Column(
                        'address',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'zip'
                        )
                    ),
                    new Column(
                        'number',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 15,
                            'after' => 'address'
                        )
                    ),
                    new Column(
                        'address2',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 50,
                            'after' => 'number'
                        )
                    ),
                    new Column(
                        'city',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 100,
                            'after' => 'address2'
                        )
                    ),
                    new Column(
                        'state',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 20,
                            'after' => 'city'
                        )
                    ),
                    new Column(
                        'country_code',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "BR",
                            'notNull' => true,
                            'size' => 3,
                            'after' => 'state'
                        )
                    ),
                    new Column(
                        'phone',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 20,
                            'after' => 'country_code'
                        )
                    ),
                    new Column(
                        'active',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 4,
                            'after' => 'phone'
                        )
                    ),
                    new Column(
                        'website',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'active'
                        )
                    ),
                    new Column(
                        'facebook',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'website'
                        )
                    ),
                    new Column(
                        'logo_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 8,
                            'after' => 'facebook'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY'),
                    new Index('logo_id', array('logo_id'), null)
                ),
                'references' => array(
                    new Reference(
                        'mod_institution_ibfk_1',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'mod_dropbox',
                            'columns' => array('logo_id'),
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
        $this->batchInsert('mod_institution', array(
                'id',
                'permission_access_mode',
                'name',
                'formal_name',
                'contact',
                'observations',
                'zip',
                'address',
                'number',
                'address2',
                'city',
                'state',
                'country_code',
                'phone',
                'active',
                'website',
                'facebook',
                'logo_id'
            )
        );
     }
}
