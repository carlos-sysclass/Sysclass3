<?php
namespace Sysclass\Modules\Certificado;

use Phalcon\Mvc\Model,
    Dompdf\Dompdf,
    Dompdf\Canvas;

/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/certificado")
 */

class CertificadoModule extends \SysclassModule
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
        //$courseModel->getCertificateTemplate() // RETORNA template3

        //$html = $this->view->render("certificate/template3.cert");
        //generate some PDFs!
        var_dump($courseModel->isCompleted());

        echo "ID ".$id;
        exit;
    }
}

?>