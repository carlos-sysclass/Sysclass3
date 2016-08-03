<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class UsersMigration_100
 */
class UsersMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('users', array(
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
                        'login',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 100,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'password',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 100,
                            'after' => 'login'
                        )
                    ),
                    new Column(
                        'backend',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "sysclass",
                            'notNull' => true,
                            'size' => 45,
                            'after' => 'password'
                        )
                    ),
                    new Column(
                        'locked',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'backend'
                        )
                    ),
                    new Column(
                        'email',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 150,
                            'after' => 'locked'
                        )
                    ),
                    new Column(
                        'name',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 100,
                            'after' => 'email'
                        )
                    ),
                    new Column(
                        'surname',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 100,
                            'after' => 'name'
                        )
                    ),
                    new Column(
                        'language_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'surname'
                        )
                    ),
                    new Column(
                        'birthday',
                        array(
                            'type' => Column::TYPE_DATE,
                            'size' => 1,
                            'after' => 'language_id'
                        )
                    ),
                    new Column(
                        'timezone',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 100,
                            'after' => 'birthday'
                        )
                    ),
                    new Column(
                        'short_description',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'timezone'
                        )
                    ),
                    new Column(
                        'can_be_instructor',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'short_description'
                        )
                    ),
                    new Column(
                        'can_be_coordinator',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'can_be_instructor'
                        )
                    ),
                    new Column(
                        'viewed_license',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'size' => 1,
                            'after' => 'can_be_coordinator'
                        )
                    ),
                    new Column(
                        'autologin',
                        array(
                            'type' => Column::TYPE_CHAR,
                            'size' => 32,
                            'after' => 'viewed_license'
                        )
                    ),
                    new Column(
                        'active',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'autologin'
                        )
                    ),
                    new Column(
                        'user_type',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "student",
                            'notNull' => true,
                            'size' => 50,
                            'after' => 'active'
                        )
                    ),
                    new Column(
                        'dashboard_id',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "default",
                            'notNull' => true,
                            'size' => 25,
                            'after' => 'user_type'
                        )
                    ),
                    new Column(
                        'comments',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'dashboard_id'
                        )
                    ),
                    new Column(
                        'pending',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'comments'
                        )
                    ),
                    new Column(
                        'user_types_ID',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'size' => 8,
                            'after' => 'pending'
                        )
                    ),
                    new Column(
                        'last_login',
                        array(
                            'type' => Column::TYPE_TIMESTAMP,
                            'default' => "CURRENT_TIMESTAMP",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'user_types_ID'
                        )
                    ),
                    new Column(
                        'api_secret_key',
                        array(
                            'type' => Column::TYPE_CHAR,
                            'size' => 64,
                            'after' => 'last_login'
                        )
                    ),
                    new Column(
                        'websocket_key',
                        array(
                            'type' => Column::TYPE_CHAR,
                            'size' => 64,
                            'after' => 'api_secret_key'
                        )
                    ),
                    new Column(
                        'reset_hash',
                        array(
                            'type' => Column::TYPE_CHAR,
                            'size' => 64,
                            'after' => 'websocket_key'
                        )
                    ),
                    new Column(
                        'cnpj',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'reset_hash'
                        )
                    ),
                    new Column(
                        'phone',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'cnpj'
                        )
                    ),
                    new Column(
                        'how_did_you_know',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'phone'
                        )
                    ),
                    new Column(
                        'is_supplier',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'how_did_you_know'
                        )
                    ),
                    new Column(
                        'supplier_name',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'is_supplier'
                        )
                    ),
                    new Column(
                        'postal_code',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'supplier_name'
                        )
                    ),
                    new Column(
                        'country',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'postal_code'
                        )
                    ),
                    new Column(
                        'street',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'country'
                        )
                    ),
                    new Column(
                        'street_number',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'street'
                        )
                    ),
                    new Column(
                        'district',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'street_number'
                        )
                    ),
                    new Column(
                        'city',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'district'
                        )
                    ),
                    new Column(
                        'state',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 150,
                            'after' => 'city'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY'),
                    new Index('login', array('login'), 'UNIQUE'),
                    new Index('active', array('active'), null),
                    new Index('email', array('email'), null),
                    new Index('name', array('name'), null),
                    new Index('surname', array('surname'), null)
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
        $this->batchInsert('users', array(
                'id',
                'login',
                'password',
                'backend',
                'locked',
                'email',
                'name',
                'surname',
                'language_id',
                'birthday',
                'timezone',
                'short_description',
                'can_be_instructor',
                'can_be_coordinator',
                'viewed_license',
                'autologin',
                'active',
                'user_type',
                'dashboard_id',
                'comments',
                'pending',
                'user_types_ID',
                'last_login',
                'api_secret_key',
                'websocket_key',
                'reset_hash',
                'cnpj',
                'phone',
                'how_did_you_know',
                'is_supplier',
                'supplier_name',
                'postal_code',
                'country',
                'street',
                'street_number',
                'district',
                'city',
                'state'
            )
        );
     }
}
