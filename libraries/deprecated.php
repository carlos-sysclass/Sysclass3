<?php
/**

 *

 */
//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}
/**

* Create content structure

*

* This function creates the content structure in its primitive form. This form consists

* of a multidimensional array, where each value represents a unit. If a unit has children,

* these children exist as sub-arrays of the unit. This way, the array created closely matches

* the tree structure

*

* @param int $content_ID The content id to build the tree for

* @param int $lessons_ID The lesson to build the tree for, defaults to session value

* @param bool $only_active Whether it should return only active values, defaults to true*

*
* @deprecated

*

*/
function sC_createContentStructure($content_ID = 0, $lessons_ID = '', $only_active = true)
{
    if (!$lessons_ID || !sC_checkParameter($lessons_ID, 'id')) {
        $lessons_ID = $_SESSION['s_lessons_ID'];
    }
    $units = sC_getTableData("content", "id, name, parent_content_ID, ctg_type, active, previous_content_ID, data", "lessons_ID=".$lessons_ID, "parent_content_ID, previous_content_ID"); //Get all the lesson units
    for ($i = 0; $i < sizeof($units); $i++) { //Initialize the nodes array
        $units[$i]['data'] == '' ? $units[$i]['data'] = false : $units[$i]['data'] = true; //Set data bit, if the node has data.
        $nodes[$units[$i]['id']] = $units[$i];
        $nodes[$units[$i]['id']]['children'] = array();
    }
    $q = 0;
    while (sizeof($units) > 0 && $q++ < 1000 && !isset($wanted)) { //$q is put here to prevent an infinite loop
        $leaves = sC_findLeaves($units); //Get the leaf nodes of the tree
        foreach ($leaves as $leaf) {
            $nodes[$leaf['parent_content_ID']]['children'][$nodes[$leaf['id']]['id']] = $nodes[$leaf['id']];
            if ($leaf['id'] == $content_ID) { //If the user asked for the tree below a specified content id, then put it in $wanted
                $wanted = $nodes[$leaf['id']];
            }
            unset($nodes[$leaf['id']]);
        }
    }
    isset($wanted) ? $nodes = array(0 => $wanted) : $nodes = $nodes[0]['children']; //$nodes now has either the whole tree (which was put in $nodes[0]['children'] from the loop above), or the branch under the specified content_ID (which was put in $wanted)
    $tree = array();
    $tree = sC_makeCompatibleTree($tree, $nodes, 0); //Convert the tree from its multidimensional form to its flat form, which is used throughout SysClass
    if (sizeof($tree) > 0) {
        $tree = sC_putInCorrectOrder($tree, $only_active); //Reorder the units to match the content series
    }

    return $tree;
}

