<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ModLessonsMigration_101
 */
class ModLessonsMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('mod_lessons', array(
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
                        'class_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 8,
                            'after' => 'permission_access_mode'
                        )
                    ),
                    new Column(
                        'name',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 150,
                            'after' => 'class_id'
                        )
                    ),
                    new Column(
                        'info',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'name'
                        )
                    ),
                    new Column(
                        'position',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'info'
                        )
                    ),
                    new Column(
                        'active',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'position'
                        )
                    ),
                    new Column(
                        'type',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "lesson",
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'active'
                        )
                    ),
                    new Column(
                        'has_text_content',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'type'
                        )
                    ),
                    new Column(
                        'text_content',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'has_text_content'
                        )
                    ),
                    new Column(
                        'text_content_language_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'size' => 11,
                            'after' => 'text_content'
                        )
                    ),
                    new Column(
                        'has_video_content',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'size' => 1,
                            'after' => 'text_content_language_id'
                        )
                    ),
                    new Column(
                        'subtitle_content_language_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'size' => 11,
                            'after' => 'has_video_content'
                        )
                    ),
                    new Column(
                        'instructor_id',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'subtitle_content_language_id'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY'),
                    new Index('class_id', array('class_id'), null)
                ),
                'references' => array(
                    new Reference(
                        'mod_lessons_ibfk_1',
                        array(
                            'referencedSchema' => 'sysclass_clean',
                            'referencedTable' => 'mod_classes',
                            'columns' => array('class_id'),
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
        $this->batchInsert('mod_lessons', array(
                'id',
                'permission_access_mode',
                'class_id',
                'name',
                'info',
                'position',
                'active',
                'type',
                'has_text_content',
                'text_content',
                'text_content_language_id',
                'has_video_content',
                'subtitle_content_language_id',
                'instructor_id'
            )
        );
     }
}
