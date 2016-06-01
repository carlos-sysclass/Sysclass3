<?php
namespace Sysclass\Models\Courses\Tests;

use Sysclass\Models\Courses\Lesson as BaseLesson;

class Lesson extends BaseLesson
{
    protected $assignedData = null;

    public function initialize()
    {
        parent::initialize();

        $this->setSource("mod_lessons");

        $this->hasOne(
            "id",
            "Sysclass\\Models\\Courses\\Tests\\Test",
            "id",
            array('alias' => 'Test')
        );
    }

    public function assign(array $data, $dataColumnMap = NULL, $whiteList = NULL) {
        $this->assignedData = $data;
        return parent::assign($data, $dataColumnMap, $whiteList);
    }


    public function afterSave() {
        // SAVE THE LINKED TEST
        if (array_key_exists('test', $this->assignedData) && is_array($this->assignedData['test'])) {
            $testModel = new Test();
            $testModel->assign($this->assignedData['test']);
            $testModel->id = $this->id;
            $testModel->save();
        }
        
    }

}
