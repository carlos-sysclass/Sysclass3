<?php

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}

$smarty -> assign("T_OPTION", $_GET['option']);
try {
    if ($currentUser -> user['user_type'] == 'administrator') {
        $lessons = MagesterLesson :: getLessons();
    } elseif ($isProfessor) {
        $lessons = $currentUser -> getLessons(true, 'professor');
    }

    if (sizeof($lessons) == 1) {
        $infoLesson = array_pop($lessons); //get the current (first) lesson
        if (!($infoLesson instanceof MagesterLesson)) {
            $infoLesson = new MagesterLesson($infoLesson['id']);
        }
    } elseif (isset($_GET['sel_lesson']) && in_array($_GET['sel_lesson'], array_keys($lessons))) {
        $infoLesson = new MagesterLesson($_GET['sel_lesson']);
    } elseif (isset($_SESSION['s_lessons_ID']) && in_array($_SESSION['s_lessons_ID'], array_keys($lessons))) {
        $infoLesson = new MagesterLesson($_SESSION['s_lessons_ID']);
    }

    $smarty -> assign("T_INFO_LESSON", $infoLesson -> lesson);

    //get the lesson information
    if (isset($infoLesson)) {
        try {
         $directionsTree = new MagesterDirectionsTree();
         $directionsPaths = $directionsTree -> toPathString();

      $roles = MagesterLessonUser :: getLessonsRoles(true);
      $smarty -> assign("T_ROLES_ARRAY", $roles);

      $rolesBasic = MagesterLessonUser :: getLessonsRoles();
      $smarty -> assign("T_BASIC_ROLES_ARRAY", $rolesBasic);

      foreach ($rolesBasic as $key => $role) {
       $constraints = array('archive' => false, 'table_filters' => $stats_filters, 'condition' => 'ul.user_type = "'.$key.'"');
          $numUsers = ($infoLesson -> countLessonUsers($constraints));
       if ($numUsers) {
        $usersPerRole[$key] = $numUsers;
       }
          //$role == 'student' ? $studentRoles[] = $key : $professorRoles[] = $key;
      }

      $infoLesson -> lesson['users_per_role'] = $usersPerRole;
      $infoLesson -> lesson['num_users'] = array_sum($usersPerRole);

/*

	    	$constraints = array('archive' => false, 'table_filters' => $stats_filters, 'condition' => 'ul.user_type in ("'.implode('","', $studentRoles).'")');

        	$infoLesson -> lesson['num_students']   = ($infoLesson -> countLessonUsers($constraints));

	    	$constraints = array('archive' => false, 'table_filters' => $stats_filters, 'condition' => 'ul.user_type in ("'.implode('","', $professorRoles).'")');

        	$infoLesson -> lesson['num_professors'] = ($infoLesson -> countLessonUsers($constraints));

*/
         $infoLesson -> lesson['category_path'] = $directionsPaths[$infoLesson -> lesson['directions_ID']];
      $smarty -> assign("T_CURRENT_LESSON_INFO", $infoLesson);
            $smarty -> assign("T_STATS_ENTITY_ID", $infoLesson -> lesson['id']);
         $lessonInfo = $infoLesson -> getStatisticInformation();
         $smarty -> assign("T_LESSON_INFO", $lessonInfo);
        } catch (Exception $e) {
         handleNormalFlowExceptions($e);
        }
        require_once $path."includes/statistics/stats_filters.php";
        try {
         if (isset($_GET['ajax']) && $_GET['ajax'] == 'lessonUsersTable') {
          //$smarty -> assign("T_DATASOURCE_COLUMNS", array('login', 'location', 'user_type', 'completed', 'score', 'operations'));
          //$smarty -> assign("T_DATASOURCE_OPERATIONS", array('statistics'));
          $constraints = createConstraintsFromSortedTable() + array('archive' => false, 'return_objects' => false, 'table_filters' => $stats_filters);
          $users = $infoLesson -> getLessonStatusForUsers($constraints);
          foreach ($users as $key => $value) {
           if ($value['user_type'] == 'professor' || $rolesBasic[$value['user_types_ID']] == 'professor') {
            $users[$key]['basic_user_type'] = 'professor';
           }
          }
          $totalEntries = $infoLesson -> countLessonUsers($constraints);
          $dataSource = $users;
          $smarty -> assign("T_TABLE_SIZE", $totalEntries);
         //pr($users);
          $tableName = $_GET['ajax'];
         }
         $alreadySorted = true;
         include 'sorted_table.php';
        } catch (Exception $e) {
         handleAjaxExceptions($e);
        }

        /*

         *  Lesson's tests

         */
        try {
            $lessonTests = $infoLesson -> getTests(true);
            $scormTests = $infoLesson -> getScormTests();
            if (sizeof($lessonTests) > 0 || sizeof($scormTests) > 0) {
                if (sizeof($lessonTests) > 0) {
                    $testsInfo = MagesterStats :: getTestInfo(array_keys($lessonTests), false, false, $infoLesson -> lesson['id']);
                } else {
                    $testsInfo = array();
                }
                if (sizeof($scormTestsInfo = MagesterStats :: getScormTestInfo($scormTests)) > 0) {
                    $testsInfo = $testsInfo + $scormTestsInfo;
                }

                if (isset($groupUsers)) {
                    foreach ($testsInfo as $id => $test) {
                        foreach ($test['done'] as $key => $value) {
                            if (!in_array($value['users_LOGIN'], $groupUsers['student'])) {
                                unset($testsInfo[$id]['done'][$key]);
                            }
                        }
                    }
                }

                $smarty -> assign("T_TESTS_INFO", $testsInfo);
            }
        } catch (Exception $e) {
         handleNormalFlowExceptions($e);
        }

        /*

         *      Lesson's questions

         */
        try {
            $lessonQuestions = array_keys($infoLesson -> getQuestions());
            if (sizeof($lessonQuestions) > 0) {
                $info = MagesterStats :: getQuestionInfo($lessonQuestions, $infoLesson -> lesson['id']);
                $questionsInfo = array();
                foreach ($info as $id => $questionInfo) {
                    $questionsInfo[$id] = array('text' => $questionInfo['general']['reduced_text'],
                                                        'type' => $questionInfo['general']['type'],
                                                        'difficulty' => $questionInfo['general']['difficulty'],
                                                        'times_done' => $questionInfo['done']['times_done'],
                                                        'avg_score' => round($questionInfo['done']['avg_score'], 2));
                }
                $smarty -> assign("T_QUESTIONS_INFORMATION", $questionsInfo);
            }
        } catch (Exception $e) {
            $smarty -> assign("T_EXCEPTION_TRACE", $e -> getTraceAsString());
            $message = $e -> getMessage().' ('.$e -> getCode().') &nbsp;<a href = "javascript:void(0)" onclick = "eF_js_showDivPopup(\''._ERRORDETAILS.'\', 2, \'error_details\')">'._MOREINFO.'</a>';
            $message_type = 'failure';
        }

        /*

         *      Lesson's projects

         */
        try {
            $lessonProjects = $infoLesson -> getProjects(true, false, false);
            if (sizeof($lessonProjects) > 0) {
                $projectsInfo = MagesterStats :: getProjectInfo(array_keys($lessonProjects));
                $smarty -> assign("T_PROJECTS_INFORMATION", $projectsInfo);
            }
        } catch (Exception $e) {
            $smarty -> assign("T_EXCEPTION_TRACE", $e -> getTraceAsString());
            $message = $e -> getMessage().' ('.$e -> getCode().') &nbsp;<a href = "javascript:void(0)" onclick = "eF_js_showDivPopup(\''._ERRORDETAILS.'\', 2, \'error_details\')">'._MOREINFO.'</a>';
            $message_type = 'failure';
        }

        /*

         *  lesson traffic

         */
        try {
            if (isset($_GET['from_year'])) { //the admin has chosen a period
                $from = mktime($_GET['from_hour'], $_GET['from_min'], 0, $_GET['from_month'], $_GET['from_day'], $_GET['from_year']);
                $to = mktime($_GET['to_hour'], $_GET['to_min'], 0, $_GET['to_month'], $_GET['to_day'], $_GET['to_year']);
            } else {
                $from = mktime(date("H"), date("i"), 0, date("m"), date("d") - 7, date("Y"));
                $to = mktime(date("H"), date("i"), 0, date("m"), date("d"), date("Y"));
            }
            $actions = array('login' => _LOGIN,
                                     'logout' => _LOGOUT,
                                     'lesson' => _ACCESSEDLESSON,
                                     'content' => _ACCESSEDCONTENT,
                                     'tests' => _ACCESSEDTEST,
                                     'test_begin' => _BEGUNTEST,
                                     'lastmove' => _NAVIGATEDSYSTEM);
            $smarty -> assign("T_ACTIONS", $actions);
            if (isset($_GET['showlog']) && $_GET['showlog'] == "true") {
                $contentNames = eF_getTableDataFlat("content", "id, name");
                $contentNames = array_combine($contentNames['id'], $contentNames['name']);
                $testNames = eF_getTableDataFlat("tests t, content c", "t.id, c.name", "c.id=t.content_ID");
                $testNames = array_combine($testNames['id'], $testNames['name']);
                $result = eF_getTableData("logs", "*", "timestamp between $from and $to and lessons_ID='".$infoLesson -> lesson['id']."' order by timestamp desc");

                foreach ($result as $key => $value) {
                    if ($value['action'] == 'content') {
                        $result[$key]['content_name'] = $contentNames[$value['comments']];
                    } elseif ($value['action'] == 'tests' || $value['action'] == 'test_begin') {
                        $result[$key]['content_name'] = $testNames[$value['comments']];
                    }
                }
                $smarty -> assign("T_LESSON_LOG", $result);
            }

            $constraints = array('archive' => false, 'return_objects' => false, 'table_filters' => $stats_filters);

            $filteredUsers = $infoLesson -> getLessonUsers($constraints);

            $users = array();
            foreach ($filteredUsers as $user) {
             $users[$user['login']] = $user['active'];
            }
            $traffic['users'] = $infoLesson -> getLessonTimesForUsers();
            foreach ($traffic['users'] as $key => $user) {
                if (isset($groupUsers) && !in_array($key, $groupUsers['professor']) && !in_array($key, $groupUsers['student'])) {
                    unset($traffic['users'][$key]);
                } else {
                    $traffic['users'][$key]['active'] = $users[$key];
                }
            }

            foreach ($traffic['users'] as $value) {
                $traffic['total_seconds'] += $value['total_seconds'];
            }
            $traffic['total_time'] = eF_convertIntervalToTime($traffic['total_seconds']);

            try {
/*

            	if (isset($_GET['ajax']) && $_GET['ajax'] == 'graph_access') {

            		$graph = new MagesterGraph();

            		$graph -> type = 'bar';

            		$count = 0;

            		foreach ($traffic['users'] as $key => $value) {

            			$graph -> data[]    = array($count, $value['accesses']);

            			$graph -> xLabels[] = array($count++, formatLogin($key));

            		}

            		//pr($graph);

            		$graph -> xTitle = _USERS;

            		$graph -> yTitle = _ACCESSES;

            		$graph -> title  = _ACCESSESPERUSER;



            		echo json_encode($graph);

            		exit;

            	} else

*/
             if (isset($_GET['ajax']) && $_GET['ajax'] == 'graph_user_access') {
              $user = MagesterUserFactory :: factory($_GET['entity']);
     $timesReport = new MagesterTimes(array($from, $to));
     $cnt=0;
     $result = $timesReport -> getUserSessionTimeInSingleLessonPerDay($user -> user['login'], $infoLesson -> lesson['id']);
     foreach ($result as $key => $value) {
      $labels[$cnt] = $key;
      $count[$cnt++] = ceil($value/60);
     }
     $graph = new MagesterGraph();
     $graph -> type = 'line';
     for ($i = 0; $i < sizeof($labels); $i++) {
      $graph -> data[] = array($i, $count[$i]);
      $graph -> xLabels[] = array($i, formatTimestamp($labels[$i]));
     }
     $graph -> xTitle = _DAY;
     $graph -> yTitle = _MINUTES;
     $graph -> title = _MINUTESPERDAY;
     echo json_encode($graph);
     exit;
             } elseif (isset($_GET['ajax']) && $_GET['ajax'] == 'graph_test_questions') {
              $test = new MagesterTest($_GET['entity']);
     $types = array();
              foreach ($test -> getQuestions() as $value) {
               isset($types[$value['type']]) ? $types[$value['type']]++ : $types[$value['type']] = 1;
              }
              $graph = new MagesterGraph();
     $graph -> type = 'pie';
     $count = 0;
     foreach ($types as $key => $value) {
      $graph -> data[] = array(array($count, $value));
      $graph -> labels[] = array(Question :: $questionTypes[$key]);
     }
     echo json_encode($graph);
              exit;
             }
            } catch (Exception $e) {
             handleAjaxExceptions($e);
            }
            $smarty -> assign("T_LESSON_TRAFFIC", $traffic);
            $smarty -> assign('T_FROM_TIMESTAMP', $from);
            $smarty -> assign('T_TO_TIMESTAMP', $to);
        } catch (Exception $e) {
         handleNormalFlowExceptions($e);
        }
        $groups = MagesterGroup :: getGroups();
        $smarty -> assign("T_GROUPS", $groups);
    }
} catch (Exception $e) {
 handleNormalFlowExceptions($e);
}
if (isset($_GET['excel']) && $_GET['excel'] == 'lesson') {
    // Get the associated group name
    if (isset($_GET['group_filter']) && $_GET['group_filter']) {
        try {
            $group = new MagesterGroup($_GET['group_filter']);
            $groupname = str_replace(" ", "_" , $group -> group['name']);
        } catch (Exception $e) {
            $groupname = false;
        }
    }
    if (G_VERSIONTYPE == 'enterprise' && isset($_GET['branch_filter']) && $_GET['branch_filter']) {
        try {
            $branch = new MagesterBranch($_GET['branch_filter']);
            $branchName = $branch -> branch['name'];
        } catch (Exception $e) {
            $branchName = false;
        }
    }
    require_once 'Spreadsheet/Excel/Writer.php';
    $workBook = new Spreadsheet_Excel_Writer();
    $workBook -> setTempDir(G_UPLOADPATH);
    $workBook -> setVersion(8);
    $filename = 'export_'.$infoLesson -> lesson['name'];
    if ($groupname) {
        $filename .= '_group_'.str_replace(" ", "_" , $groupname);
    }
    if ($branchName) {
        $filename .= '_branch_'.str_replace(" ", "_" , $branchName);
    }
    $workBook -> send($filename.'.xls');
    $formatExcelHeaders = & $workBook -> addFormat(array('Size' => 14, 'Bold' => 1, 'HAlign' => 'left'));
    $headerFormat = & $workBook -> addFormat(array('border' => 0, 'bold' => '1', 'size' => '11', 'color' => 'black', 'fgcolor' => 22, 'align' => 'center'));
    $formatContent = & $workBook -> addFormat(array('HAlign' => 'left', 'Valign' => 'top', 'TextWrap' => 1));
    $headerBigFormat = & $workBook -> addFormat(array('HAlign' => 'center', 'FgColor' => 22, 'Size' => 16, 'Bold' => 1));
    $titleCenterFormat = & $workBook -> addFormat(array('HAlign' => 'center', 'Size' => 11, 'Bold' => 1));
    $titleLeftFormat = & $workBook -> addFormat(array('HAlign' => 'left', 'Size' => 11, 'Bold' => 1));
    $fieldLeftFormat = & $workBook -> addFormat(array('HAlign' => 'left', 'Size' => 10));
    $fieldRightFormat = & $workBook -> addFormat(array('HAlign' => 'right', 'Size' => 10));
    $fieldCenterFormat = & $workBook -> addFormat(array('HAlign' => 'center', 'Size' => 10));
    $fieldLeftBoldFormat = & $workBook -> addFormat(array('HAlign' => 'left', 'Size' => 10, 'Bold' => 1));
    $fieldLeftItalicFormat = & $workBook -> addFormat(array('HAlign' => 'left', 'Size' => 10, 'Italic' => 1));
    //first tab
    $workSheet = & $workBook -> addWorksheet("General Lesson Info");
    $workSheet -> setInputEncoding('utf-8');
    $workSheet -> setColumn(0, 0, 5);

    //basic info
    if ($groupname || $branchName) {
        $celltitle = "";
        if ($groupname) {
            $celltitle .= _BASICINFO . " " . _FORGROUP . ": ". $groupname . " ";
        }
        if ($branchName) {
            if ($celltitle != "") {
                $celltitle .= _ANDBRANCH. ": ". $branchName . " ";
            } else {
                $celltitle .= _BASICINFO . " " ._FORBRANCH . ": ". $branchName . " ";
            }
        }
        $workSheet -> write(1, 1, $celltitle, $headerFormat);
    } else {
        $workSheet -> write(1, 1, _BASICINFO, $headerFormat);
    }
    $workSheet -> mergeCells(1, 1, 1, 2);
    $workSheet -> setColumn(1, 2, 30);


    $directionName = eF_getTableData("directions", "name", "id=".$infoLesson -> lesson['directions_ID']);
    $languages = MagesterSystem :: getLanguages(true);

    // Get only filtered users
    $constraints = array('archive' => false, 'return_objects' => false, 'table_filters' => $stats_filters);
    $filteredUsers = $infoLesson -> getLessonStatusForUsers($constraints);

    $students = array();
    $professors = array();
    foreach ($filteredUsers as $user) {
     if ($user['user_type'] == "student") {
      $students[$user['login']] = $user;

     } elseif ($user['user_type'] == "professor") {
      $professors[$user['login']] = $user;
     }
    }


    $workSheet -> write(2, 1, _LESSON, $fieldLeftFormat);
    $workSheet -> write(2, 2, $infoLesson -> lesson['name'], $fieldRightFormat);
    $workSheet -> write(3, 1, _CATEGORY, $fieldLeftFormat);
    $workSheet -> write(3, 2, $directionName[0]['name'], $fieldRightFormat);


    if ($groupname || $branchName) {
        $workSheet -> write(4, 1, _STUDENTS, $fieldLeftFormat);
        $workSheet -> writeNumber(4, 2, sizeof($students), $fieldRightFormat);
        $workSheet -> write(5, 1, _PROFESSORS, $fieldLeftFormat);
        $workSheet -> writeNumber(5, 2, sizeof($professors), $fieldRightFormat);
    } else {
        $workSheet -> write(4, 1, _STUDENTS, $fieldLeftFormat);
        $workSheet -> writeNumber(4, 2, sizeof($infoLesson -> getUsers('student')), $fieldRightFormat);
        $workSheet -> write(5, 1, _PROFESSORS, $fieldLeftFormat);
        $workSheet -> writeNumber(5, 2, sizeof($infoLesson -> getUsers('professor')), $fieldRightFormat);
    }
    $workSheet -> write(6, 1, _PRICE, $fieldLeftFormat);
    $workSheet -> write(6, 2, $infoLesson -> lesson['price'].' '.$GLOBALS['CURRENCYNAMES'][$GLOBALS['configuration']['currency']], $fieldRightFormat);
    $workSheet -> write(7, 1, _LANGUAGE, $fieldLeftFormat);
    $workSheet -> write(7, 2, $languages[$infoLesson -> lesson['languages_NAME']], $fieldRightFormat);
    $workSheet -> write(8, 1, _ACTIVE, $fieldLeftFormat);
    $workSheet -> write(8, 2, $infoLesson -> lesson['active'] ? _YES : _NO, $fieldRightFormat);

    //participation info
    $workSheet -> write(9, 1, _LESSONPARTICIPATIONINFO, $headerFormat);
    $workSheet -> mergeCells(9, 1, 9, 2);
    //$workSheet -> setColumn(9, 9, 30);

    $workSheet -> write(10, 1, _COMMENTS, $fieldLeftFormat);
    $workSheet -> write(10, 2, $lessonInfo['comments'], $fieldRightFormat);
    $workSheet -> write(11, 1, _MESSAGES, $fieldLeftFormat);
    $workSheet -> write(11, 2, $lessonInfo['messages'], $fieldRightFormat);
    $workSheet -> write(12, 1, _CHATMESSAGES, $fieldLeftFormat);
    $workSheet -> write(12, 2, $lessonInfo['chatmessages'], $fieldRightFormat);

    //lesson content info
    $workSheet -> write(14, 1, _LESSONCONTENTINFO, $headerFormat);
    $workSheet -> mergeCells(14, 1, 14, 2);
    //$workSheet -> setColumn(14, 14, 30);

    $workSheet -> write(15, 1, _THEORY, $fieldLeftFormat);
    $workSheet -> write(15, 2, $lessonInfo['theory'], $fieldRightFormat);
    if ($GLOBALS['configuration']['disable_projects'] != 1) {
        $workSheet -> write(16, 1, _PROJECTS, $fieldLeftFormat);
        $workSheet -> write(16, 2, $lessonInfo['projects'], $fieldRightFormat);
    }
    $workSheet -> write(17, 1, _EXAMPLES, $fieldLeftFormat);
    $workSheet -> write(17, 2, $lessonInfo['examples'], $fieldRightFormat);
    if ($GLOBALS['configuration']['disable_tests'] != 1) {
        $workSheet -> write(18, 1, _TESTS, $fieldLeftFormat);
        $workSheet -> write(18, 2, $lessonInfo['tests'], $fieldRightFormat);
    }
    $workSheet -> setColumn(3, 3, 5);

    //lesson users info
    $workSheet -> write(1, 4, _USERSINFO, $headerFormat);
    $workSheet -> mergeCells(1, 4, 1, 11);
    $workSheet -> setColumn(4, 10, 15);

    $workSheet -> write(2, 4, _LOGIN, $titleLeftFormat);
    $workSheet -> write(2, 5, _LESSONROLE, $titleLeftFormat);
    $workSheet -> write(2, 6, _TIME, $titleCenterFormat);
    $workSheet -> write(2, 7, _CONTENT, $titleCenterFormat);
    if ($GLOBALS['configuration']['disable_tests'] != 1) {
        $workSheet -> write(2, 8, _TESTS, $titleCenterFormat);
    }
    if ($GLOBALS['configuration']['disable_projects'] != 1) {
        $workSheet -> write(2, 9, _PROJECTS, $titleCenterFormat);
    }
    $workSheet -> write(2, 10, _COMPLETED, $titleCenterFormat);
    $workSheet -> write(2, 11, _GRADE, $titleCenterFormat);

    $roles = MagesterLessonUser :: getLessonsRoles(true);

    $row = 3;
    foreach ($students as $user) {
        $workSheet -> write($row, 4, formatLogin($user['login']), $fieldLeftFormat);
        $workSheet -> write($row, 5, $roles[$user['role']], $fieldLeftFormat);
        $workSheet -> write($row, 6, $user['time_in_lesson']['time_string'], $fieldCenterFormat);
        $workSheet -> write($row, 7, formatScore($user['overall_progress']['percentage'])."%", $fieldCenterFormat);
        if ($GLOBALS['configuration']['disable_tests'] != 1) {
            $workSheet -> write($row, 8, formatScore($user['test_status']['percentage'])."%", $fieldCenterFormat);
        }
        if ($GLOBALS['configuration']['disable_projects'] != 1) {
            $workSheet -> write($row, 9, formatScore($user['project_status']['percentage'])."%", $fieldCenterFormat);
        }
        $workSheet -> write($row, 10, $user['completed'] ? _YES : _NO, $fieldCenterFormat);
        $workSheet -> write($row, 11, formatScore($user['score'])."%", $fieldCenterFormat);
        $row++;
    }
    $row += 2;

    //lesson professors info
    $workSheet -> write($row, 4, _PROFESSORSINFO, $headerFormat);
    $workSheet -> mergeCells($row, 4, $row++, 11);
    $workSheet -> write($row, 4, _LOGIN, $titleLeftFormat);
    $workSheet -> write($row, 5, _LESSONROLE, $titleLeftFormat);
    $workSheet -> write($row++, 6, _TIME, $titleCenterFormat);
    foreach ($professors as $user) {
        $workSheet -> write($row, 4, formatLogin($user['login']), $fieldLeftFormat);
        $workSheet -> write($row, 5, $roles[$user['role']], $fieldLeftFormat);
        $workSheet -> write($row, 6, $user['time_in_lesson']['time_string'], $fieldCenterFormat);
        $row++;
    }

    //Sheet with lesson's tests and questions
    if (isset($testsInfo)) {
        $workSheet = & $workBook -> addWorksheet('Tests Info');
        $workSheet -> setInputEncoding('utf-8');

        $workSheet -> setColumn(0, 0, 5);

        $workSheet -> write(1, 1, _TESTSINFORMATION, $headerFormat);
        $workSheet -> mergeCells(1, 1, 1, 2);
        $workSheet -> setColumn(1, 1, 30);
        $row = 3;
        foreach ($testsInfo as $id => $info) {
            $avgScore = array();
            $workSheet -> write($row, 1, $info['general']['name'], $titleLeftFormat);
            $workSheet -> mergeCells($row, 1, $row, 2);
            $row++;
            foreach ($info['done'] as $results) {
                $workSheet -> write($row, 1, $results['users_LOGIN'], $fieldLeftFormat);
                $workSheet -> write($row++, 2, formatScore(round($results['score'], 2))."%", $fieldCenterFormat);
                $avgScore[] = $results['score'];
            }
            if (sizeof($avgScore) > 0) {
                $workSheet -> write($row, 1, _AVERAGESCORE, $fieldLeftBoldFormat);
                $workSheet -> write($row++, 2, formatScore(round(array_sum($avgScore) / sizeof($avgScore), 2))."%", $fieldCenterFormat);
            } else {
                $workSheet -> write($row++, 1, _NODATAFOUND, $fieldLeftItalicFormat);
            }
            $row++;
        }
    }

    if (sizeof($lessonQuestions) > 0) {
        $workSheet -> setColumn(3, 3, 3);
        $workSheet -> write(1, 4, _QUESTIONSINFORMATION, $headerFormat);
        $workSheet -> mergeCells(1, 4, 1, 8);
        $workSheet -> setColumn(4, 4, 30);
        $workSheet -> setColumn(5, 8, 20);
        $row = 3;

        $workSheet -> write($row, 4, _QUESTION, $titleLeftFormat);
        $workSheet -> write($row, 5, _QUESTIONTYPE, $titleCenterFormat);
        $workSheet -> write($row, 6, _DIFFICULTY, $titleCenterFormat);
        $workSheet -> write($row, 7, _TIMESDONE, $titleCenterFormat);
        $workSheet -> write($row++, 8, _AVERAGESCORE, $titleCenterFormat);

        $questionShortHands = array('multiple_one' => 'MC',
                                            'multiple_many' => 'MCMA',
                                            'match' => 'MA',
                                            'empty_spaces' => 'FB',
                                            'raw_text' => 'OA',
                                            'true_false' => 'YN',
                       'drag_drop' => 'DD');

        foreach ($questionsInfo as $id => $questionInfo) {
            $workSheet -> write($row, 4, $questionInfo['text'], $fieldLeftFormat);
            $workSheet -> write($row, 5, $questionShortHands[$questionInfo['type']], $fieldCenterFormat);
            $workSheet -> write($row, 6, Question :: $questionDifficulties[$questionInfo['difficulty']], $fieldCenterFormat);
            $workSheet -> write($row, 7, $questionInfo['times_done'], $fieldCenterFormat);
            $workSheet -> write($row, 8, formatScore($questionInfo['avg_score'])."%", $fieldCenterFormat);
            $row++;
        }

        $row++;
        $workSheet -> write($row++, 4, _MCEXPLANATION, $fieldLeftFormat);
        $workSheet -> write($row++, 4, _MCMAEXPLANATION, $fieldLeftFormat);
        $workSheet -> write($row++, 4, _MAEXPLANATION, $fieldLeftFormat);
        $workSheet -> write($row++, 4, _FBEXPLANATION, $fieldLeftFormat);
        $workSheet -> write($row++, 4, _OAEXPLANATION, $fieldLeftFormat);
        $workSheet -> write($row, 4, _YNEXPLANATION, $fieldLeftFormat);

    }

    //Sheet with tests matrix
    if (isset($testsInfo)) {
        $workSheet = & $workBook -> addWorksheet('Tests Matrix');
        $workSheet -> setInputEncoding('utf-8');

        $workSheet -> write(0, 0, _TESTSMATRIX, $headerFormat);
        $workSheet -> setColumn(0, 0, 40);
        $workSheet -> mergeCells(0, 0, 0, sizeof($testsInfo));

        $rows = array();
        $row = 2;
        $column = 0;
        foreach ($students as $login => $user) {
            $rows[$login] = $row;
            $workSheet -> write($row++, $column, formatLogin($login), $fieldLeftFormat);
        }
        //$row    = 1;
        $column = 1;
        //pr($testsInfo['done']);exit;
        foreach ($testsInfo as $id => $info) {
            $row = 1;
            $workSheet -> setColumn($column, $column, 20);
            $workSheet -> write($row, $column, $info['general']['name'], $fieldCenterFormat);
            foreach ($info['done'] as $results) {
             if (isset($rows[$results['users_LOGIN']])) {
                 $workSheet -> write($rows[$results['users_LOGIN']], $column, formatScore(round($results['score'], 2))."%", $fieldCenterFormat);
             }
            }
            $column++;
        }
    }

    //Sheet with lesson's projects
    if (sizeof($lessonProjects) > 0 && $GLOBALS['configuration']['disable_projects'] != 1) {
        $workSheet = & $workBook -> addWorksheet('Projects Info');
        $workSheet -> setInputEncoding('utf-8');

        $workSheet -> setColumn(0, 0, 5);

        $workSheet -> write(1, 1, _PROJECTSINFORMATION, $headerFormat);
        $workSheet -> mergeCells(1, 1, 1, 3);
        $workSheet -> setColumn(1, 1, 30);
        $row = 3;
        foreach ($lessonProjects as $id => $project) {
            $avgScore = array();
            $workSheet -> write($row, 1, $project -> project['title'], $titleLeftFormat);
            $workSheet -> mergeCells($row, 1, $row, 3);
            $row++;
            foreach ($projectsInfo[$id]['done'] as $results) {
                $workSheet -> write($row, 1, $results['users_LOGIN'], $fieldLeftFormat);
                $workSheet -> write($row, 2, formatScore(round($results['grade'], 2))."%", $fieldCenterFormat);
                $workSheet -> write($row++, 3, $results['comments'], $fieldCenterFormat);
                $avgScore[] = formatScore($results['grade'])."%";
            }
            if (sizeof($avgScore) > 0) {
                $workSheet -> write($row, 1, _AVERAGESCORE, $fieldLeftBoldFormat);
                $workSheet -> write($row++, 2, formatScore(round(array_sum($avgScore) / sizeof($avgScore), 2))."%", $fieldCenterFormat);
            } else {
                $workSheet -> write($row++, 1, _NODATAFOUND, $fieldLeftItalicFormat);
            }
            $row++;
        }


        //Sheet with projects matrix
        $workSheet = & $workBook -> addWorksheet('Projects Matrix');
        $workSheet -> setInputEncoding('utf-8');

        $workSheet -> setColumn(0, 0, 40);
        $workSheet -> write(0, 0, _PROJECTSMATRIX, $headerFormat);

        $rows = array();
        $row = 2;
        $column = 0;
        foreach ($students as $login => $user) {
            $rows[$login] = $row;
            $workSheet -> write($row++, $column, $login." (".$user['name']." ".$user['surname'].")", $fieldLeftFormat);
        }
        $row = 1;
        $column = 1;
        foreach ($lessonProjects as $id => $project) {
            $row = 1;
            $workSheet -> setColumn($column, $column, 20);
            $workSheet -> write($row++, $column, $project -> project['title'], $fieldCenterFormat);
            foreach ($projectsInfo[$id]['done'] as $results) {
                if (in_array($results['users_LOGIN'], array_keys($rows))) {
                    $workSheet -> write($rows[$results['users_LOGIN']], $column, formatScore(round($results['grade'], 2))."%", $fieldCenterFormat);
                }
            }
            $column++;
        }
    }

    //add a separate sheet for each distinct student of that lesson
    //$doneTests        = MagesterStats :: getStudentsDoneTests($infoLesson -> lesson['id']);
    $assignedProjects = MagesterStats :: getStudentsAssignedProjects($infoLesson -> lesson['id']);
    foreach ($students as $user) {
        $workSheet = & $workBook -> addWorksheet($user['login']);
        $workSheet -> setInputEncoding('utf-8');

        $row = 0;
        $workSheet -> write($row, 0, $infoLesson -> lesson['name'], $headerBigFormat);
        $workSheet -> mergeCells($row, 0, $row++, 9);
        $workSheet -> write($row, 0, formatLogin($user['login']), $fieldCenterFormat);
        $workSheet -> mergeCells($row, 0, $row++, 9);

        $workSheet -> setColumn(0, 0, 40);

        $workSheet -> write(++$row, 0, _LESSONROLE, $headerFormat);
        $workSheet -> mergeCells($row, 0, $row, 1);
        $workSheet -> write(++$row, 0, $roles[$user['role']], $fieldCenterFormat);
        $workSheet -> mergeCells($row, 0, $row, 1);
        $workSheet -> write(++$row, 0, _TIMEINLESSON, $headerFormat);
        $workSheet -> mergeCells($row, 0, $row, 1);
        $workSheet -> write(++$row, 0, $user['time_in_lesson']['time_string'], $fieldCenterFormat);
        $workSheet -> mergeCells($row, 0, $row, 1);
        $workSheet -> write(++$row, 0, _STATUS, $headerFormat);
        $workSheet -> mergeCells($row, 0, $row, 1);
        $workSheet -> write(++$row, 0, _COMPLETED, $fieldCenterFormat);
        $workSheet -> write($row, 1, $user['completed'] ? _YES : _NO, $fieldCenterFormat);
        $workSheet -> write(++$row, 0, _GRADE, $fieldCenterFormat);
        $workSheet -> write($row, 1, formatScore($user['score'])."%", $fieldCenterFormat);

        $workSheet -> write(++$row, 0, _CONTENT, $headerFormat);
        $workSheet -> mergeCells($row, 0, $row, 1);
        $workSheet -> write(++$row, 0, formatScore($user['overall_progress']['percentage'])."%", $fieldCenterFormat);
        $workSheet -> mergeCells($row, 0, $row, 1);
/*

        $row++;

        if (sizeof($doneTests[$user['login']]) > 0 && $GLOBALS['configuration']['disable_tests'] != 1) {

            $avgScore = array();

            $workSheet -> write($row, 0, _TESTS, $headerFormat);

            $workSheet -> mergeCells($row, 0, $row++, 1);

            foreach ($doneTests[$user['login']] as $test) {

                $workSheet -> write($row, 0, $test['name'], $fieldLeftFormat);

                $workSheet -> write($row, 1, formatScore($test['score'])."%", $fieldCenterFormat);

                $avgScore[] = $test['score'];

                $row++;

            }

            $row +=2;

            $workSheet -> write($row, 0, _AVERAGESCORE, $titleLeftFormat);

            $workSheet -> write($row++, 1, formatScore(array_sum($avgScore) / sizeof($avgScore))."%", $fieldCenterFormat);

        }



        if (sizeof($assignedProjects[$user['login']]) > 0 && $GLOBALS['configuration']['disable_projects'] != 1) {

            $workSheet -> write($row, 0, _PROJECTS, $headerFormat);

            $workSheet -> mergeCells($row, 0, $row, 1);

            $row++;

            foreach ($assignedProjects[$user['login']] as $project) {

                $workSheet -> write($row, 0, $project['title'], $fieldCenterFormat);

                $workSheet -> write($row, 1, formatScore($project['grade'])."%", $fieldCenterFormat);

                $workSheet -> write($row, 2, $project['comments'], $fieldCenterFormat);

                $row++;

            }

        }

*/
    }
    $workBook -> close();
    exit(0);
} elseif (isset($_GET['pdf']) && $_GET['pdf'] == 'lesson') {
 $groupname = $branchName = false;
 try {
  $group = new MagesterGroup($_GET['group_filter']);
  $groupname = $group -> group['name'];
 } catch (Exception $e) {/*Do nothing if group filters are not specified*/}
 $reportTitle = _REPORT.": ".$infoLesson -> lesson['name'];
 if ($groupname) {
  $reportTitle .= " "._FORGROUP.": ".$groupname;
  !$branchName OR $reportTitle .= _ANDBRANCH.": ".$branchName;
 } elseif ($branchName) {
  $reportTitle .= " "._FORBRANCH.": ".$branchName;
 }
 $languages = MagesterSystem :: getLanguages(true);
    // Get only filtered users
    $constraints = array('archive' => false, 'return_objects' => false, 'table_filters' => $stats_filters);
    $filteredUsers = $infoLesson -> getLessonStatusForUsers($constraints);
    $students = array();
    $professors = array();
    foreach ($filteredUsers as $user) {
     if ($user['user_type'] == "student") {
      $students[$user['login']] = $user;
     } elseif ($user['user_type'] == "professor") {
      $professors[$user['login']] = $user;
     }
    }
    if ($groupname || $branchName) {
     $studentsSize = sizeof($students);
        $professorsSize = sizeof($professors);
    } else {
        $studentsSize = sizeof($infoLesson -> getUsers('student'));
        $professorsSize = sizeof($infoLesson -> getUsers('professor'));
    }
 $pdf = new MagesterPdf($reportTitle);
 $info = array(array(_LESSON, $infoLesson -> lesson['name']),
      array(_CATEGORY, $directionsPaths[$infoLesson -> lesson['directions_ID']]),
      array(_STUDENTS, $studentsSize),
      array(_PROFESSORS, $professorsSize),
      array(_LANGUAGE, $languages[$infoLesson -> lesson['languages_NAME']]),
      array(_ACTIVENEUTRAL, $infoLesson -> lesson['active'] ? _YES : _NO));
 $pdf -> printInformationSection(_BASICINFO, $info);
 if ($lessonInfo['comments'] || $lessonInfo['messages'] || $lessonInfo['chatmessages']) {
  $info = array(array(_COMMENTS, ($lessonInfo['comments'])),
       array(_MESSAGES, $lessonInfo['messages']),
       array(_CHATMESSAGES, $lessonInfo['chatmessages']));
  $pdf -> printInformationSection(_LESSONPARTICIPATIONINFO, $info);
 }
 $info = array(array(_THEORY, $lessonInfo['theory']),
      array(_EXAMPLES, $lessonInfo['examples']));
 if ($GLOBALS['configuration']['disable_projects'] != 1) {
  $info[] = array(_PROJECTS, $lessonInfo['projects']);
 }
 if ($GLOBALS['configuration']['disable_tests'] != 1) {
  $info[] = array(_TESTS, $lessonInfo['tests']);
 }
 $pdf -> printInformationSection(_LESSONCONTENTINFO, $info);
 $formatting = array(_USER => array('width' => '25%', 'fill' => false),
      _TIMEINLESSON => array('width' => '15%', 'fill' => false),
      _CONTENT => array('width' => '10%', 'fill' => false, 'align' => 'C'),
      _TESTS => array('width' => '10%', 'fill' => false, 'align' => 'C'),
      _PROJECTS => array('width' => '10%', 'fill' => false, 'align' => 'C'),
      _COMPLETED => array('width' => '20%', 'fill' => false),
      _SCORE => array('width' => '10%', 'fill' => false, 'align' => 'R'));
 $data = array();
 foreach ($students as $user) {
  $data[] = array(_USER => formatLogin($user['login']),
      _TIMEINLESSON => $user['time_in_lesson']['time_string'],
      _CONTENT => formatScore($user['overall_progress']['percentage'])."%",
      _TESTS => formatScore($user['test_status']['percentage'])."%",
      _PROJECTS => formatScore($user['project_status']['percentage'])."%",
      _COMPLETED => $user['completed'] ? _YES.', '._ON.' '.formatTimestamp($user['timestamp_completed']) : _NO,
      _SCORE => formatScore($user['score'])."%");
    }
 $pdf->printDataSection(_USERSINFO, $data, $formatting);
 $data = array();
 foreach ($professors as $user) {
  $data[] = array(_USER => formatLogin($user['login']),
      _TIMEINLESSON => $user['time_in_lesson']['time_string']);
    }
 $pdf->printDataSection(_PROFESSORSINFO, $data, $formatting);
    if ($GLOBALS['configuration']['disable_tests'] != 1) {
  $formatting = array(_USER => array('width' => '25%', 'fill' => false),
       _SCORE => array('width' => '15%', 'fill' => false));
  foreach ($testsInfo as $id => $info) {
   $data = array();
   foreach ($info['done'] as $results) {
    $avgScore[] = $results['score'];
    $data[] = array(_USER => formatLogin($results['users_LOGIN']),
        _SCORE => formatScore(round($results['score'], 2))."%");
   }
   $data[] = array(_USER => '',
       _SCORE => formatScore(round(array_sum($avgScore) / sizeof($avgScore), 2))."%");
   $pdf->printDataSection(_TESTSINFORMATION.': '.$info['general']['name'], $data, $formatting);
  }
  if (sizeof($lessonQuestions) > 0) {
   $formatting = array(_QUESTION => array('width' => '50%', 'fill' => false),
        _QUESTIONTYPE => array('width' => '20%', 'fill' => false),
        _DIFFICULTY => array('width' => '10%', 'fill' => false, 'align' => 'C'),
        _TIMESDONE => array('width' => '10%', 'fill' => false, 'align' => 'C'),
        _AVERAGESCORE => array('width' => '10%', 'fill' => false, 'align' => 'C'));
   $data = array();
   foreach ($questionsInfo as $id => $questionInfo) {
    $data[] = array(_QUESTION => $questionInfo['text'],
        _QUESTIONTYPE => Question :: $questionTypes[$questionInfo['type']],
        _DIFFICULTY => Question :: $questionDifficulties[$questionInfo['difficulty']],
        _TIMESDONE => $questionInfo['times_done'],
        _AVERAGESCORE => formatScore($questionInfo['avg_score'])."%");
      }
   $pdf->printDataSection(_QUESTIONSINFORMATION, $data, $formatting);
        }
    }
 $pdf -> OutputPdf('lesson_form_'.$infoLesson -> lesson['name'].'.pdf');
 exit;
}
