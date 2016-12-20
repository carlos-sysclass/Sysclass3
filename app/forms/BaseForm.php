<?php
namespace Sysclass\Forms;

use Phalcon\Forms\Form,
	Sysclass\Forms\Element\Hidden,
	Sysclass\Forms\Element\Select2,
	ReflectionClass;

class BaseForm extends Form
{
    public function initialize($entity, $info) {

    	// CREATE CSRF TOKEN
    	/*
		$this->add(new Hidden(
			$this->security->getTokenKey(),
			array(
				'value' => $this->security->getToken()
			)
		));


		// CREATE THE INFO FOR SELECTED PACKAGE
		$this->add(new Hidden(
			'_package_id',
			array(
				'value' => $info->identifier
			)
		));
		
    	// CREATE THE COURSE COMBO, THE SELECT FIELDS AND CREATE THE RULES FOR CLIENT AND SERVER VALIDATION
    	
    	$selectOpt = array();

    	foreach($info->courses as $opt) {
    		$selectOpt[$opt->course_id] = $opt->course->name;
    	}

    	if (count($selectOpt) > 0) {
	
	    	$el = new Select2(
		        "courses",
		        array(
		        	'multiple' => 'multiple',
		        	'data-rule-required' => true
		        ),
		        $selectOpt
		    );
	    	$el->setLabel($info->labels->choose_program);
	    	$this->add($el);

    	}
		*/
    	foreach($info['fields'] as $enrollfield) {
    		if ($enrollfield['field']['active']) {
    			$field = $enrollfield['field'];

				$attributes = array(
		        	'placeholder' => $enrollfield['label'],
		        	'class' => "form-control",
		        	'data-helper' => $field['type']['helper_class'],
		        	'data-binding-raw' => "true"
		        );
				if ($enrollfield['required'] == 1 && $enrollfield['required_time'] == 0) {
					$attributes['data-rule-required'] = "true";
				}

			
				if ($enrollfield['options']) {
					$fieldOptions = array();
					foreach($enrollfield['options'] as $opt) {
						$fieldOptions[$opt['value']] = $opt['label'];
					}
					/**
					 * SMALL HACK FOR SELECTABLE ELEMENTS, BUT THE MULTIPLE ATTR HAVE BE GETTED FROM THE ENROLL API
					 */
					//if (in_array($field->name, array('how_did_you_know'))) {
					//	$attributes['multiple'] = 'multiple';
					//} else {
						$attributes['useEmpty'] = true;
						$attributes['emptyText'] = "";
					//}
				}


				$className = sprintf('Sysclass\Forms\Element\%s', \Phalcon\Text::camelize($field['type']['name']));

				$args = array();

				$args[] = $field['name'];
				$args[] = $attributes;

				if (count($fieldOptions) > 0) {
					$args[] = $fieldOptions;
					$fieldOptions = array();
				}

				//var_dump($args);

				$reflect  = new ReflectionClass($className);
    			$el = $reflect->newInstanceArgs($args);

		    	$el->setLabel($enrollfield['label']);
		    	$el->setUserOption('weight', $enrollfield['weight']);
		    	$this->add($el);
    		}
    	}
   	}
}