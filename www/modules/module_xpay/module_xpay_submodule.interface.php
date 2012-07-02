<?php
	interface IxPaySubmodule {
		public static function getInstance();
		
		public function getPaymentInstances();
		public function getPaymentInstanceConfig($instance_id, array $overrideOptions);
		public function initPaymentProccess($payment_id, $invoice_id, array $data);
		
		// 
		public function paymentCanBeDone($payment_id, $invoice_id);
		
		//public function paymentCanBeDone($payment_id, $invoice_id);
		
		
		
		// STATUS CHECK FUNCTIONS 
		/*
		public function getInvoiceStatusById($payment_id, $invoice_id);
		*/
		// Management
	}
?>