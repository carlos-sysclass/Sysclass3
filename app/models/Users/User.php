<?php
namespace Sysclass\Models\Users;

use Phalcon\Mvc\Model,
    Phalcon\Mvc\Model\Relation;

class User extends Model
{
    public function initialize()
    {
        $this->setSource("users");

        $this->hasOne("id", "Sysclass\\Models\\Users\\UserAvatar", "user_id",  array('alias' => 'avatar'));

        $this->hasManyToMany(
            "id",
            "Sysclass\\Models\\Users\\UserAvatar",
            "user_id", "file_id",
            "Sysclass\\Models\\Dropbox\\File",
            "id",
            array('alias' => 'Avatars', 'reusable' => true)
        );

    }

    public function getType() {
        return $this->user_type;
    }

    public function toFullArray($manyAliases = null) {
        $itemData = $this->toArray();

        if (is_array($manyAliases)) {
            foreach($manyAliases as $alias) {
                $alias = ucfirst(strtolower($alias));
                $methodName = "get{$alias}";

                $key = strtolower($alias);
                $itemRel = $this->{$methodName}();
                if ($itemRel) {
                    $itemData[$key] = $itemRel->toArray();
                } else {
                    $itemData[$key] = null;
                }

            }
        }

        $relations = $this->modelsManager->getRelations(__CLASS__);

        foreach($relations as $relation) {

            if ($relation->getType() == Relation::HAS_ONE) {
                $options = $relation->getOptions();

                if (array_key_exists('alias', $options)) {
                    $alias = $options['alias'];
                    $methodName = "get{$alias}";
                    $key = strtolower($alias);

                    $itemRel = $this->{$methodName}();
                    if ($itemRel) {
                        $itemData[$key] = $itemRel->toArray();
                    } else {
                        $itemData[$key] = null;
                    }
                }
            } else {

            }
        }

        return $itemData;
    }

}
