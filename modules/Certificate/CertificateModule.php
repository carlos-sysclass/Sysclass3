<?php
namespace Sysclass\Modules\Certificate;

use Sysclass\Models\Users\User,
    Sysclass\Models\Certificates\Certificate,
    Sysclass\Models\Content\Course,
    Sysclass\Models\Courses\Tests\Unit as UnitTest,
    Dompdf\Dompdf,
    Dompdf\Canvas,
    Sysclass\Models\Organizations\Organization,
    Sysclass\Services\MessageBus\INotifyable,
    Sysclass\Collections\MessageBus\Event;

/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/certificate")
 */

class CertificateModule extends \SysclassModule implements \ISummarizable, INotifyable
{
    

    /* ISummarizable */
    public function getSummary() {
        $certificates = Certificate::find(array(
            'conditions' => 'user_id = ?0',
            'bind' => array($this->user->id),
            'order' => 'id ASC'
        ));

        $info = array(
            'type'  => 'success',
            'format' => "dropdown",
            'count' => $certificates->count(),
            'text'  => $this->translate->translate('Certificates'),
            'name'  => $this->translate->translate('View'),
        );

        if ($certificates->count() > 0) {
            $info['links'] = array();

            foreach($certificates as $index => $cert) {
                $info['links'][]  = array(
                    //'text'  => $this->translate->translate('View'),
                    'link'  => $this->getBasePath() . "print/" . $cert->id,
                    'text' => ($index+1) . '&ordm;',
                    'name' => $cert->name,
                    'target' => '_blank'
                );
            }
            /*
            $info['link']  = array(
                'text'  => $this->translate->translate('View'),
                'link'  => $this->getBasePath() . "print/" . $certificates->getFirst()->id,
                'target' => '_blank'
            );
            */
        } else {
            $info['link']  = array(
                'text'  => $this->translate->translate('View'),
                'link'  => "javascript:void(0);",
                'target' => '_blank'
            );
        }

        //var_dump($info);
        //exit;

        return $info;
    }

    /* INotifyable */
    public function getAllActions() {

    }

    public function processNotification($action, Event $event) {
        switch($action) {
            case "make-avaliable" : {
                /*
                $data = $event->data;
                $course = Course::findFirstById($data['course_id']);
                $user = User::findFirstById($data['user_id']);

                if ($course && $user) {
                    $this->notification->createForUser(
                        $user,
                        sprintf(
                            'You have a certificate avaliable for course %s', 
                            $course->name
                        ),
                        'info',
                        array(
                            'text' => "View",
                            'link' => $this->getBasePath() . "view/" . $course->id
                        )
                    );

                    // CREATE THE CERTIFICATE ON THE DATABASE


                    return array(
                        'status' => true
                    );
                } else {
                    */
                    return array(
                        'status' => false,
                        'unqueue' => true
                    );
                //}
                break;
            }
            case "create-certificate": {
                $data = $event->data;

                $type = $data['trigger'];

                if ($type == "test") {
                    if ($this->createTestCertificate($data['user_id'], $data['entity_id'])) {
                        return array(
                            'status' => true,
                            'unqueue' => true
                        );
                    }
                } elseif ($type == "course") {
                    if ($this->createCourseCertificate($data['user_id'], $data['entity_id'])) {
                        return array(
                            'status' => true,
                            'unqueue' => true
                        );
                    } else {
                        var_dump($data);
                    }

                }
                return array(
                    'status' => false,
                    'unqueue' => false
                );
            }
        }
    }

    public function createTestCertificate($user_id, $test_id) {
        // GET CLASS AND COURSE NAME
        $unitTest = UnitTest::findFirstById($test_id);
        $user = User::findFirstById($user_id);
        if ($lessonTest && $user) {
            $module = $lessonTest->getCourse();
            if ($module) {
                $course = $module->getProgram();
                if ($course) {
                    //$course = $courses->getFirst();

                    $vars = array(
                        'username' => $user->name . " " . $user->surname,
                        'coursename' => $course->name,
                        'modulename' => $module->name,
                        'date' => date("d/m/Y")
                    );

                    $certificate = Certificate::findFirst(array(
                        'conditions' => "user_id = ?0 AND entity_id = ?1 AND type = 'test'",
                        'bind' => array($user_id, $test_id)
                    ));

                    if (!$certificate) {
                        $certificate = new Certificate();
                        $certificate->user_id = $user_id;
                        $certificate->entity_id = $test_id;
                        $certificate->type = 'test';
                    }
                    $certificate->name = $module->name;

                    $certificate->vars = json_encode($vars);
                    if ($certificate->save()) {

                        $this->notification->createForUser(
                            $user,
                            $this->translate->translate('You have a new Certificate: %s', $module->name),
                            'info',
                            array(
                                'text' => "View",
                                'link' => $this->getBasePath() . "print/" . $certificate->id
                            ),
                            false,
                            "CERTIFICATE:" . "U" . $certificate->user_id . "E" . $certificate->entity_id . "T" . $certificate->type
                        );

                        return true;
                    }
                }
            }
        }
        return false;
    }

