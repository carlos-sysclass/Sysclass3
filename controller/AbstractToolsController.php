<?php
abstract class AbstractToolsController extends PhalconWrapperController
{
	protected $config;

    // CREATE FUNCTION HERE
    public function loadLayout($layout_id = 'default', $use_cache = true) {
        $this->layout_id = $layout_id;

        $this->config = $this->loadConfig($use_cache);

        if (@$this->config['dashboard']['merge_resource_with_modules']) {
            $modules = $this->getModules();

            $modules_keys = array_map('strtolower', array_keys($modules));
            foreach($this->config['dashboard']['resources'] as $index => $resource) {
                $this->config['dashboard']['resources'][$index] = array_unique(array_merge($resource, $modules_keys));
            }
        }

        return $this->layoutSpec = $this->config['dashboard'];
    }

    public function layoutExists($layout_id) {
        $config = yaml_parse_file(__DIR__ . "/../config/" . $layout_id . ".yml");

        return (bool) $config;
    }

    protected function loadConfig($use_cache = true) {
        //$cached = $this->getCache("dashboard/{$this->layout_id}");

        //if (is_null($cached) || $use_cache == false) {

            $defaultconfig = yaml_parse_file(__DIR__ . "/../config/default.yml");

            $config = yaml_parse_file(__DIR__ . "/../config/" . $this->layout_id . ".yml");

            $this->config = array_replace_recursive($defaultconfig, $config);

        //    $this->setCache("dashboard/{$this->layout_id}", $this->config);
        //} else {
        //    $this->config = $cached;
        //}
        return $this->config;
    }

    protected function getResource($resourceID) {
        return $this->config['global']['resources'][$resourceID];
    }

    // TODO REVIEW MENUS... MUST APPPER ON ALL PAGES
    public function getMenuBySection($section) {
        // LOAD MENUS
        $modules = array();
        // GET ALL MODULES, CHECK FOR IMenu Interface, CHECK FOR SECTION
        $modules = $this->getModules("ISectionMenu");

        


        $menu_items = array();
        foreach($modules as $index => $module) {
            $menu_item = $module->getSectionMenu($section);

            if ($menu_item) {
                $menu_items[$index] = $menu_item;
            }
        }

        $menu_items = $this->sortModules("layout.sections." . $section, $menu_items, true);

        return $menu_items;
    }

    public function sortModules($sortId, $data, $preserveUncontainedKey = false) {
        $resource = $this->getResource($sortId);

        $dataArray = array();
        if ($resource) {
            foreach($resource as $sortIndex) {
                if (array_key_exists($sortIndex, $data)) {
                    $dataArray[$sortIndex] = $data[$sortIndex];
                    unset($data[$sortIndex]);
                }
            }

	        if (strlen($preserveUncontainedKey) > 0 && count($data) > 0) {
	            $dataArray[$preserveUncontainedKey] = array();
	            foreach($data as $key => $uncontained) {
	                $dataArray[$preserveUncontainedKey] = array_merge($dataArray[$preserveUncontainedKey], $uncontained);
	            }
	        }
    	} else {
        	$dataArray = $data;
    	}
        return $dataArray;
    }

