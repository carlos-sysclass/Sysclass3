<?php
class module_area51 extends MagesterExtendedModule {

    public function getName() {
        return "AREA51";
    }

    public function getPermittedRoles() {
        return array("student", "professor", "administrator");
    }

    public function getCenterLinkInfo() {
        return array(
            'title' => _AREA51_NAME,
            'image' => $this->moduleBaseDir.'images/gradebook_logo.png',
            'link' => $this->moduleBaseUrl,
            'class' => 'grade'
        );
    }

    public function getDefaultAction() {
        return "main";
    }

    public function mainAction() {
        $smarty = $this->getSmartyVar();

        /**
         * Primeiro busca-se todas as avaliações docentes do banco de dados
         *
         * Lista de IDs dos cursos de Pós:
         * 20 - Pós-Graduação em Bioenergia - Formas Alternativas de Energia
         * 21 - Pós-Graduação em Engenharia de Software - IBM
         * 28 - Pós-Graduação em EaD
         * 31 - Pós-Graduação em Gestão com ERP
         * 39 - Pós-Graduação em Desenvolvimento de Sistemas para Mainframe
         * 40 - Pós-Graduação em Segurança da Informação
         */
        $reports = array();
        $tests = eF_getTableData(
            "tests, lessons, lessons_to_courses ltc, courses",
            "tests.*, lessons.name as lesson_name, courses.name as course_name",
            "tests.name = 'Avaliação Docente' AND tests.active = 1 AND tests.lessons_ID = lessons.id AND 
             ltc.courses_ID = courses.id AND ltc.lessons_ID = lessons.id" .
            (isset($_GET["a51_course_id"]) ? " AND courses.id = " . $_GET["a51_course_id"] : "")
        );

        /**
         * Para cada avaliação docente, busca-se todas as avaliações realizadas pelos alunos
         */
        foreach ($tests as $testKey => $test) {
            $report = array();
            $completedTests = eF_getTableData(
                "completed_tests",
                "*",
                "status = 'completed' and tests_ID = " . $test['id']
            );

            /**
             * Se o a quantidade de avaliações realizadas pelos alunos for maior que zero então
             * deve-se percorrer todas as questões do teste contabilizando assim a quantidade de
             * ocorrencias da mesma
             */
            if (sizeof($completedTests)>0) {
                foreach ($completedTests as $complKey => $completedTest) {
                    $completedTestObject = unserialize($completedTest['test']);
                    foreach ($completedTestObject->questions as $questionKey => $question) {
                        $questionText = strip_tags($question->question['text']);
                        if (!is_array($report[$questionText])) {
                            $report[$questionText] = array();
                            foreach ($question->options as $optionKey => $option) {
                                $report[$questionText][$option] = 0;
                            }
                        }
                        if (get_class($question)=='RawTextQuestion') {
                            $report[$questionText][] = $question->userAnswer;
                        } else {
                            foreach ($question->userAnswer as $usrAnsKey => $userAnswer) {
                                if ($userAnswer==1) {
                                    $report[$questionText][$question->options[$usrAnsKey]] += 1;
                                }
                            }
                        }
                    }
                }
                $reports[$test['course_name']][$test['lesson_name']] = $report;
            }
        }


        $smarty->assign("T_AREA51_REPORTS", $reports);
        //$smarty->display("/home/aribas/projects/wiseflex/sysclass.com/root/www/modules/module_area51/templates/actions/main.tpl");
        $smarty->display($this->moduleBaseDir . "templates/actions/main.tpl");
        exit;
    }


}
