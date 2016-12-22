<?php
namespace Plico\Mvc;

use Phalcon\DI,
    Phalcon\Mvc\Model\Relation;

class Model extends \Phalcon\Mvc\Model
{
    protected static $_translate = false;
    protected static $_translateFields = [];

    public function translate() {
        static::$_translate = true;

        if (count(static::$_translateFields) > 0) {
            //var_dump(get_class($this), static::$_translateFields);
            $DepInject = DI::getDefault();
            $translator = $DepInject->get("translate");
            foreach(static::$_translateFields as $fieldKey) {
                $this->{$fieldKey} = $translator->translate($this->{$fieldKey});
            }

        }

        return $this;
    }
    public function toAdditionalArray($relAliases = null, $itemData = null, $extended = false) {

        if (is_null($itemData)) {
            $itemData = $this->toArray();    
        }
        
        if (is_array($relAliases)) {
            foreach($relAliases as $index => $alias) {
                if (!array_key_exists($alias, $itemData)) {
                    $alias = ucfirst(strtolower($alias));
                    $methodName = "get{$alias}";

                    if (is_numeric($index)) {
                        $key = strtolower($alias);
                    } else {
                        $key = $index;
                    }
                    $itemRel = $this->{$methodName}();

                    $exportMethod = "toArray";
                    if ($extended) {
                        $exportMethod = "toFullArray";
                    }

                    if (get_class($itemRel) == 'Phalcon\Mvc\Model\Resultset\Simple') {
                        $itemData[$key] = array();
                        foreach($itemRel as $sub) {
                            if (static::$_translate) {
                                $sub->translate();
                            }
                            $itemData[$key][] = $sub->{$exportMethod}();
                        }
                    } else {
                        if ($itemRel) {
                            if (static::$_translate) {
                                $itemRel->translate();
                            }
                            $itemData[$key] = $itemRel->{$exportMethod}();
                        } else {
                            $itemData[$key] = null;
                        }
                    }
                }

            }
        }

        return $itemData;
    }

    public function getItemRel($method) {
        /*
        if (strpos($method, ".")) {
            $chainMethods = explode(".", $method);
            $method = ucfirst(strtolower(reset($chainMethods)));
        } else {
        */
            $chainMethods = [$method];
            $method = ucfirst(strtolower($method));    
        //}
        $methodName = "get{$method}";
        $itemRel = $this->{$methodName}();
        /*
        if (count($chainMethods) > 1) {
            var_dump($chainMethods);

            return $itemRel->getItemRel(array_shift($chainMethods));
        }
        */
        return $itemRel;

    }
    public function toFullArray($manyAliases = null, $itemData = null, $extended = false) {

//        var_dump($manyAliases);

        if (is_null($itemData)) {
            $itemData = $this->toArray();    
        }
        
        if (is_array($manyAliases)) {
            foreach($manyAliases as $index => $alias) {

                if (is_array($alias)) {
                    $exportArgs = $alias;
                    $exportMethod = "toFullArray";
                    $itemRel = $this->getItemRel($index);
                } else {
                    $itemRel = $this->getItemRel($alias);    

                    $exportMethod = "toArray";
                    if ($extended) {
                        $exportMethod = "toFullArray";
                    }
                }
                

                /*
                if (strpos($alias, ".")) {
                    $chainMethods = explode(".", $alias);
                    $alias = ucfirst(strtolower(reset($chainMethods)));
                } else {
                    $chainMethods = [$alias];
                    $alias = ucfirst(strtolower($alias));    
                }
                
                $methodName = "get{$alias}";
                */
                if (is_numeric($index)) {
                    $key = strtolower($alias);
                } else {
                    $key = strtolower($index);
                }

                //$itemRel = $this->{$methodName}();

                
                //print_r(get_class($itemRel));


                if (get_class($itemRel) == 'Phalcon\Mvc\Model\Resultset\Simple') {
                    $itemData[$key] = array();
                    foreach($itemRel as $sub) {
                        if (static::$_translate && method_exists([$sub, 'translate'])) {
                            $sub->translate();
                        }
                        print_r($index, $exportMethod, $exportArgs);
                        $itemData[$key][] = call_user_func(array($sub, $exportMethod), $exportArgs);
                    }
                } else {
                    if ($itemRel) {
                        if (static::$_translate && method_exists([$itemRel, 'translate'])) {
                            $itemRel->translate();
                        }
                        $itemData[$key] = $itemRel->{$exportMethod}();
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
                        if (static::$_translate && method_exists([$itemRel, 'translate'])) {
                            $itemRel->translate();
                        }
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

                    $exportMethod = "toArray";
                    if ($extended) {
                        $exportMethod = "toFullArray";
                    }
                    
                    if (get_class($itemRel) == 'Phalcon\Mvc\Model\Resultset\Simple') {
                        $itemData[$key] = array();
                        foreach($itemRel as $sub) {
                            if (static::$_translate && method_exists([$sub, 'translate'])) {
                                $sub->translate();
                            }
                            $itemData[$key][] = $sub->{$exportMethod}();
                        }
                    } else {
                        if ($itemRel) {
                            if (static::$_translate && method_exists([$itemRel, 'translate'])) {
                                $itemRel->translate();
                            }
                            $itemData[$key] = $itemRel->{$exportMethod}();
                        } else {
                            $itemData[$key] = null;
                        }
                    }

                    /*
                    if ($extended) {
                        if (get_class($itemRel) == 'Phalcon\Mvc\Model\Resultset\Simple') {
                            $itemData[$key] = array();
                            foreach($itemRel as $sub) {
                                if ($this->_translate) {
                                    $sub->translate();
                                }
                                $itemData[$key][] = $sub->toFullArray();
                            }
                        } else {
                            //var_dump($key, get_class($itemRel));
                            if ($this->_translate) {
                                $itemRel->translate();
                            }
                            $itemData[$key] = $itemRel->toFullArray();
                        }
                    } else {
                        if ($itemRel) {
                            if ($this->_translate) {
                                $itemRel->translate();
                            }

                            $itemData[$key] = $itemRel->toArray();
                        } else {
                            $itemData[$key] = null;
                        }
                    }
                    */
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
