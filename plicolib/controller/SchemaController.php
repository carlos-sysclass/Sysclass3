<?php 
/**
 * Schema Controller
 *
 */
class SchemaController extends SessionManager
{
    public function authorize()
    {
        return TRUE;

    }
   	/**
	 * Get leads by id
	 *
	 * @url GET /
     * @url GET /:resource
	 */
	public function getSchemaAction($resource)
	{
        /*
        'nome'
        'email'
        'telefone'
        'celular'
        'datanascimento'

        app_name
        app_version
        app_description
        app_email
        app_license
        app_license_url
        */
        $plicolib = PlicoLib::instance();
    
        $data = array(
            'apiVersion'        => $plicolib->get("app_version"),
            'swaggerVersion'    => '1.2',
            
            'apis'  => $plicolib->server()->getDescriptors(),
            "info"  =>  array(
                "title"             => $plicolib->get("client_name") . ' - ' . $plicolib->get("app_name"),
                "description"       => $plicolib->get("app_description"),
                "termsOfServiceUrl" => "",
                "contact"           => $plicolib->get("app_email"),
                "license"           => $plicolib->get("app_license"),
                "licenseUrl"        => $plicolib->get("app_license_url")
            )
        );

        if (!empty($resource)) {
            $data['resourcePath']   = '/' .$resource;
            $data['basePath']       = $plicolib->get('http/fqdn');
            $data['apis']           =  $plicolib->server()->getDescriptors($resource);
            $data['produces']       = array("application/json");
        }

        //$data = json_decode($json, true);
        //echo "<pre>";
        //var_dump($plicolib->get());
        //var_dump($data);
        //exit;
 
        return $data;
	}
}
