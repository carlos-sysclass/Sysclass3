<?php
class module_xgeomap extends MagesterExtendedModule {
    // CORE MODULE FUNCTIONS
    // GET FROM CONFIG
	const GOOGLEMAPS_URL_BYADDRESS_TPL = 'https://maps.googleapis.com/maps/api/geocode/%2$s?address=%1$s&sensor=false&language=pt-BR';
	//const GOOGLEMAPS_URL_BYGEOCODE_TPL = 'https://maps.googleapis.com/maps/api/geocode/%2$s?address=%1$s&sensor=false';
	 
    public function getName() {
        return "XGEOMAP";
    }
    public function getPermittedRoles() {
        return array("administrator");
    }
    public function isLessonModule() {
        return false;
    }
    
    /* MAIN-INDEPENDENT MODULE INFO, PAGES, TEMPLATES, ETC... */
    /* ACTIONS FUNCTIONS */
    /* HOOK ACTIONS FUNCTIONS */  
    /* DATA MODEL FUNCTIONS /*/
    public function encodeAddress($address) {
    	// FORMATO: 
    	// Rua Coronel Dulcídio, 150, Batel, Curitiba - Paraná, Brasil
    	if (empty($address['endereco'])) {
    		return false;
    	}
    	$result = 
	     	$address['endereco'] . 
			(empty($address['numero']) 	? '' : ', ' . $address['numero']) .
			(empty($address['bairro']) 	? '' : ', ' . $address['bairro']) .
			(empty($address['cidade']) 	? '' : ', ' . $address['cidade']) .
			(empty($address['uf']) 		? '' : '- ' . $address['uf']); // CHECK FOR LONG NAMES
			
		return urlencode($result);
    }
	public function createAddress($endereco, $numero, $bairro, $cidade, $uf) {
		// USE THIS FUNCTION TO SEARCH, CASE IS MISSING DATA
		$address = array();
     	$address['endereco']	= $endereco;
		$address['numero']		= $numero;
		$address['bairro']		= $bairro;
		$address['cidade']		= $cidade;
		$address['uf']			= $uf;
		
		return $address;
	}
    
    public function calculateLatLngByAddress($address, $format = 'json') {
    	if (is_object($address) || is_array($address)) {
    		$address = $this->encodeAddress($address);
    		if (!$address) {
    			return false;
    		}
    	}
    	if (!in_array($format, array('xml', 'json'))) {
    		$format = 'json';
    	} 
    	
    	$url = sprintf(self::GOOGLEMAPS_URL_BYADDRESS_TPL, $address, $format);
    	
    	$ch = curl_init($url);
		$encoded = array();
		// include GET as well as POST variables; your needs may vary.
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$output = curl_exec($ch); 
		curl_close($ch);
		
		if ($output !== FALSE) {
			if ($format == 'json') {
				$result = json_decode($output, true);
				
				if ($result['status'] == 'OK') {
					return $result;
				}
			} elseif ($format == 'xml') {
				// CREATE A XML OBJECT, DOMDOCUMENT, ETC...
				return $output;
			}
		} else {
			return false;
		}
        return true;
	}
}