/**

* Convert primitive content tree to SysClass-compatible tree

*

* This recursive function will convert the given (multidimensional) units array to the corresponding (flat)

* magester content tree. It starts with an empty array as input.

*

* @param array $tree The magester-compatible content tree array (empty on initial call)

* @param array $nodes The primitive content array

* @param int $level The current tree level (depth) to consider

* @return array The new, magester-compatible content tree

* @deprecated

*/
function sC_makeCompatibleTree(&$tree, $nodes, $level = 0)
{
    foreach ($nodes as $node) {
        $new_node = array('id' => $node['id'],
                          'name' => $node['name'],
                          'ctg_type' => $node['ctg_type'],
                          'parent_id' => $node['parent_content_ID'],
                          'level' => $level,
                          'active' => $node['active'],
                          'data' => $node['data']);
        if (!isset($tree[$node['previous_content_ID']])) {
            $tree[$node['previous_content_ID']] = $new_node;
        }
        sC_makeCompatibleTree($tree, $node['children'], $level + 1);
    }

    return $tree;
}
/**

* Reorder tree according to content sequence

*

* This function is used to reorder the tree array, so that the array values succession

* match the content sucession. The reordering is done based on array indexes, as returned

* by sC_makeCompatibleTree

*

* @param array $tree The content tree, as returned by sC_makeCompatibleTree

* @param bool $only_active Whether the tree should contain only active units

* @return array The correct content tree

* @version 1.1

* @deprecated

* Changes from version 1.0 to 1.1 (2007/06/18 - venakis):

* - Added the check if the first unit is active

*/
function sC_putInCorrectOrder($tree, $only_active)
{
    $current_unit = $tree[0];
    if ($current_unit['active'] || !$only_active) {
        $correct_tree[0] = $tree[0];
    }
    unset($tree[0]);
    foreach ($tree as $node) {
        $current_unit = $tree[$current_unit['id']];
        if ($current_unit['active'] == 1 || !$only_active) {
            $correct_tree[] = $current_unit;
        }
    }

    return $correct_tree;
}
/**

* Get the student seen content

*

* This function gets the units that the student has set as seen

* A unit is considered seen when the student clicks the corresponding icon

* below the unit content. The same way he may deignate a unit as unseen (except

* for test units).

*

* @param string $login The student login

* @param int $lessons_ID The current lesson id

* @return array An array holding the seen unit ids (including tests)

* @version 1.0

* @deprecated

*/
function sC_getSeenContent($login, $lessons_ID)
{
    $done_content = sC_getTableDataFlat("users_to_lessons", "done_content", "users_LOGIN='".$login."' and lessons_ID=".$lessons_ID);
    $done_tests = sC_getTableDataFlat("done_tests as dt,tests as t, content as c", "c.id", "dt.users_LOGIN='".$login."' AND t.content_ID = c.id AND c.lessons_ID = ".$lessons_ID." AND t.id=dt.tests_ID");
    (sizeof($done_content['done_content'][0]) > 0 && $done_content['done_content'][0]) ? $done_content = unserialize($done_content['done_content'][0]) : $done_content = array();
    sizeof($done_tests['id'][0]) > 0 ? $done_tests = array_combine($done_tests['id'], $done_tests['id']) : $done_tests = array();
 if ($done_content == false) { // in case $done_content is false from a "b:0;" value in database
  $done_content = array();
 }

    return ($done_content + $done_tests);
}
/**

* Calculates the lesson tree

*

* This function is used to create an array with all the lesson's units, sorted the right way, so that

* parent-children and previous-next relationships are preserved. Each element of the array is another array,

* whose elements are holding unit information: Specifically, these elements are:

* - 'id'        : the unit id

* - 'name'      : the name of the unit

* - 'ctg_type'  : the unit type

* - 'parent_id' : the id of the unit's parent

* - 'level'     : The level of the unit

* - 'active'    : whether it is an active unit

* - 'data'      : Whether the unit has data.

* If a content id is specified, then only the tree under and including the specified unit is returned. content id

* defaults to 0, which means the entire lesson tree. We may also specify the content type, using the $ctg parameter.

* This will result in returning a tree containing only units of the specified type. The $ctg_in_tree variable is

* only used when $only_current or $only_done variables are set to true. It is an array that, after the function

* returns, will contain the unit types present in the list. So, if only theory and exercise units are present in

* the tree, then this variable will be equivalent to: array('theory'=>'theory', 'exercise'=>'exercise');

* <br/>Example:

* <code>

* // We name the first variable $nouse because we will not be using it

* // This call returns the full lesson tree, only active and done units.

* sC_getContentTree($nouse, $lessons_ID, 0, false, true, false, true, false);

* </code>

*

* @param array $ctg_in_tree Lists the unit types present in the list.

* @param int $lessons_ID The lesson id

* @param int $content_ID The content id

* @param string $ctg The content type

* @param bool $only_active Shows only active units

* @param bool $only_current Shows only current units

* @param bool $only_done Shows only units done so far

* @param mixed $only_period If true, then it shows only units that belong to a currently active period. Otherwise,

*                            if set to a number, then it shows only units assigned to the period with this id

* @param string $only_unseen Gets only units that the student with the specified login hasn't seen

* @deprecated

* @return array The content tree

* @version 1.2.1

* Changes from version 1.2 to 1.2.1 (20/11/2005):

* - Replaced > with >= in the $only_period part, since, without it, it was displaying the first unit as clickable in the student tree, even if it wasn't eligible (i.e. out of period)

* Changes from version 1.1 to 1.2 (18/11/2005):

* - Moved the part that examines the $ctg at the end of the function body, in order to exclude units from the student theory / examples etc tree.

*   Before that, parent units were appearing, of units not in the current period

* - Replaced > with >= in the $ctg check part

* Changes from version 1.0 to 1.1 (3/11/2005):

* - Replaced >= with > in many places

*

*/
function sC_getContentTree(&$ctg_in_tree, $lessons_ID = false, $content_ID = 0, $ctg = false, $only_active = true, $only_current = false, $only_done = false, $only_period = false, $only_unseen = false)
{
    if (!$lessons_ID) {
        return "";
    }
    if ($content_ID == "" OR $content_ID === false) { //defaults to 0
        $content_ID = 0;
    }
    $counter = 0;
    $level = 0;
    $tree = sC_createContentStructure($content_ID, $lessons_ID, $only_active); //New way of getting the tree, at least 5 times faster (and many lines of code less)!
    if ($_SESSION['s_type'] == 'student') {
        $seen_content = sC_getSeenContent($_SESSION['s_login'], $lessons_ID);
        //print_r($seen_content);
        foreach ($tree as $key => $value) {
            if (in_array($value['id'], $seen_content)) {
                $tree[$key]['seen']='yes';
            }
        }
    }
/*

    foreach ($tree as $key => $value)

    {

        foreach ($seen_content as $key1 => $value1)

        {

            if ($value['id']==$value1['id'])

                $tree[$key]['seen']='yes';

        }

    }

*/
    $keep = array();
    if ($only_current) {
        $counter = 0;
        for ($i = sizeof($tree) - 1; $i >= 0; $i--) {
            //if (!sC_isCurrentContent($tree[$i]['id'])) {
            if (!$tree[$i]['isCurrentContent']) {
                $parent_keep = false;
                for ($k = 0; $k < sizeof($keep) AND !$parent_keep; $k++) {
                    if ($tree[$i]['id'] == $keep[$k]) {
                        $parent_keep = true;
                        $keep[$counter] = $tree[$i]['parent_id'];
                        $counter++;
                    }
                }
                if (!$parent_keep) {
                    $tree = array_merge(array_slice($tree, 0, $i), array_slice($tree, $i + 1, sizeof($tree) - 1));
                }
            } else {
                $ctg_in_tree[$tree[$i]['ctg_type']] = $tree[$i]['ctg_type'];
                $keep[$counter] = $tree[$i]['parent_id'];
                $counter++;
            }
        }
    }
//print_r($tree);
    $keep = array();
    if ($only_done) {
        $counter = 0;
        for ($i = sizeof($tree) - 1; $i >= 0; $i--) {
            //if (!sC_isDoneContent($tree[$i]['id'])) {
            if (!($tree[$i]['isDoneContent'])) {
                $parent_keep = false;
                for ($k = 0; $k < sizeof($keep) AND !$parent_keep; $k++) {
                    if ($tree[$i]['id'] == $keep[$k]) {
                        $parent_keep = true;
                        $keep[$counter] = $tree[$i]['parent_id'];
                        $counter++;
                    }
                }
                if(!$parent_keep)
                    $tree = array_merge(array_slice($tree, 0, $i), array_slice($tree, $i + 1, sizeof($tree) - 1));
            } else {
                $ctg_in_tree[$tree[$i]['ctg_type']] = $tree[$i]['ctg_type'];
                $keep[$counter] = $tree[$i]['parent_id'];
                $counter++;
            }
        }
    }
    $keep = array();
    if ($only_active) {
        $counter = 0;
        for ($i = sizeof($tree) - 1; $i >= 0; $i--) {
            //if (!sC_isDoneContent($tree[$i]['id'])) {
            if (!$tree[$i]['isDoneContent']) {
                $parent_keep = false;
                for ($k = 0; $k < sizeof($keep) AND !$parent_keep; $k++) {
                    if ($tree[$i]['id'] == $keep[$k]) {
                        $parent_keep = true;
                        $keep[$counter] = $tree[$i]['parent_id'];
                        $counter++;
                    }
                }
                if (!$parent_keep) {
                    $tree[$i]['active'] = false;
                }
            } else {
                $keep[$counter] = $tree[$i]['parent_id'];
                $counter++;
            }
        }
    }
    $keep = array();
    if ($only_period AND $only_period === true) {
        $result = sC_getTableData("current_content", "content_ID");
        for ($i = 0; $i < sizeof($result); $i++) {
            $currentContent[$result[$i]['content_ID']] = $result[$i]['content_ID'];
        }
        $counter = 0;
        for ($i = sizeof($tree) - 1; $i >= 0; $i--) {
            //$res = sC_getTableData("current_content", "content_ID", "content_ID=".$tree[$i]['id']);
            //if (sizeof($res) == 0) {
            if (!isset($currentContent[$tree[$i]['id']])) {
                $parent_keep = false;
                for ($k = 0; $k < sizeof($keep) AND !$parent_keep; $k++) {
                    if ($tree[$i]['id'] == $keep[$k]) {
                        $parent_keep = true;
                        $keep[$counter] = $tree[$i]['parent_id'];
                        $counter++;
                    }
                }
                if (!$parent_keep) {
                    $tree = array_merge(array_slice($tree, 0, $i), array_slice($tree, $i + 1, sizeof($tree) - 1));
                } else {
                    $tree[$i]['active'] = false;
                }
            } else {
                $keep[$counter] = $tree[$i]['parent_id'];
                $counter++;
            }
        }
    } elseif ($only_period) {
        $counter = 0;
        for ($i = sizeof($tree) - 1; $i >= 0; $i--) {
            $res = sC_getTableData("current_content", "content_ID", "content_ID=".$tree[$i]['id']." AND periods_ID=$only_period");
            if (sizeof($res) == 0) {
                $parent_keep = false;
                $keep = array();
                for ($k = 0; $k < sizeof($keep) AND !$parent_keep; $k++) {
                    if ($tree[$i]['id'] == $keep[$k]) {
                        $parent_keep = true;
                        $keep[$counter] = $tree[$i]['parent_id'];
                        $counter++;
                    }
                }
                if (!$parent_keep) {
                    $tree = array_merge(array_slice($tree, 0, $i), array_slice($tree, $i + 1, sizeof($tree) - 1));
                } else {
                    $tree[$i]['active'] = false;
                }
            } else {
                $keep[$counter] = $tree[$i]['parent_id'];
                $counter++;
            }
        }
    }
    $keep = array();
/*

    if ($only_unseen) {

        $counter = 0;

        for ($i = sizeof($tree) - 1; $i >= 0; $i--) {

            $res = sC_getTableData("logs","count(*) as times","(action='content' OR action='lessons' OR action = 'tests') AND users_LOGIN='".$only_unseen."' AND comments=".$tree[$i]['id']);

            if ($res[0]['times'] > 0 OR ($tree[$i]['data'] == false && $tree[$i]['ctg_type'] != 'tests')) {

                $parent_keep = false;

                for ($k = 0; $k < sizeof($keep) AND !$parent_keep; $k++) {

                    if ($tree[$i]['id'] == $keep[$k]) {

                        $parent_keep    = true;

                        $keep[$counter] = $tree[$i]['parent_id'];

                        $counter++;

                    }

                }

                if (!isset($tree[$i]['seen']) && $tree[$i]['ctg_type'] == 'tests') {            //This line checks against 'seen' attribute for tests, so that a test is considered 'unseen' not if it hasn't been visited, but if it hasn't been completed

                    //do nothing

                } elseif (!$parent_keep) {

                    $tree = array_merge(array_slice($tree, 0, $i), array_slice($tree, $i + 1, sizeof($tree) - 1));

                } else {

                    $tree[$i]['active'] = false;

                }

            } else {

                $keep[$counter] = $tree[$i]['parent_id'];

                $counter++;

            }

        }

    }

    $keep = array();

*/
    if ($only_unseen) {
        $counter = 0;
        for ($i = sizeof($tree) - 1; $i >= 0; $i--) {
            if ($tree[$i]['seen'] == 'yes') {
                $parent_keep = false;
                for ($k = 0; $k < sizeof($keep) AND !$parent_keep; $k++) {
                    if ($tree[$i]['id'] == $keep[$k]) {
                        $parent_keep = true;
                        $keep[$counter] = $tree[$i]['parent_id'];
                        $counter++;
                    }
                }
                if (!isset($tree[$i]['seen']) && $tree[$i]['ctg_type'] == 'tests') { //This line checks against 'seen' attribute for tests, so that a test is considered 'unseen' not if it hasn't been visited, but if it hasn't been completed
                    //do nothing
                } elseif (!$parent_keep) {
                    $tree = array_merge(array_slice($tree, 0, $i), array_slice($tree, $i + 1, sizeof($tree) - 1));
                } else {
                    $tree[$i]['active'] = false;
                }
            } else {
                $keep[$counter] = $tree[$i]['parent_id'];
                $counter++;
            }
        }
    }
    $keep = array();
    if ($ctg) { // ????? ???? ????????? ??????? ?? ???? ?? ctg
        $counter = 0;
        for ($i = sizeof($tree) - 1; $i >= 0; $i--) {
            if ($tree[$i]['ctg_type'] != $ctg) {
                $parent_keep = false;
                if (!isset($keep)) {
                    echo $keep = null;
                }
                for ($k = 0; $k < sizeof($keep) AND !$parent_keep; $k++) {
                    if ($tree[$i]['id'] == $keep[$k]) {
                        $parent_keep = true;
                        $keep[$counter] = $tree[$i]['parent_id'];
                        $counter++;
                    }
                }
                if (!$parent_keep) {
                    $tree = array_merge(array_slice($tree, 0, $i), array_slice($tree, $i + 1, sizeof($tree) - 1));
                }
            } else {
                $keep[$counter] = $tree[$i]['parent_id'];
                $counter++;
            }
        }
    }
//    echo "<br>";print_r($tree);
    return $tree;
}
/**

* Parents of a unit

*

* This function returns an array containing the parents of a unit.

* For example, for the unit 3.2.1, the array will hold the ids of the units 3.2.1,

* 3.2, 3 and the 0.

* <br/>Example:

* <code>

* $parents = sC_getParents(213);

* print_r($parents);

* //Returns:

* //Array

* //(

* //    [0] => 213

* //    [1] => 211

* //    [2] => 206

* //    [3] => 0

* //)

* </code>

*

* @param int $content_ID The id of the unit

* @param array $tree The content tree

* @return array The unit's parents ids

* @version 2.0

* @deprecated

* Changes from version 1.0 to version 2.0 (2007/05/15 - venakis):

* - Rewritten so that if $tree is specified, it doesn't perform any database queries

*/
function sC_getParents($content_ID, $tree = null, $tree_indexes = null)
{
    if ($tree) {
        if (!$tree_indexes) {
            for ($i = 0; $i < sizeof($tree); $i++) {
                $tree_indexes[$tree[$i]['id']] = $i;
            }
        }
        $parents['id'][] = $content_ID;
        $parents['name'][] = $tree[$tree_indexes[$content_ID]]['name'];
        while ($tree[$tree_indexes[$content_ID]]['parent_id'] != 0) {
            $content_ID = $tree[$tree_indexes[$content_ID]]['parent_id'];
            $parents['id'][] = $content_ID;
            $parents['name'][] = $tree[$tree_indexes[$content_ID]]['name'];
        }
    } else {
        $now = 0;
        $count = 0;
        $parents['id'][0] = $content_ID;
        while ($now <= $count) {
            $child = $parents['id'][$now];
            $res = sC_getTableData("content", "parent_content_ID, name", "id=$child");
            for ($i = 0; $i < sizeof($res) && $res[$i]['parent_content_ID'] != 0; $i++) {
                $parents['name'][$count] = $res[$i]['name'];
                $count++;
                $parents['id'][$count] = $res[$i]['parent_content_ID'];
            }
            $parents['name'][$count] = $res[$i]['name'];
            $now++;
        }
    }

    return $parents;
}
/**

* Gets dates offset for a student

*

* This functions is used to add an offset to all dates concerning a student that has

* enrolled late to the system. The amount of time he enrolled after the first period

* started is added to the current time, in order to normalize the content flow per period

*

* @return int The date offset is an integer to be added to a timestamp

* @version 1.0.1

* Changes from version 1.0 to 1.0.1:

* - Fixed bug: it always reported offset.

* @deprecated

*/
function sC_getOffset()
{
    if ($GLOBALS['currentLesson'] -> options['dynamic_periods']) {
        $res = sC_getTableData("users_to_lessons", "from_timestamp", "lessons_ID=".$_SESSION['s_lessons_ID']." AND users_LOGIN='".$_SESSION['s_login']."'");
        $new_timestamp = $res[0]['from_timestamp'];
        if ($new_timestamp > 0) {
            $periods = sC_getTableData("periods", "id, name, from_timestamp, to_timestamp", "lessons_ID=".$_SESSION['s_lessons_ID'], "from_timestamp ASC");
            if (sizeof ($periods) > 0) {
                $old_timestamp = $periods[0]['from_timestamp'];
                $temp = $new_timestamp - $old_timestamp;
                if ($new_timestamp > $old_timestamp) {
                    $offset = $new_timestamp - $old_timestamp;
                }
            }
        }
    }
    if (!isset($offset)) {
        $offset = 0;
    }

    return $offset;
}
/**

* 18_1_2007 add case for periods...makriria.. now, in lessons_list op, it appears only current content numbers

* @deprecated

*/
function getLessonContentUnits($lessons_ID = false, $nonempty = false)
{
    if (!$lessons_ID) {
        $lessons_ID = $_SESSION['s_lessons_ID'];
    }
    ($nonempty) ? $nonempty = " AND content.data != '' " : $nonempty = "";
    $time = time();
    $offset = sC_getOffset();
    $result = sC_getTableData("periods", "id", "lessons_ID=".$lessons_ID);
    if (sizeof($result) > 0) {
        $units = sC_getTableData("current_content,periods,content", "count(*)", "ctg_type != 'tests' AND periods.lessons_ID = ".$lessons_ID." AND current_content.periods_ID=periods.id AND periods.from_timestamp+$offset<=$time AND periods.to_timestamp+$offset>$time AND content.id=current_content.content_ID AND content.active=1".$nonempty);
        $tests = sC_getTableData("current_content,periods,content", "count(*)", "ctg_type = 'tests' AND periods.lessons_ID = ".$lessons_ID." AND current_content.periods_ID=periods.id AND periods.from_timestamp+$offset<=$time AND periods.to_timestamp+$offset>$time AND content.id=current_content.content_ID AND content.active=1");
    } else {
        $units = sC_getTableData("content", "count(*)", "ctg_type != 'tests' and lessons_ID = $lessons_ID and active = 1".$nonempty);
        $tests = sC_getTableData("content", "count(*)", "ctg_type = 'tests' and lessons_ID = $lessons_ID and active = 1");
    }

    return array('units' => $units[0]['count(*)'], 'tests' => $tests[0]['count(*)']);
}
/**

* prints header - why this is not a smarty template?

*

* @version 1.0

* @DEPRECATED!!!

*/
function sC_printHeader($editor = false)
{
    global $path, $COLOR, $ctg;
    //$marginstr = isset($_POST['standalone'])? "" : " style = 'margin-left: -5px;'";
    //$marginstr = "style = 'margin-left: -5px;'";
    $marginstr = "style = 'margin-left:0px; margin-right:2px; margin-top:4px; margin-bottom:0px'";
    print '
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 transitional//EN">
      <HTML><HEAD>
      <meta http-equiv="Content-Language" content="'._HEADERLANGUAGETAG.'" />
      <TITLE>SysClass - '._THENEWFORMOFADDITIVELEARNING.'</TITLE>
      <link rel="shortcut icon" href="images/favicon.ico" >
      <link rel="icon" href="images/favicon.gif" type="image/gif" >
      <META http-equiv="keywords" content="education" />
      <META http-equiv="description" content="Collaborative Elearning Platform" />
      <META http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <META http-equiv="Cache-Control" content="no-cache" />
      <META http-equiv="Pragma" content="no-cache" />
      <META http-equiv="Expires" content="0" />
    <LINK rel="stylesheet" type="text/css" href="css/css_global.css" />
    <LINK rel="stylesheet" type="text/css" href="css/css_global_temp.css" />
    ';
    $css = sC_getTableData("configuration", "value", "name='css'");
    $css = $css[0]['value'];
    if ($css && sC_checkParameter($css, 'filename') && is_file(G_ROOTPATH.'www/css/custom_css/'.$css)) {
        print '<LINK rel="stylesheet" type="text/css" href="css/custom_css/'.$css.'" />';
    } else {
        print '<LINK rel="stylesheet" type="text/css" href="css/custom_css/normal.css" />';
    }
    print '<LINK rel="stylesheet" type="text/css" href="slashfiles/menu.css" />
    <LINK rel="stylesheet" type="text/css" href="css/drag-drop-folder-tree.css" />
    <LINK rel="stylesheet" type="text/css" href="css/tabber.css" />';
    if ($editor == true) {
        print '
        <script language="javascript" type="text/javascript" src="editor/tiny_mce/tiny_mce.js"></script>
        <script language="javascript" type="text/javascript">
        tinyMCE.init({
        mode : "specific_textareas",
        editor_selector : "simpleEditor",
        theme : "simple",
        ';
        switch ($_SESSION['s_language']) {
            case "greek":
                print 'language : \'el\',';
                break;
            case "english":
                print 'language : \'en\',';
                break;
        }
        print '
        entity_encoding : "raw",
        force_p_newlines : false,
        force_br_newlines : true,
        convert_newlines_to_brs : true
            });
        tinyMCE.init({
        //mode : "exact",
        mode : "specific_textareas",
        ';
        switch ($_SESSION['s_language']) {
            case "greek":
                print 'language : \'el\',';
                break;
            case "english":
                print 'language : \'en\',';
                break;
        }
        print '
        editor_selector : "mceEditor",
        theme : "advanced",
        //height : "550",
        theme_advanced_resizing : true,
        theme_advanced_resizing_use_cookie : false,
        entity_encoding : "raw",
        force_p_newlines : false,
        force_br_newlines : true,
        convert_newlines_to_brs : false,
        apply_source_formatting : true,
        plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,java,searchreplace,print,contextmenu,media,mathtype,paste,fullscreen",
        theme_advanced_buttons1_add_before : "save,separator",
        theme_advanced_buttons1_add : "fontselect,fontsizeselect",
        theme_advanced_buttons2_add : "separator,insertdate,inserttime,zoom,separator,forecolor,backcolor",
        theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
        theme_advanced_buttons3_add_before : "tablecontrols,separator",
        theme_advanced_buttons3_add : "emotions,iespell,advhr,separator,print,java,media,separator,mathtype,pastetext,pasteword,selectall,preview,fullscreen",
        plugin_preview_width : "700",
        plugin_preview_height : "700",
        paste_create_paragraphs : false,
        paste_create_linebreaks : false,
        paste_use_dialog : true,
        paste_auto_cleanup_on_paste : true,
        paste_convert_middot_lists : false,
        paste_unindented_list_class : "unindentedList",
        paste_convert_headers_to_strong : true,
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_path_location : "bottom",
        plugin_insertdate_dateFormat : "%Y-%m-%d",
        plugin_insertdate_timeFormat : "%H:%M:%S",
        extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],applet[code|codebase|width|height],embed[src|autostart|type]",';
        //file_browser_callback : "myCustomFileBrowser",
        print '
        external_link_list_url : "example_data/example_link_list.js",
        external_image_list_url : "myexternallist.js",
        flash_external_list_url : "example_data/example_flash_list.js"
        });
    function myCustomFileBrowser(field_name, url, type, win)
    {
        var fileBrowserWindow = new Array();
        fileBrowserWindow["file"] = "editor/popups/insert_image.php" + "?type=" + type + "&lessons_ID='.$_SESSION['s_lessons_ID'].'";
        fileBrowserWindow["title"] = "File Browser";
        fileBrowserWindow["width"] = "800";
        fileBrowserWindow["height"] = "600";
        fileBrowserWindow["close_previous"] = "no";
        tinyMCE.openWindow(fileBrowserWindow, {
          window : win,
          input : field_name,
          resizable : "yes",
          inline : "yes"
        });
        win.tinyMCE.setWindowArg(\'editor_id\',\'mce_editor_0\');
    }
        tinyMCE.init({
        mode : "specific_textareas",
        ';
        switch ($_SESSION['s_language']) {
            case "greek":
                print 'language : \'el\',';
                break;
            case "english":
                print 'language : \'en\',';
                break;
        }
        print '
        editor_selector : "intermediateEditor",
        theme : "advanced",
        theme_advanced_resizing : true,
        theme_advanced_resizing_use_cookie : false,
        entity_encoding : "raw",
        force_p_newlines : false,
        force_br_newlines : true,
        convert_newlines_to_brs : false,
        apply_source_formatting : true,
        plugins : "table,advhr,advimage,advlink,emotions,iespell,java,contextmenu,media,mathtype",
        theme_advanced_buttons1_add : "fontselect,fontsizeselect,removeformat",
        theme_advanced_buttons2_add_before : "forecolor,backcolor,separator,table",
        theme_advanced_buttons2_add : "emotions,iespell,separator,advimage,java,media,separator,mathtype,sub,sup",
        theme_advanced_buttons3 : "",
        theme_advanced_disable : "formatselect,styleselect,strikethrough,cut,copy,paste,indent,outdent,help,cleanup,hr,anchor",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_path_location : "bottom",
        extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],applet[code|codebase|width|height],embed[src|autostart|type]"
        });
        </script>';
    }
    print '<script type="text/javascript" src="js/mathml.js"> </script>
        <script type="text/javascript" src="js/ASCIIMathML.js"></script>
    <script type="text/javascript" src="js/ajax.js"></script>
    <script type="text/javascript" src="js/drag-drop-folder-tree.js"></script>
    <script type="text/javascript" src="js/statists_tabber.js"></script>
    <script type="text/javascript" src="js/calendar3.js"></script>
    <script type="text/javascript">
    //--------------------------------
    // Save functions
    //--------------------------------
    var ajaxObjects = new Array();
    // Use something like this if you want to save data by Ajax.
    function saveMyTree()
    {
            saveString = treeObj.getNodeOrders();
            var ajaxIndex = ajaxObjects.length;
            ajaxObjects[ajaxIndex] = new sack();
            var url = "saveNodes.php?saveString=" + saveString;
            ajaxObjects[ajaxIndex].requestFile = url; // Specifying which file to get
            ajaxObjects[ajaxIndex].onCompletion = function() { saveComplete(ajaxIndex); };// Specify function that will be executed after file has been found
            ajaxObjects[ajaxIndex].runAJAX(); // Execute AJAX function
    }
    function saveComplete(index)
    {
        alert(ajaxObjects[index].response);
    }
    // Call this function if you want to save it by a form.
    function saveMyTree_byForm()
    {
        document.myForm.elements["saveString"].value = treeObj.getNodeOrders();
        document.myForm.submit();
    }
        </script>';
