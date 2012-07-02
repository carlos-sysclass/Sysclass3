<?php
class module_subscribe extends MagesterModule {

    // Mandatory functions required for module function
    public function getName() {
        return _POLOS;
    }

    public function getPermittedRoles() {
        return array("administrator","professor" ,"student");
    }
    
    public function notify($eventName, $data) {
    	switch ($eventName) {
    		case "user_register"	: {
    			
    			break;	
    		}
    		case "user_pre_register"	: {
    			// ENVIAR E-MAIL PARA O EMAIL DE VENDAS CORRESPONDENTES, COM AS INFORMAÇÕES DO LOGIN
    			//var_dump($data);
    			exit;
    			
    			
    			break;
    		}
    	}
    }
}
?>
