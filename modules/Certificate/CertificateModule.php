<?php
namespace Sysclass\Modules\Certificate;

use Sysclass\Models\Courses\Course,
    Sysclass\Models\Users\User,
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
        $data = array(1);

        return array(
            'type'  => 'warning',
            'count' => $data[0],
            'text'  => $this->translate->translate('Certificates'),
            'link'  => array(
                'text'  => $this->translate->translate('View'),
                'link'  => $this->getBasePath()
            )
        );
    }

    /* INotifyable */
    public function getAllActions() {

    }

    public function processNotification($action, Event $event) {
        switch($action) {
            case "make-avaliable" : {
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


                    return array(
                        'status' => true
                    );

                } else {
                    return array(
                        'status' => false,
                        'unqueue' => true
                    );
                }
            }
        }
    }

    /**
    * [ add a description ]
    *
    * @Get("/view/{id}")
    */    
    public function printCertificate($id)
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
                $this->translate->translate('Warning: Your course is not completed yet!'),
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