//echo $ctg." ".$COLOR[$ctg];
    //include_once $path."css.php";
    if (!$ctg) {
        $ctg = "lessons";
    }
    if (!$COLOR[$ctg] OR $COLOR[$ctg] == "") {
        //$DsC_COLOR   = sC_getDefaultColors();
        $COLOR[$ctg] = $DsC_COLOR["lessons"];
    }
    $COLOR['messages'] = $COLOR['forum'];
    print '<style type="text/css">
            TH {
                BACKGROUND-COLOR: '.$COLOR[$ctg].';
            }
            .top_title {
                BACKGROUND-COLOR: '.$COLOR[$ctg].';
            }
            </style>';
print'        </HEAD>
            <BODY id = "body_'.$ctg.'" onload = "jsC_initialize()" '.$marginstr.'>
        <script type="text/javascript" src="js/PieNG.js"></script>
        <script type="text/javascript" src="js/tabber.js"></script>
        <script type="text/javascript" LANGUAGE="JavaScript">
            if (window.location==top.location) {
            //if(document.body.style && document.body.style.marginLeft)
                    //  document.body.style.marginLeft = "10px";
            }
        </script>
        <script type="text/javascript">
            top.document.title = "SysClass - '._THENEWFORMOFADDITIVELEARNING.'";
        </script>
        <SCRIPT type="text/javascript" LANGUAGE="JavaScript" src="js/print-script.js"></SCRIPT>
            ';
        //echo $ctg." ".$COLOR[$ctg];
    print " <SCRIPT LANGUAGE=\"JavaScript\">
            function jsC_initialize()
            {
                if (el = document.getElementById('main_table'))
                    el.style.display = '';
                if (el = document.getElementById('loading_table'))
                    el.style.display = 'none';
                if (window._editor_url) initEditor();
                setCorrectIframeSize();
                if(changeImages)
                        changeImages();
            }
            function popUp(URL, width, height, resize)
            {
                var left = (screen.width - width) / 2
                var top = (screen.height-height) / 2
                var resizeable = 0;
                if (resize == 1) {
                    resizeable = 1;
                }
                popup = window.open(URL, '', 'toolbar = 0, scrollbars = 1, location = 0, statusbar = 1, menubar = 0, resizable = '+resizeable+', width = '+width+', height = '+height+', left = '+left+', top = '+top);

                return popup;
            }
            //���� �� ������� javascript ������ �� iframe ��� scorm ��� ����� �������, ���� �� ������ ��� ��� ������
            function setCorrectIframeSize()
            {
                if (frame = window.document.getElementById('scormFrameID')) {
                    innerDoc = (frame.contentDocument) ? frame.contentDocument : frame.contentWindow.document;
                    objToResize = (frame.style) ? frame.style : frame;
                    if (frame.document) {
                        //alert(frame.contentWindow.document.body.clientHeight);
                        objToResize.height = Math.max(innerDoc.body.scrollHeight, frame.document.body.scrollHeight) + 50;
                        //alert(frame.contentWindow.document.body.clientHeight);
                    } else {
                        objToResize.height = innerDoc.body.scrollHeight + 50;
                    }
                }
            }
            </SCRIPT>";
}
/**

* Get the leaf nodes of the tree

*

* Ths function is used to traverse through the primitive content tree array,

* to find all units that do not have children (leaf units). It also removes

* these leaves from the array.

*

* @param array $units The array of units

* @return array The tree leaves

*/
function sC_findLeaves(&$units)
{
    for ($i = 0; $i < sizeof($units); $i++) {
        $ok = true;
        for ($j = 0; $j < sizeof($units); $j++) {
            if ($units[$j]['parent_content_ID'] == $units[$i]['id']) {
                $ok = false;
            }
        }
        if ($ok) {
            $leaves[] = $units[$i];
            $unset_array[] = $i;
        }
    }
    for ($i = 0; $i < sizeof($unset_array); $i++) {
        unset($units[$unset_array[$i]]);
    }
    $units = array_values($units);

    return $leaves;
}
/**

* Recursively delete directory contents

*

* This function deletes all the contents of the designated folder, including subfolders, and the folder itself

* <br/>Example:

* <code>

* sC_deleteFolder('/tmp/useless_dir/');

* </code>

*

* @param string $folder The full pathname of the directory to be deleted

* @return bool true if everythin is ok.

* @see sC_getDirContents

* @todo return other than true

*/
function sC_deleteFolder($folder)
{
    $folder = $folder.'/';
    $filelist = sC_getDirContents($folder);
    for ($i = 0; $i < sizeof($filelist); $i++) {
        unlink($filelist[$i]);
    }
    $folders[] = $folder;
    for ($k = 0; $k < sizeof($folders); $k++) {
        $handle = opendir($folders[$k]);
        while (false !== ($file = readdir($handle))) {
            if ($file != "." AND $file != "..") {
                $folders[] = $folders[$k].'/'.$file;
            }
        }
        closedir($handle);
    }
    for ($k = sizeof($folders) - 1; $k >= 0; $k--) {
        rmdir($folders[$k]);
    }
    if (is_dir($folder)) {
        return false;
    } else {
        return true;
    }
}

