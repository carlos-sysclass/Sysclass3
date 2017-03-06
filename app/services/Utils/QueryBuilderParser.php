<?php
namespace Sysclass\Services\Utils;

use Phalcon\Mvc\User\Component;

class QueryBuilderParser extends Component {
    protected $operators = [];

    public function initialize() {
        $this->operators = array(
            'equal' => "=", 
            'not_equal' => "!=",
            'in' => "IN ({_REP_:array})",
            'not_in' => "NOT IN (_REP_:array)", 
            'less' => "<", 
            'less_or_equal' => "<=", 
            'greater' => ">", 
            'greater_or_equal' => ">=",
            'begins_with' => "LIKE",
            'not_begins_with' => "NOT LIKE",
            'contains' => "LIKE",
            'not_contains' => "NOT LIKE",
            'ends_with' => "LIKE",
            'not_ends_with' => "NOT LIKE",
            'is_empty' => "=''",
            'is_not_empty' => "!=''", 
            'is_null' => "IS NULL", 
            'is_not_null' => "IS NOT NULL"
        );
    }
    public function parse($rules) {
        /*
        echo "<pre>";
        print_r($rules);
        */
        
        $this->conditions = "";
        $this->bind = [];

        $jsonResult = array("data" => array());
        $getAllResults = false;
        $conditions = null;
        $result = "";
        $params = array();
        if (is_string($rules)) {
            $conditions = json_decode(utf8_encode($rules), true);    
        } else {
            $conditions = $rules;
        }

        if(!array_key_exists('condition', $conditions)) {
            $getAllResults = true;
        } else {

            $global_bool_operator = $conditions['condition'];

            // i contatori servono per evitare di ripetere l'operatore booleano
            // alla fine del ciclo se non ci sono più condizioni
            $counter = 0;
            $total = count($conditions['rules']);

            foreach($conditions['rules'] as $index => $rule) {
                if(array_key_exists('condition', $rule)) {
                    $result .= $this->parseGroup($rule);
                    $total--;
                    if($counter < $total)
                       $result .= " $global_bool_operator ";
                } else {
                    $result .= $this->parseRule($rule);
                    $total--;
                    if($counter < $total)
                       $result .= " $global_bool_operator ";
                }
            }
        }
        $this->conditions = $result;

        return [
            'conditions' => $this->conditions,
            'bind' => $this->bind
        ];
    }

    protected function newBindName() {
        return "b" . $this->random->base58(4);
    }

    /**
     * Parse a group of conditions 
     */
    protected function parseGroup($rule, &$param) {
        $parseResult = "(";
        $bool_operator = $rule['condition'];
        // counters to avoid boolean operator at the end of the cycle 
        // if there are no more conditions
        $counter = 0;
        $total = count($rule['rules']);

        foreach($rule['rules'] as $i => $r) {
            if(array_key_exists('condition', $r)) {
                $parseResult .= "\n".$this->parseGroup($r);
            } else {
                $parseResult .= $this->parseRule($r);
                $total--;
                if($counter < $total)
                    $parseResult .= " ".$bool_operator." ";
            }
        }

      return $parseResult.")";
    }

    /**
     * Parsing of a single condition 
     */
    protected function parseRule($rule) {

        $parseResult = "";
        $parseResult .= $rule['field']." ";

        if($this->isLikeOp($rule['operator'])) {

            $parseResult .= $this->parseLikeRule($rule['operator'], $rule['value']);

        } elseif($this->isNoValueOp($rule['operator'])) {

            $parseResult .= $this->operators[$rule['operator']];

        } elseif($this->isMultiValueOp($rule['operator'])) {
            $values = explode(', ', $rule['value']);

            $bindName = $this->newBindName();

            $this->bind[$bindName] = $values;
            //$index = count($this->bind)-1;

            $parseResult .= str_replace("_REP_", $bindName, $this->operators[$rule['operator']]);
        } else {
            $bindName = $this->newBindName();

            $this->bind[$bindName] = $rule['value'];

            $index = count($this->bind)-1;
            $parseResult .= $this->operators[$rule['operator']]." {" . $bindName . "}";
            //$parseResult .= $this->operators[$rule['operator']]." ?" . $index;

        }
        return $parseResult;
    }

    protected function parseLikeRule($operator, $value) {
        $bindName = $this->newBindName();
        switch($operator) {
            case 'begins_with':
            case 'not_begins_with': {
                $this->bind[$bindName] = $value . "%";
                break;
            }
            case 'contains':
            case 'not_contains': {
                $this->bind[$bindName] = '%' . $value . "%";
                break;
            }
            case 'ends_with':
            case 'not_ends_with': {
                $this->bind[$bindName] = "%" . $value;
                break;
            }
            default: {
                $this->bind[$bindName] = $value;
            }
        }
        //$index = count($this->bind)-1;
        return $this->operators[$operator]." {" . $bindName . "}";
    }

    protected function isLikeOp($operator) {
        $like_operators = [
            'begins_with',
            'not_begins_with',
            'contains',
            'not_contains',
            'ends_with',
            'not_ends_with'
        ];
        if (in_array($operator, $like_operators)) {
            return true;
        }
        return false;
    }

    protected function isNoValueOp($operator) {
        $noValue_operators = [
            'is_empty',
            'is_not_empty',
            'is_null',
            'is_not_null'
        ];
        if (in_array($operator, $noValue_operators)) {
            return true;
        }
        return false;
    }

    protected function isMultiValueOp($operator) {
        $noValue_operators = [
            'in',
            'not_in'
        ];
        if (in_array($operator, $noValue_operators)) {
            return true;
        }
        return false;
    }



    
}



