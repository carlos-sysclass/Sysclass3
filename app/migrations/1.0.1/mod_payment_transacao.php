<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ModPaymentTransacaoMigration_101
 */
class ModPaymentTransacaoMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('mod_payment_transacao', array(
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
                        'payment_itens_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'size' => 8,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'descricao',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'payment_itens_id'
                        )
                    ),
                    new Column(
                        'token',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 100,
                            'after' => 'descricao'
                        )
                    ),
                    new Column(
                        'status',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 15,
                            'after' => 'token'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY'),
                    new Index('fk_mod_payment_transacao_1_idx', array('payment_itens_id'), null)
                ),
                'references' => array(
                    new Reference(
                        'fk_mod_payment_transacao_1',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'mod_payment_itens',
                            'columns' => array('payment_itens_id'),
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
                    'TABLE_COLLATION' => 'latin1_swedish_ci'
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
        $this->batchInsert('mod_payment_transacao', array(
                'id',
                'payment_itens_id',
                'descricao',
                'token',
                'status'
            )
        );
     }
}
