<?php 
namespace Sysclass\Forms\Element;

use Phalcon\Forms\Element\Hidden as PhalconHidden,
	Sysclass\Forms\ElementInterface;

class Hidden extends PhalconHidden implements ElementInterface
{
    // ElementInterface
    public function getHTMLType() {
        return "hidden";
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