	/* OLD FUNCTION WRAPPERS
	/**
	 * Check a parameter against a type
	 *
	 * This function accepts a parameter and a type. It then checks the parameter against a regular expression corresponding
	 * to the type specified. If the regular expression is met, then the parameter is returned. Otherwise, false is returned
	 * Supported types are:<br>
	 * - string: Only characters, [A-Za-a]
	 * - uint: Only positive numbers or zero, [0-9]
	 * - id: Alias for uint
	 * - login: Valid login names are made of alphanumeric characters and @, no spaces
	 * - email: Valid email address
	 * - filename: Valid filenames must not include special characters, such as /,\,..
	 * - hex: Hexadecimal number
	 * - alnum: Alphanumeric characters, [A-Za-z0-9]
	 * - alnum_with_spaces: Alphanumeric characters, but spaces are valid as well, [A-Za-z0-9\s]
	 * - ldap_attribute: Valid ldap attribute names
	 * - text: A string with plain characters, digits, and symbols, but not quotes or other special characters (like $, / etc)
	 *
	 * <br>Example:
	 * <code>
	 * $param = 'Hello world!';
	 * if (sC_checkParameter($param, 'string')) {
	 *     echo "Parameter is String";
	 * }
	 *
	 * $param = '123';
	 * if (sC_checkParameter($param, 'unit')) {
	 *     echo "Parameter is Unsigned integer";
	 * }
	 *
	 * </code>
	 * But be careful:
	 * <code>
	 * $param = '0';
	 * if (sC_checkParameter($param, 'unit')) {                      //Wrong way! This will not evalute to true, since sC_checkParameter will return $param, which is 0.
	 *     echo "Parameter is Unsigned integer";
	 * }
	 *
	 * if (sC_checkParameter($param, 'unit') !== false) {             //Correct way, since we make sure that the value returned is actually false.
	 *     echo "Parameter is Unsigned integer";
	 * }
	 * </code>
	 *
	 * @param mixed $param The parameter to check
	 * @param string $type The parameter type (One of: string | uint | id | login | email | file | filename | directory | hex | timestamp | date | alnum | ldap_attribute | alnum_with_spaces | alnum_general | text | path)
	 * @return mixed The parameter, if it is of the specified type, or false otherwise
	 * @version 1.0.1
	 * Changes from 1.0 to 1.1:
	 * - Modified email declaration, so it can detect emails that have a dot (.) in the first part (before the '@').
	 */
	function _checkParameter($parameter, $type, $correct = false) {
	    switch ($type) {
	    case 'string':
	        if (!preg_match("/^[A-Za-z]{1,100}$/", $parameter)) {
	            return false;
	        }
	        break;
	    case 'uint':
	    case 'id':
	        if (!preg_match("/^[0-9]{1,100}$/", $parameter)) { //Caution: If 0 is met, then it will return 0 and not false! so, it must checked against false to make sure

	            return false;
	        }
	        break;
	    case 'login':
	        //if (!preg_match("/^[^0-9]_*\w+(\w*[._@-]*\w*)*$/", $parameter)) {              //This means: begins with 0 or more '_', never a number, followed by at least 1 word character, followed by any combination of .,_,-,@ and word characters.
	        if (!preg_match("/^_*\w+(\w*[._@-]*\w*)*$/", $parameter) || mb_strlen($parameter) > 100) { //This means: begins with 0 or more '_',                 followed by at least 1 word character, followed by any combination of .,_,-,@ and word characters.

	            return false;
	        }
	        break;
	    case 'email':
	        if (!preg_match("/^([a-zA-Z0-9_\.\-'])+\@(([a-zA-Z0-9_\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $parameter)) { //This means: begins with 0 or more '_' or '-', followed by at least 1 word character, followed by any combination of '_', '-', '.' and word characters, then '@', then the same as before, then the '.' and then 1 ore more characters.

	            return false;
	        }
	        break;
	    case 'filename':
	    case 'file':
	        if (preg_match("/^.*((\.\.)|(\/)|(\\\)).*$/", $parameter)) { //File name must not contain .. or slashes of any kind

	            return false;
	        }
	        break;
	    case 'directory':
	        if (preg_match("/^.*((\.\.)|(\\\)).*$/", $parameter)) { //Directory is the same as filename, except that it may contain forward slashes

	            return false;
	        }
	        break;
	    case 'hex':
	        if (!preg_match("/^[0-9a-fA-F]{1,100}$/", $parameter)) {
	            return false;
	        }
	        break;
	    case 'timestamp':
	        if (!preg_match("/^[0-9]{10}$/", $parameter)) {
	            return false;
	        }
	        break;
	    case 'date':
	        if (!preg_match("/^[0-3]?[0-9]\-[0-1]?[0-9]\-[0-9]{4}$/", $parameter)) {
	            return false;
	        }
	        break;
	    case 'alnum':
	        if (!preg_match("/^[A-Za-z0-9_]{1,100}$/", $parameter)) {
	            return false;
	        }
	        break;
	    case 'ldap_attribute':
	        if (!preg_match("/^[A-Za-z0-9:;\-_]{1,100}$/", $parameter)) { //An ldap attribute may be of the form: cn:lang-el;

	        return false;
	        }
	        break;
	    case 'alnum_with_spaces':
	        if (!preg_match("/^[A-Za-z0-9_\s]{1,100}$/", $parameter)) {
	            return false;
	        }
	        break;
	    case 'alnum_general':
	        if (!preg_match("/^[\.,_\-A-Za-z0-9\s]{1,100}$/", $parameter)) {
	            return false;
	        }
	        break;
	    case 'text':
	        if (preg_match("/^.*[$\/\'\"]+.*$/", $parameter)) {
	            return false;
	        }
	        break;
	    case 'noscript':
	        if (preg_match("/^.*<script>.*<\/script>.*$/i", $parameter)) {
	            return false;
	        }
	        break;
	    case 'path':
	        if (preg_match("/^.*[$\"]+.*$/", $parameter)) {
	            return false;
	        }
	        break;
	    default:
	        break;
	    }

	    return $parameter;
	}

	/**
	 * Redirect to another page
	 *
	 * This function implements either server-side (php) or client side (javascript) redirection
	 * <br/>Example:
	 * <code>
	 * </code>
	 *
	 * @param string $url The url to redirect to. If 'self' is used, it is equivalent to a reload (only it isn't)
	 * @param boolean $js Whether to use js-based redirection
	 * @param string $target which frame to reload (only applicable when $js is true). Can be 'top', 'window' or any frame name
	 * @param boolean $retainUrl Whether to retain the url as it is
	 * @since 3.6.0
	 */
	function _redirect($url, $js = false, $target = 'top', $retainUrl = false) {
	    if (!$retainUrl) {
	        $parts = parse_url($url);
	        if (isset($parts['query']) && $parts['query']) {
	            if ($GLOBALS['configuration']['encrypt_url']) {
	                $parts['query'] = 'cru='.encryptString($parts['query']);
	            }
	            $parts['query'] = '?'.$parts['query'];
	        } else {
	            $parts['query'] = '';
	        }
	        $url = G_SERVERNAME.basename($parts['path']).$parts['query'];
	    }
	    if ($js) {
	        echo "<script language='JavaScript'>$target.location='$url'</script>";
	    } else {
	    	echo "Location: $url";
	    	exit;
	        header("Location: $url");
	    }
	    exit;
	}


}
