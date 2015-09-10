<?php
namespace Plico\Mvc;

use Phalcon\Mvc\Model\Relation;

class Model extends \Phalcon\Mvc\Model
{
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

        $relations = $this->modelsManager->getRelations(get_class($this));

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
            } elseif ($relation->getType() == Relation::BELONGS_TO) {
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
            }
        }

        return $itemData;
    }

}