if (!function_exists('date_create_from_format')) {
	function date_create_from_format($format, $dateString)
	{
		switch ($format) {
			case 'd/m/Y' : {
				list($day, $month, $year) = sscanf($dateString, '%02d/%02d/%04d');
				if (
						is_null($day) ||
						is_null($month) ||
						is_null($year)
				) {
					return false;
				}
				$datetime = new DateTime("$year-$month-$day");
				break;
			}
			case 'm/d/Y' : {
				list($month, $day, $year) = sscanf($dateString, '%02d/%02d/%04d');
				if (
						is_null($day) ||
						is_null($month) ||
						is_null($year)
				) {
					return false;
				}
				$datetime = new DateTime("$year-$month-$day");
				break;
			}
			case 'Y/m/d' : {
				list($year, $month, $day) = sscanf($dateString, '%04d/%02d/%02d');
				if (
						is_null($day) ||
						is_null($month) ||
						is_null($year)
				) {
					return false;
				}
				$datetime = new DateTime("$year-$month-$day");
				break;
			}
			case 'Y-m-d' : {
				list($year, $month, $day) = sscanf($dateString, '%04d-%02d-%02d');
				if (
						is_null($day) ||
						is_null($month) ||
						is_null($year)
				) {
					// TRY strtotime
					if (strtotime($dateString) !== FALSE) {
						$datetime = new DateTime(strtotime($dateString));
					}
					return false;
				} else {
					$datetime = new DateTime("$year-$month-$day");
				}
				break;
			}
			case 'Y-m-d H:i:s' : {
				list($year, $month, $day, $hours, $minutes, $seconds) = sscanf($dateString, '%04d-%02d-%02d %02d:%02d:%02d');
				if (
						is_null($day) ||
						is_null($month) ||
						is_null($year) ||
						is_null($hours) ||
						is_null($minutes) ||
						is_null($seconds)
				) {
					// TRY strtotime
					if (strtotime($dateString) !== FALSE) {
						$datetime = new DateTime(strtotime($dateString));
					}
					return false;
				} else {
					$datetime = new DateTime("$year-$month-$day $hours:$minutes:$seconds");
				}
				break;
			}
			default : {
				$datetime = new DateTime($dateString);
				break;
			}
		}
		return $datetime;
	}
}

if (!function_exists('array_merge_recursive_keys')) {
	function array_merge_recursive_keys()
	{
		$arrays = func_get_args();
		$base = array_shift($arrays);

		foreach ($arrays as $array) {
			reset($base); //important
			while (list($key, $value) = @each($array)) {
				if (is_array($value) && @is_array($base[$key])) {
					$base[$key] = array_merge_recursive_new($base[$key], $value);
				} else {
					$base[$key] = $value;
				}
			}
		}
		return $base;
	}
}
