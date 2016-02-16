<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model;

/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/certificado")
 */
echo "----";

exit;
class Certificado extends Model
{
	#RENDERIZAR TEMPLATE
	$courseModel->isCompleted() ;

	$courseModel->getCertificateTemplate() // RETORNA template3

	$html = $this->view->render("certificate/template3.cert");
	/*

	
      [ add a description ]
     
      @Get("/modelo1/{id}")
     
    
    public function printCertificate($id)
    {
        $courseModel->getCertificateTemplate() // RETORNA template3

		$html = $this->view->render("certificate/template3.cert");
    }*/
}
?>