<?php
// Include the SDK
class module_aws extends MagesterExtendedModule {
    public function getName() {
        return "AWS";
    }

    public function getPermittedRoles() {
        return array("administrator");
    }

    public function isLessonModule() {
        return false;
    }
    
    public function  getDefaultAction() {
    	return "get_instances";
    }
	
    public function getLessonCenterLinkInfo() {
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) == "professor") {
            return array('title' => __AWS,
                         'image' => $this -> moduleBaseDir . 'images/bbb32.png',
                         'link'  => $this -> moduleBaseUrl);
        }
    }

    public function getCenterLinkInfo() {
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> getType() == "administrator") {
            return array('title' => __AWS,
                         'image' => $this -> moduleBaseDir . 'images/bbb32.png',
                         'link'  => $this -> moduleBaseUrl);
        }
    }
    
    public function getInstancesAction() {
		$smarty = $this->getSmartyVar();
		
		$instances = $this->getAllInstances();
		
		$smarty -> assign ("T_AWS_INSTANCES", $instances);
		
		return true;
    }

    public function startInstanceAction() {
		$smarty = $this->getSmartyVar();
		
		$instances = $this->getAllInstances();
		
		$instanceID = $_GET['instance_id'];
		
		if (!array_key_exists($instanceID, $instances)) {
			$message = sprintf("Instancia %s não encontrada", $instanceID);
			$message_type = "warning";
		} else {
			$ec2 = new AmazonEC2();
			$response = $ec2->start_instances($instanceID);
		
			$message = sprintf("Instancia %s iniciada com sucesso!", $instanceID);
			$message_type = "success";
		}
		header(sprintf(
			"Location: " . $this->moduleBaseUrl . "&action=get_instances&message=%s&message_type=%s",
			$message,
			$message_type
		));
		exit;
    }
    
    public function stopInstanceAction() {
		$smarty = $this->getSmartyVar();
		
		$instances = $this->getAllInstances();
		
		$instanceID = $_GET['instance_id'];
		
		if (!array_key_exists($instanceID, $instances)) {
			$message = sprintf("Instancia %s não encontrada", $instanceID);
			$message_type = "warning";
		} else {
			$ec2 = new AmazonEC2();
			$response = $ec2->stop_instances($instanceID);
		
			$message = sprintf("Instancia %s desligada com sucesso!", $instanceID);
			$message_type = "success";
		}
		
		header(sprintf(
			"Location: " . $this->moduleBaseUrl . "&action=get_instances&message=%s&message_type=%s",
			$message,
			$message_type
		));
		exit;
	}
    
    protected function getAllInstances() {
	require_once ($this->moduleBaseDir . 'includes/sdk.class.php');
	
		/*%******************************************************************************************%*/	
		// Instantiate the AmazonEC2 class
		$ec2 = new AmazonEC2();
		// Get the response from a call to the DescribeImages operation.
		$response = $ec2->describe_instances();
	
		// Prepare to collect AKIs.
		$instances = array();
		
		// Loop through the response...
		foreach ($response->body->reservationSet->item as $reserveItem) {
			foreach($reserveItem->instancesSet->item as $item) {
				// Stringify the value
				$instanceItem = array(
					'instanceId'		=> (string)$item->instanceId,
					'imageId'			=> (string)$item->imageId,
					'instanceType'		=> (string)$item->instanceType,
					'privateIpAddress'	=> (string)$item->privateIpAddress,
					'ipAddress'			=> (string)$item->ipAddress,
					'architecture'		=> (string)$item->architecture,
					'instanceState'		=> array(
						'code'			=> (string)$item->instanceState->code,
						'name'			=> $this->mapInstanceStateName(
							(string)$item->instanceState->code,
							(string)$item->instanceState->name
						)
					),
					'tagSet'			=> array()
				);
				
				foreach($item->tagSet->item as $tagSetitem) {
					$instanceItem['tagSet'][(string) $tagSetitem->key] = (string) $tagSetitem->value;
				}
				
				$instances[$instanceItem['instanceId']] = $instanceItem;
			}
		}
	
		return $instances;
    }
    
    protected function mapInstanceStateName($code, $name) {
    	$states = array(
    		0 	=> __AWS_PENDING,
    		80 	=> __AWS_STOPPED,
    		16 	=> __AWS_RUNNING,
    		64	=> __AWS_STOPPING
    	);
    	
    	if (array_key_exists($code, $states)) {
    		return $states[$code];
    	}
    	return $name;
    	
    
    }
}
?>