    // PREVIOUSLY CLASS!!!
    public function createCourseCertificate($user_id, $course_id) {
        // GET CLASS AND COURSE NAME
        $module = Course::findFirstById($course_id);
        $user = User::findFirstById($user_id);
        if ($module && $user) {
            //$courses = $module->getUnits();
            //if ($courses->count() > 0) {
                //$course = $courses->getFirst();

                $vars = array(
                    //'username' => $user->name . " " . $user->surname,
                    //'coursename' => $course->name,
                    //'modulename' => $module->name,
                    'timestamp' => time(),
                    'type' => 'course',
                    'entity_id' => $course_id,
                    'user_id' => $user_id
                );

                $certificate = Certificate::findFirst(array(
                    'conditions' => "user_id = ?0 AND entity_id = ?1 AND type = 'course'",
                    'bind' => array($user_id, $course_id)
                ));

                $notify = false;

                if (!$certificate) {
                    $certificate = new Certificate();
                    $certificate->user_id = $user_id;
                    $certificate->entity_id = $course_id;
                    $certificate->type = 'course';

                    $notify = true;
                }
                $certificate->name = $module->name;

                $certificate->vars = json_encode($vars);
                if ($certificate->save()) {

                    if ($notify) {
                        $this->notification->createForUser(
                            $user,

                            //$this->translate->translate('You have a certificate avaliable for module %s', array($module->name)),
                            sprintf('You have a new Certificate:  %s',$module->name),


                            'info',
                            array(
                                'text' => "Visualizar",
                                'link' => $this->getBasePath() . "print/" . $certificate->id
                            ),
                            false,
                            "CERTIFICATE:" . "U" . $certificate->user_id . "E" . $certificate->entity_id . "T" . $certificate->type
                        );
                    } else {
                        // UPDATE NOTIFICATION

                    }

                    return true;
                }
            //}

        }
        return false;
    }

    /**
    * [ add a description ]
    *
    * @Get("/print/{id}")
    */    
    public function printCertificate($id)
    {
        //var_dump($courseModel->getCertificateTemplate($id)); // RETORNA template3

        /*var_dump();
        exit;
        */
        $canContinue = false;

        $certificate = Certificate::findFirstById($id);

        if ($certificate && $certificate->user_id == $this->user->id) {
            // CHECK IF THE TEST IS ALREADY COMPLETED
            $canContinue = true;
        }
        /*
        $courses = $this->user->getUserCourses();

        foreach($courses as $course) {
            if ($course->course_id = $id) {
                $canContinue = $course->isCompleted();
                break;
            }
        }
        */
        if ($canContinue) {

            $vars = json_decode($certificate->vars, true);

            $vars['id'] = $id;

            // CALCULATE
            switch($certificate->type) {
                case 'course' : {
                    $module = Course::findFirstById($certificate->entity_id);

                    $program = $module->getProgram();
                    $language = $program->getLanguage();
                    if ($language) {
                        $this->translate->setSource($language->code);

                        $locale = $language->code . "_" . $language->country_code . "." . "utf8";

                        setlocale(LC_TIME, $locale);
                    }

                    //$this->translate->setSource($language->code);
                    //var_dump($language->toArray());
                    //exit;
                    if ($module) {
                        $user = User::findFirstById($certificate->user_id);

                        $vars['modulename'] = $module->name;
                        $vars['username'] = $user->name . " " . $user->surname;
                    } else {
                        $this->redirect(
                            '/dashboard',
                            $this->translate->translate("Warning: An error occured when the system is trying to complete your request!"),
                            'warning'
                        );
                        
                        return;
                    }
                    break;
                }
            }


            $vars['datetime'] = \DateTime::createFromFormat("U", $vars['timestamp']);
 
            $this->view->setVars($vars);
            //$course->complete();
            //$this->view->setVar("course", $course->getCourse());    

            $organization = Organization::findFirst();
            $this->view->setVar("organization", $organization);
            


            $this->assets
                ->collection('header')
                /*
                ->setPrefix(sprintf(
                    '%s://%s',
                    $this->request->getScheme(),
                    $this->request->getHttpHost()
                ))
                */
                //->addCss('http://fonts.googleapis.com/css?family=Roboto', true)
                //->addCss('/assets/default/plugins/bootstrap/css/bootstrap.css', true)
                ->addCss('assets/default/css/certificate.css');

            $this->assets
                ->collection('header')
                ->addCss('assets/default/css/certificates/itaipu.css');

            

            $custom_css = sprintf(
                "assets/%s/css/certificate.css",
                $this->environment->view->theme
            );


            // ADD CSS FOR ENVIRONMENT
            if (file_exists($this->environment['path/app/www'] . "/" . $custom_css)) {
                $this->assets
                    ->collection('header')
                    ->addCss($custom_css);
            }

            $environment = $this->sysconfig->deploy->environment;

            $cert_template = "certificate/" . $environment . "/" . $certificate->type . ".cert";
            
            if (!$this->view->exists($cert_template)) {
                $cert_template = "certificate/" . $certificate->type . ".cert";
            }

            $html = $this->view->render($cert_template);
           
            
            $this->response->setContent($html);
            //return true;

            global $_dompdf_show_warnings;
            $_dompdf_show_warnings = true;

            global $_dompdf_debug;
            $_dompdf_debug = true;
            
            $pdf = new \mPdf("","A4-L");

            $pdf->WriteHTML($html, 0);
            $br = rand(0, 100000);
            $ispis = "Certificado-" . $id . ".pdf";

            $pdf->Output($ispis, "I");
            /*
            $dompdf->set_base_path(REAL_PATH);
            //$dompdf->set_option('isHtml5ParserEnabled', true);
            $dompdf->set_option('isRemoteEnabled', true);
            //$dompdf->set_option('debugCss', true);

            $dompdf->load_html($html);
            $dompdf->set_paper('letter', 'landscape');
            $dompdf->render();
            
            //$dompdf->stream("$id");
            $dompdf->stream(date('d/m/Y').'certificado.pdf', array('Attachment'=>true));
            */
        } else {
            //$this->response->redirect();
            $this->redirect(
                '/dashboard',
                $this->translate->translate("Warning: An error occured when the system is trying to complete your request!"),
                'warning'
            );
            
            return;
        }
    }

