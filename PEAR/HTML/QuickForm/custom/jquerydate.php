<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * HTML class for a jquerydate field
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    HTML
 * @package     HTML_QuickForm
 * @author      Adam Daniel <adaniel1@eesus.jnj.com>
 * @author      Bertrand Mansion <bmansion@mamasam.com>
 * @copyright   2001-2009 The PHP Group
 * @license     http://www.php.net/license/3_01.txt PHP License 3.01
 * @version     CVS: $Id: jquerydate.php,v 1.7 2009/04/04 21:34:04 avb Exp $
 * @link        http://pear.php.net/package/HTML_QuickForm
 */

/**
 * Base class for <input /> form elements
 */
require_once 'HTML/QuickForm/text.php';

/**
 * HTML class for a jquerydate field
 *
 * @category    HTML
 * @package     HTML_QuickForm
 * @author      Adam Daniel <adaniel1@eesus.jnj.com>
 * @author      Bertrand Mansion <bmansion@mamasam.com>
 * @version     Release: 3.2.11
 * @since       1.0
 */
class HTML_QuickForm_jquerydate extends HTML_QuickForm_text
{
	var $options = array(
		'outputFormat' => 'd/m/Y'
	);

    /**
     * The javascript used to set and change the options
     *
     * @var       string
     * @access    private
     */
    var $_js = '';

    // {{{ constructor

    /**
     * Class constructor
     *
     * @param     string    $elementName    (optional)Input field name attribute
     * @param     string    $elementLabel   (optional)Input field label
     * @param     mixed     $attributes     (optional)Either a typical HTML attribute string
     *                                      or an associative array
     * @since     1.0
     * @access    public
     * @return    void
     */
    function HTML_QuickForm_jquerydate($elementName=null, $elementLabel=null, $attributes=null)
    {
    	parent::HTML_QuickForm_text($elementName, $elementLabel, $attributes);

		$this->updateAttributes( array('alt'=>'date') );
    } //end constructor
	// }}}

    function setValue($value)
    {
    	// VALUE IS ISO DATE
    	switch ($GLOBALS['configuration']['date_format']) {
			case "YYYY/MM/DD": {
				$date_format = 'Y/m/d'; break;
			}
			case "MM/DD/YYYY": {
				$date_format = 'm/d/Y'; break;
			}
			case "DD/MM/YYYY":
			default: {
				$date_format = 'd/m/Y'; break;
				$scanf_format = '%02d/%02d/%04d';
			}
		}

		$valueObject = date_create_from_format($date_format, $value);
		if (!$valueObject) {
			$valueObject = date_create_from_format('Y-m-d', $value);
		}

     	if ($valueObject) {
		   	$valueText =  $valueObject->format($date_format);
		   	parent::setValue($valueText);
		}
    }
    /*
    function toHtml()
    {
		// create the js function to call
		if (!defined('HTML_QUICKFORM_JQUERYDATE_EXISTS')) {
			$this->_js .= <<<JAVASCRIPT
console.log('dasdas');
alert('dasdas');
JAVASCRIPT;
			define('HTML_QUICKFORM_JQUERYDATE_EXISTS', true);
			}

		include_once 'HTML/QuickForm/Renderer/Default.php';
   	    $renderer = new HTML_QuickForm_Renderer_Default();
        $renderer->setElementTemplate('{element}');
		parent::accept($renderer);

		return (empty($this->_js)? '': "<script type=\"text/javascript\">\n//<![CDATA[\n" . $this->_js . "//]]>\n</script>") . $renderer->toHtml();
    }
    */
} //end class HTML_QuickForm_jquerydate
