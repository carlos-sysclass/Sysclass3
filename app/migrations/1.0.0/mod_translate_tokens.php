<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ModTranslateTokensMigration_100
 */
class ModTranslateTokensMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('mod_translate_tokens', array(
                'columns' => array(
                    new Column(
                        'language_code',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 10,
                            'first' => true
                        )
                    ),
                    new Column(
                        'token',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 757,
                            'after' => 'language_code'
                        )
                    ),
                    new Column(
                        'text',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 980,
                            'after' => 'token'
                        )
                    ),
                    new Column(
                        'edited',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'unsigned' => true,
                            'size' => 1,
                            'after' => 'text'
                        )
                    ),
                    new Column(
                        'timestamp',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'size' => 10,
                            'after' => 'edited'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('language_code', 'token'), 'PRIMARY')
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
        $this->batchInsert('mod_translate_tokens', array(
                'language_code',
                'token',
                'text',
                'edited',
                'timestamp'
            )
        );
     }
}
