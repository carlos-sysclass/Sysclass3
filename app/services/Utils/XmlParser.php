<?php
namespace Sysclass\Services\Utils;

use Phalcon\Mvc\User\Component;

class XmlParser extends Component
{
    protected static $cfg;
    public function __construct() {
    }

    public function parseDoc($string, $ns) {
        $result = [];
        //echo "<pre>";
        //echo(htmlentities($string));
        $xmlRoot = simplexml_load_string($string);

        return $this->parseElement($xmlRoot, $ns);
    }

    public function parseElement($el, $namespaces) {
        $result = [];

        foreach($namespaces as $ns) {
            foreach((array) $el as $index => $node) {
                $result[$index] = ( is_object($node) || is_array($node) ) ? $this->parseElement( $node, $namespaces ) : $node;
            }

            if (is_object($el)) {

                $children = $el->children($ns, true);
                foreach((array) $children as $index => $node) {
                    if (is_object($node) || is_array($node)) {
                        if (!array_key_exists($ns . ":" . $index, $result)) {
                            $result[$index] = array();
                        }
                        $result[$index] = $this->parseElement( $node, $namespaces );
                    } else {
                        $result[$index] = $node;
                    }
                }
            }
        }

        return $result;
    }

}
