<?php
class ControllerPaymentPayuSafeShopPro extends Controller {
	protected function index() {
    	
        $this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');
		$this->language->load('payment/payuSafeshopPro');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);		
        $MerchantReference = $order_info['order_id'];
        $basketArray['amountInCents'] = (float)$order_info['total']*100;
        
        require ("./system/config.payu-safeshop-pro.php");
        $payuOpenCartConfig = PayuOpenCartConfig::getConfig();
        
        $transactionArray = array();        
        
        $transactionArray['MerchantReferenceNumber'] = $MerchantReference;
        $transactionArray['TransactionAmount'] = (float) $order_info['total']*100;;
        $transactionArray['CurrencyCode'] = $this->config->get('payuSafeshopPro_selectedCurrency');
        $transactionArray['ReceiptURL'] = $this->config->get('payuSafeshopPro_successURL');
        $transactionArray['FailURL'] = $this->config->get('payuSafeshopPro_failURL');
        $transactionArray['TransactionType'] = $this->config->get('payuSafeshopPro_transactionType');
        
        if(strtolower($this->config->get('payuSafeshopPro_systemToCall')) == "staging") {        
            $safeKeyToUse = $payuOpenCartConfig['PayuSafeshopPro']['safekey'];            
        }
        else {
            $safeKeyToUse = $this->config->get('payuSafeshopPro_safekey');            
        }
        $constructorArray['safeKey'] = $safeKeyToUse;
        
        $this->data['error'] = 'Unable to contact PayU service. Please contact merchant.';        
        try {
            $payuSsProInstance = new PayuSafeShopPro($constructorArray);
            $returnValue = $payuSsProInstance->getFormHtmlData($transactionArray);
            
            if(isset($returnValue['formName'])) {
                $this->data['payuFormData'] = $returnValue['htmlString'];
            }
            else {
                $this->data['error'] = 'Unable to build PayU details';
            }
            
        }
        catch(Exception $e) {
            $this->data['error'] = $e->getMessage();            
        }
        
        if(isset($this->data['payuFormData'])) {
            unset($this->data['error']);
        }
        
        $templateName = "payuSafeshopPro";
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/template/'.$templateName.'.tpl')) {
            $this->template = $this->config->get('config_template') .'/template/payment/'.$templateName.'.tpl';
        } else {
            $this->template = 'default/template/payment/'.$templateName.'.tpl';
        }        
		$this->render();
	}
    
    public function response() {
        require ("./system/config.payu-safeshop-pro.php");
        $payuOpenCartConfig = PayuOpenCartConfig::getConfig();
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);		
        $MerchantReference = $order_info['order_id'];
        
        $transactionState = "failure";    
        try {
            
            $errorMessage = "Invalid Gateway Reponse. Please enquire with merchant";
            if(isset($this->request->post['SafePayRefNr']) && !empty($this->request->post['SafePayRefNr'])) {                
                if(isset($this->request->post['TransactionErrorResponse']) && empty($this->request->post['TransactionErrorResponse'])) {
                    
                    // if staging allow test transaction - if not staging dont allow test transactions
                    if(strtolower($this->config->get('payuSafeshopPro_systemToCall')) == "staging") {
                        $transactionState = "paymentSuccessfull";
                    }
                    else {
                        if(isset($this->request->post['LiveTransaction']) && !empty($this->request->post['LiveTransaction']) && (strtolower($this->request->post['LiveTransaction']) == 'true') ) {
                            $transactionState = "paymentSuccessfull";
                        }
                        else {
                            $errorMessage = "No Test Transactions are allowed. Please enquire with merchant.";
                        }
                    }
                }
            }
            
            if(isset($this->request->post['TransactionErrorResponse']) && !empty($this->request->post['TransactionErrorResponse'])) {
                $errorMessage = $this->request->post['TransactionErrorResponse'];
            }            
        }
        catch(Exception $e) {
            $errorMessage = $e->getMessage();            
        }            
        
        $this->load->model('checkout/order');
        
        //Now doing db updates for the orders 
        if($transactionState == "paymentSuccessfull") {
            $message = 'Payment Successful:'."\r\n";
            $message .= 'Order Id: ' . $this->session->data['order_id'] . "\r\n";
            $message .= 'PayU Reference: ' . $this->request->get['SafePayRefNr'] . "\r\n";
            $message .= 'Sent Merchant Reference: ' . $this->request->get['MerchantReference'] . "\r\n";
            $message .= 'Gateway Reference: ' . $this->request->get['BankRefNr'] . "\r\n";

            $this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('payuSafeshopPro_successorder_status_id'),$message, true);            
            $this->redirect($this->url->link('checkout/success', '', 'SSL'));            
        }    
        else{
            $this->data['breadcrumbs'] = array();
            if( !isset($this->data['heading_title']) || (empty($this->data['heading_title'])) ) {
                $this->data['heading_title'] = "Payment Failed";
            }
            $this->data['notification_message'] = $errorMessage;
            $this->data['continue'] = $this->url->link('checkout/checkout');
            
            $message = "Payment failed. Response: ".$errorMessage;
            
            $this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('payuSafeshopPro_failorder_status_id'),$message, true);
            
            $templateName = "payuSafeshopPro_notification";            
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/template/'.$templateName.'.tpl')) {
                $this->template = $this->config->get('config_template') .'/template/payment/'.$templateName.'.tpl';
            } else {
                $this->template = 'default/template/payment/'.$templateName.'.tpl';
            }
            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'
            );
            $this->response->setOutput($this->render());            
        }	        
    }
	
}
?>