<?php
namespace Sysclass\Modules\Certificate;

use Phalcon\Mvc\Model,
    Dompdf\Dompdf,
    Dompdf\Canvas,
    Sysclass\Models\Organizations\Organization;

/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/certificate")
 */

class CertificateModule extends \SysclassModule
{
    
    #RENDERIZAR TEMPLATE
    /*$courseModel->isCompleted() ;
    $courseModel->getCertificateTemplate() // RETORNA template3
    $html = $this->view->render("certificate/template3.cert");
    */    

    /**
    * [ add a description ]
    *
    * @Get("/view/{id}")
    */    
    public function printCertificate($id)
    {
        //var_dump($courseModel->getCertificateTemplate($id)); // RETORNA template3

        //themes/default/templates/certificate
        //$html = $this->view->render("certificate/template3.cert");
        
        /*var_dump($this->user->getCourses());
        exit;

        foreach($this->user->getCourses() as $course) {
            echo "<br>=> ".$course;
        }   

        exit;*/
        echo "=> ".$organization = Organization::findFirst();

        $this->view->setVar("organization", $organization);

        $html = $this->view->render("certificate/default.cert");
        exit;

   /* $html ="
    <table border='15' align='center' width='99%' cellspacing='0' cellpadding='0'><tr><td>
        <table border='0' align='center' width='100%' cellspacing='0' cellpadding='0'>
            <tr><td colspan='3' align='center' style='height:150px; font-size:35px'>Certificate of Completion</td></tr>
            <tr><td colspan='3' align='center' style='height:50px'>Checked the __________________________ This certificate</td></tr>    
            <tr><td colspan='3' align='center' style='height:50px'>for participation in the online course __________________________</td></tr>    
            <tr><td colspan='3' align='center' style='height:50px'>with a workload of ________ hours.</td></tr>
            <tr>
                <td align='center' style='height:200px'>___________<p>Instructor</td>m
                <td align='center' style='height:200px'>___________<p>Institution</td>
                <td align='center' style='height:200px'>___________<p>Student</td>
            </tr>    
        </table>
    </td></tr></table>";*/
        //exit;
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'landscape');
        $dompdf->render();
        $dompdf->stream("$id");
        $pdf->stream(date('d/m/Y').'certificado.pdf', array('Attachment'=>true));
    }
}
?>