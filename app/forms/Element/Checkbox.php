<?php 
namespace Sysclass\Forms\Element;

use Phalcon\Forms\Element\Check,
    Sysclass\Forms\ElementInterface;

class Checkbox extends Check implements ElementInterface
{
    // ElementInterface
    public function getHTMLType() {
        return "checkbox";
    }

	public function render($attributes = null)
    {
        $translator = \Phalcon\DI::getDefault()->get("translate");

        $attr = $this->getAttributes();
        if (!is_array($attributes)) {
            $attributes = array();
        }
        $attr = array_merge($attr, $attributes);

        if (!(array_key_exists('class', $attr) && strpos($attr['class'], 'bootstrap-switch-me') !== FALSE)) {
            $attr['class'] .= ' bootstrap-switch-me';
        }

        if (!array_key_exists('data-wrapper-class', $attr)) {
            $attr['data-wrapper-class'] = "block";
        }
        if (!array_key_exists('data-size', $attr)) {
            $attr['data-size'] = "small";
        }
        if (!array_key_exists('data-on-color', $attr)) {
            $attr['data-on-color'] = "success";
        }
        if (!array_key_exists('data-off-color', $attr)) {
            $attr['data-off-color'] = "danger";
        }
        if (!array_key_exists('data-on-text', $attr)) {
            $attr['data-on-text'] = $translator->translate("ON");
        }
        if (!array_key_exists('data-off-text', $attr)) {
            $attr['data-off-text'] = $translator->translate("OFF"); 
        }

        $this->setAttributes($attr);
    	//$attributes['class'] = 'rem-checkbox';
    	$html = parent::render($attributes);

    	return $html;
        /*
        '<input data-wrapper-class="" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">'
        */


    }

    public function toArray($attributes = null) {
        return [
            'label'  => $this->getLabel(),
            'HTMLType'  => $this->getHTMLType(),
            'rendered'  => $this->render($attributes),
            'options'   => $this->getUserOptions()
        ];
    }



}