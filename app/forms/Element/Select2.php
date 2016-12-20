<?php 
namespace Sysclass\Forms\Element;

use Phalcon\Forms\Element\Select,
    Sysclass\Forms\ElementInterface;

class Select2 extends Select implements ElementInterface
{
    // ElementInterface
    public function getHTMLType() {
        return "select2";
    }
    /* USED TO REVERSE THE PARAMETERS ORDER */    
    public function __construct($name, $attributes, array $options = []) {
        return parent::__construct($name, $options, $attributes);
    }

    public function render($attributes = null)
    {
    	$attr = $this->getAttributes();

    	if (!is_array($attributes)) {
    		$attributes = array();
    	}
    	$attr = array_merge($attr, $attributes);

        if (!(array_key_exists('class', $attr) && strpos($attr['class'], 'select2-me') !== FALSE)) {
            $attr['class'] .= ' select2-me';
        }

        /*
    	if (array_key_exists('class', $attr)) {
    		$attr['class'] .= ' select2-me';
    	} else {
    		$attr['class'] = 'select2-me';
    	}
        */
    	$attr['name'] = $this->getName();

    	$attr['data-placeholder'] = $this->getLabel();


    	$this->setAttributes($attr);
    	return parent::render();
    }

    public function toArray($attributes = null) {
        //var_dump($this->getUserOptions());
        //var_dump($this->getAttributes());
        //exit;
        return [
            'label'     => $this->getLabel(),
            'HTMLType'  => $this->getHTMLType(),
            'rendered'  => $this->render($attributes),
            'options'   => $this->getUserOptions()
        ];
    }
}