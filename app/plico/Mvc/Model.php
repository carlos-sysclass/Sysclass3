<?php
namespace Plico\Mvc;

use Phalcon\DI,
    Phalcon\Mvc\Model\Relation;

class Model extends \Phalcon\Mvc\Model
{
    public function toFullArray($manyAliases = null, $itemData = null, $extended = false) {

        if (is_null($itemData)) {
            $itemData = $this->toArray();    
        }
        
        if (is_array($manyAliases)) {
            foreach($manyAliases as $alias) {
                $alias = ucfirst(strtolower($alias));
                $methodName = "get{$alias}";

                $key = strtolower($alias);
                $itemRel = $this->{$methodName}();

                if ($extended) {
                    if (get_class($itemRel) == 'Phalcon\Mvc\Model\Resultset\Simple') {
                        $itemData[$key] = array();
                        foreach($itemRel as $sub) {
                            $itemData[$key][] = $sub->toFullArray();
                        }
                    } else {
                        $itemData[$key] = $itemRel->toFullArray();
                    }
                } else {
                    if ($itemRel) {
                        $itemData[$key] = $itemRel->toArray();
                    } else {
                        $itemData[$key] = null;
                    }
                }

            }
        }

        $relations = $this->modelsManager->getRelations(get_class($this));

        $DepInject = DI::getDefault();

        foreach($relations as $relation) {

            if ($relation->getType() == Relation::HAS_ONE) {
                $options = $relation->getOptions();

                if (array_key_exists('alias', $options)) {
                    $alias = $options['alias'];
                    $methodName = "get{$alias}";
                    $key = $DepInject->get("stringsHelper")->camelDiscasefying($alias);

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
                    $key = $DepInject->get("stringsHelper")->camelDiscasefying($alias);

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


    public function toExtendArray($manyAliases, $itemData = null) {
        return $this->toFullArray($manyAliases, $itemData, true);
    }

    public static function findConnectBy($params) {

        if (!array_key_exists('connect_by', $params)) {
            throw new Exception("The parameter 'connect_by' can not be null!");
        }

        $resultset = parent::find($params);

        $connectByField = $params['connect_by'];

        $result = array();
        foreach($resultset as $item) {
            $connectByValue = $item->{$connectByField};
            if (!array_key_exists($connectByValue, $result)) {
                $result[$connectByValue] = array();
            }
            $result[$connectByValue][] = $item->toArray();
        }
        return $result;
        
    }

}
