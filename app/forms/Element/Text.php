<?php 
namespace Sysclass\Forms\Element;

use Phalcon\Forms\Element\Text as PhalconText,
	Sysclass\Forms\ElementInterface;

class Text extends PhalconText implements ElementInterface
{
    // ElementInterface
    public function getHTMLType() {
        return "text";
    }

    public function toArray($attributes = null) {
        return [
            'label'     => $this->getLabel(),
            'HTMLType'  => $this->getHTMLType(),
            'rendered'  => $this->render($attributes),
            'options'   => $this->getUserOptions()
        ];
    }

}