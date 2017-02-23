<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model,
    Sysclass\Models\Users\User,
    Sysclass\Models\Content\ContentFile;

class UnitContent extends Model
{
    protected $assignedData = null;

    public function initialize()
    {
        $this->setSource("mod_units_content");

        $this->belongsTo(
            "unit_id",
            "Sysclass\Models\Content\Unit",
            "id",
            array("alias" => 'Unit')
        );
        
		$this->hasManyToMany(
            "id",
            "Sysclass\Models\Content\ContentFile",
            "content_id", "file_id",
            "Sysclass\Models\Dropbox\File",
            "id",
            array('alias' => 'Files')
        );

        $this->hasOne(
            "id",
            "Sysclass\Models\Content\Progress\Content",
            "content_id",
            array('alias' => 'Progress')
        );
    }

    public function afterFetch() {
        $this->tags = json_decode($this->tags, true);
    }

    public function beforeSave() {
        $this->tags = json_encode($this->tags);
    }

    public function toArray() {
        if (!is_array($this->tags) && !empty($this->tags)) {
            $this->tags = json_decode($this->tags, true);       
        }

        return parent::toArray();

    }

    public function assign(array $data, $dataColumnMap = NULL, $whiteList = NULL) {
        $this->assignedData = $data;
        return parent::assign($data, $dataColumnMap, $whiteList);
    }

    public function afterSave() {
        // SAVE THE LINKED TEST
        if (array_key_exists('files', $this->assignedData) && is_array($this->assignedData['files'])) {

            foreach($this->assignedData['files'] as $file) {
                $contentFileModel = new ContentFile();

                $contentFileModel->assign([
                    'content_id' => $this->id,
                    'file_id' => $file['id'],
                    'active' => 1
                ]);

                $contentFileModel->addOrUpdate();
            }
        }
    }



    public function toFullContentArray() {
        // GRAB FILES AND OTHER INFO
        $item = $this->toArray();
        $files = $this->getFiles(array(
            'conditions' => 'Sysclass\Models\Content\ContentFile.active = 1'
        ));

        $item['files'] = [];
        foreach($files as $file) {
            $item['files'][] = $file->toFullArray();
        }

        $file = $files->getFirst();
        if ($file) {
            $item['file'] = $files->getFirst()->toArray();
        } else {
            $item['file'] = array();
        }
        

        return $item;
    }

    public function getFullTree(User $user = null, $only_active = false) {
        $result = $this->toFullContentArray();
        $result['info'] = json_decode($result['info']);
        
        $user_id = $this->getDI()->get("user")->id;

        $progress = $this->getProgress(array(
            'conditions' => "user_id = ?0",
            'bind' => array($user_id)
        ));


        if ($progress) {
            $result['rating'] = $progress->average([
                "conditions" => "rating >= 0 and content_id = ?0",
                "bind" => [$this->id],
                'column' => "rating"
            ]);

            $result['progress'] = $progress->toArray();   
            $result['progress']['factor'] = floatval($result['progress']['factor']);
        } else {
            $result['progress'] = array(
                'factor' => 0
            );
        }

        return $result;
    }
}