    /**
    * [ add a description ]
    *
    * @Get("/view/{id}")
    * @deprecated 3.2.0.150
    */    
    public function viewCertificate($id)
    {
        //var_dump($courseModel->getCertificateTemplate($id)); // RETORNA template3

        /*var_dump();
        exit;
        */

        $courses = $this->user->getUserCourses();

        $canContinue = false;
        foreach($courses as $course) {
            if ($course->course_id = $id) {
                $canContinue = $course->isCompleted();
                break;
            }
        }

        if ($canContinue) {

            //$course->complete();
            $this->view->setVar("course", $course->getCourse());    

            $organization = Organization::findFirst();
            $this->view->setVar("organization", $organization);


            $this->assets
                ->collection('header')
                /*
                ->setPrefix(sprintf(
                    '%s://%s',
                    $this->request->getScheme(),
                    $this->request->getHttpHost()
                ))
                */
                //->addCss('http://fonts.googleapis.com/css?family=Roboto', true)
                //->addCss('/assets/default/plugins/bootstrap/css/bootstrap.css', true)
                ->addCss('assets/default/css/certificate.css')
                ->addCss('assets/default/css/certificates/itaipu.css');

            $html = $this->view->render("certificate/itaipu.cert");
            
            $this->response->setContent($html);
            //return true;

            global $_dompdf_show_warnings;
            //$_dompdf_show_warnings = true;

            global $_dompdf_debug;
            //$_dompdf_debug = true;
            
            $pdf = new \mPdf("","A4-L");

            $pdf->WriteHTML($html, 0);
            $br = rand(0, 100000);
            $ispis = "Certificado-" . $id . ".pdf";

            $pdf->Output($ispis, "I");
            /*
            $dompdf->set_base_path(REAL_PATH);
            //$dompdf->set_option('isHtml5ParserEnabled', true);
            $dompdf->set_option('isRemoteEnabled', true);
            //$dompdf->set_option('debugCss', true);

            $dompdf->load_html($html);
            $dompdf->set_paper('letter', 'landscape');
            $dompdf->render();
            
            //$dompdf->stream("$id");
            $dompdf->stream(date('d/m/Y').'certificado.pdf', array('Attachment'=>true));
            */
        } else {
            //$this->response->redirect();
            $this->redirect(
                '/dashboard',
                $this->translate->translate('Warning: Your course is not completed yet.'),
                'warning'
            );
            //$this->response->redirect('/module/progress/course/' . $id);
            //$this->view->disable();
            /*
            $this->dispatcher->forward(
                array(
                    'namespace'     => 'Sysclass\Modules\Courses',
                    'controller'    => 'courses_module',
                    'action'        => 'viewpage'
                )
            );
            */
            
            return;
        }
    }
}
?>