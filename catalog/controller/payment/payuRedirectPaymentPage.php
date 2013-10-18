<?php
class ControllerPaymentPayuRedirectPaymentPage extends Controller {
	protected function index() {
    	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payuRedirectPaymentPage.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/payuRedirectPaymentPage.tpl';
		} else {
			$this->template = 'default/template/payment/payuRedirectPaymentPage.tpl';
		}			
		
		$this->render();
	}
    
    public function send () {
        $this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');
		$this->language->load('payment/payuRedirectPaymentPage');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
        require (preg_replace("/\/++/","/", dirname(__FILE__)."/./../../../system/config.payu-rpp.php"));
        
        $this->data['payuRedirectPaymentPage_transactionType'] = $this->config->get('payuRedirectPaymentPage_transactionType'); 
        $this->data['payuRedirectPaymentPage_paymentMethod'] = $this->config->get('payuRedirectPaymentPage_paymentMethod'); 
        $this->data['payuRedirectPaymentPage_enableLogging'] = $this->config->get('payuRedirectPaymentPage_enableLogging'); 
        $this->data['payuRedirectPaymentPage_enableExtendedDebug'] = $this->config->get('payuRedirectPaymentPage_enableExtendedDebug'); 
        $this->data['payuRedirectPaymentPage_selectedCurrency'] = $this->config->get('payuRedirectPaymentPage_selectedCurrency'); 
        $this->data['payuRedirectPaymentPage_defaultOrderNumberPrepend'] = $this->config->get('payuRedirectPaymentPage_defaultOrderNumberPrepend'); 
        $this->data['payuRedirectPaymentPage_returnURL'] = $this->config->get('payuRedirectPaymentPage_returnURL');
        $this->data['payuRedirectPaymentPage_cancelURL'] = $this->config->get('payuRedirectPaymentPage_cancelURL'); 
        
        $this->data['amount1'] = (float)$order_info['total'];
		$this->data['productinfo'] = 'opencart products information';
		$this->data['firstname'] = $order_info['payment_firstname'];
		$this->data['Lastname'] = $order_info['payment_lastname'];
		$this->data['Zipcode'] = $order_info['payment_postcode'];
		$this->data['email'] = $order_info['email'];
		$this->data['phone'] = $order_info['telephone'];
        
        $setTransactionSoapDataArray = array();
        $setTransactionSoapDataArray['TransactionType'] = $this->config->get('payuRedirectPaymentPage_transactionType');
        
        $MerchantReference = $order_info['order_id'];
        
        // Creating Basket Array
        $basketArray = array();
        $basketArray['amountInCents'] = (float) $order_info['total']*100;
		if (strpos($basketArray['amountInCents'],'.') !== false) {
			list($basketArray['amountInCents'],$tempVar) = explode(".",$basketArray['amountInCents'],2);			
			$basketArray['amountInCents'] = $basketArray['amountInCents']+1;
		}
        $basketArray['description'] = $this->config->get('payuRedirectPaymentPage_defaultOrderNumberPrepend').$MerchantReference;
        $basketArray['currencyCode'] = $this->config->get('payuRedirectPaymentPage_selectedCurrency');
        $setTransactionSoapDataArray = array_merge($setTransactionSoapDataArray, array('Basket' => $basketArray ));
        $basketArray = null; unset($basketArray);

        // Creating Customer Array
        $customerSubmitArray = array();
        $customerSubmitArray['firstName'] = $order_info['payment_firstname'];
        $customerSubmitArray['lastName'] = $order_info['payment_lastname'];
        $customerSubmitArray['mobile'] = $order_info['telephone'];
        $customerSubmitArray['email'] = $order_info['email'];        
        $setTransactionSoapDataArray = array_merge($setTransactionSoapDataArray, array('Customer' => $customerSubmitArray ));
        $customerSubmitArray = null; unset($customerSubmitArray);
        
        //$customerArray['regionalId'] = ''; - 
        //$customerArray['merchantUserId'] = ''; - dont have a merchant user id here        
        //$setTransactionSoapDataArray = array_merge($setTransactionSoapDataArray, array('Customer' => $customerSubmitArray ));
        //$customerSubmitArray = null; unset($customerSubmitArray);
        
        //Creating Additional Information Array
        $additionalInformationArray = array();
        $additionalInformationArray['supportedPaymentMethods'] = $this->config->get('payuRedirectPaymentPage_paymentMethod');
        $additionalInformationArray['cancelUrl'] = $this->config->get('payuRedirectPaymentPage_cancelURL' );
        //$additionalInformationArray['notificationUrl'] = get_option('payuRedirectPaymentPage_notificationURL' );
        $additionalInformationArray['returnUrl'] = $this->config->get('payuRedirectPaymentPage_returnURL');
        $additionalInformationArray['merchantReference'] = $MerchantReference;
        $setTransactionSoapDataArray = array_merge($setTransactionSoapDataArray, array('AdditionalInformation' => $additionalInformationArray ));
        $additionalInformationArray = null; unset($additionalInformationArray);
        
        //Creating a constructor array for RPP instantiation        
        $constructorArray = array();        
        $constructorArray['safeKey'] = $this->config->get('payuRedirectPaymentPage_safekey'); ;
        $constructorArray['username'] = $this->config->get('payuRedirectPaymentPage_username'); ;
        $constructorArray['password'] = $this->config->get('payuRedirectPaymentPage_password'); ;;
        
        //$constructorArray['logEnable'] = false;
        $constructorArray['extendedDebugEnable'] = true;
        
        if(strtolower($this->config->get('payuRedirectPaymentPage_systemToCall')) == 'production') {
            $constructorArray['production'] = true;
        }
        
        $json['error'] = 'Unable to contact PayU service. Please contact merchant.';
        try {
            $payuRppInstance = new PayuRedirectPaymentPage($constructorArray);
            $setTransactionResponse = $payuRppInstance->doSetTransactionSoapCall($setTransactionSoapDataArray);
            
            
            if(isset($setTransactionResponse['redirectPaymentPageUrl'])) {
                $json['success'] = $setTransactionResponse['redirectPaymentPageUrl'];
                $message = 'Redirected to PayU for payment'."\r\n";            
                $message .= 'PayU Reference: ' . $setTransactionResponse['soapResponse']['payUReference'] . "\r\n";
                $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('payuRedirectPaymentPage_preorder_status_id'),$message, true);
            }
            
        }
        catch(Exception $e) {
            $json['error'] = $e->getMessage();            
        }
        
        if(isset($json['success'])) {
            unset($json['error']);
        }
        
        if(isset($json['error'])) {
            $this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('payuRedirectPaymentPage_preorder_status_id'),$json['error'], true);
        }   
        
		$this->response->setOutput(json_encode($json));
    }
	
    public function response() {
		
		require (preg_replace("/\/++/","/", dirname(__FILE__)."/./../../../system/config.payu-rpp.php"));        
        
        $transactionState = "failure";    
        try {
            
            $errorMessage = "Invalid Gateway Reponse";
            
            if(isset($this->request->get['PayUReference']) && !empty($this->request->get['PayUReference'])) {
                

                //Creating get transaction soap data array
                $getTransactionSoapDataArray = array();
                //$getTransactionSoapDataArray['Safekey'] = get_option('payuRedirectPaymentPage_safekey');
                $getTransactionSoapDataArray['AdditionalInformation']['payUReference'] = $this->request->get['PayUReference'];        

                $constructorArray = array();        
                $constructorArray['safeKey'] = $this->config->get('payuRedirectPaymentPage_safekey'); ;
                $constructorArray['username'] = $this->config->get('payuRedirectPaymentPage_username'); ;
                $constructorArray['password'] = $this->config->get('payuRedirectPaymentPage_password'); ;;
                //$constructorArray['logEnable'] = false;
                $constructorArray['extendedDebugEnable'] = true;
                if(strtolower($this->config->get('payuRedirectPaymentPage_systemToCall')) == 'production') {
                    $constructorArray['production'] = true;
                }

                $payuRppInstance = new PayuRedirectPaymentPage($constructorArray);
                $getTransactionResponse = $payuRppInstance->doGetTransactionSoapCall($getTransactionSoapDataArray); 

                $errorMessage = $getTransactionResponse['soapResponse']['displayMessage'];
                
                //Checking the response from the SOAP call to see if successfull
                if(isset($getTransactionResponse['soapResponse']['successful']) && ($getTransactionResponse['soapResponse']['successful']  === true)) {                    
                    if(isset($getTransactionResponse['soapResponse']['transactionType']) && (strtolower($getTransactionResponse['soapResponse']['transactionType']) == 'payment') ) {                    
                        if(isset($getTransactionResponse['soapResponse']['resultCode']) && (strtolower($getTransactionResponse['soapResponse']['resultCode']) == '00') ) {
                            $MerchantReferenceCheck = $this->session->data['order_id'];
                            $MerchantReferenceCallBack = $getTransactionResponse['soapResponse']['merchantReference'];
                            $gatewayReference = $getTransactionResponse['soapResponse']['paymentMethodsUsed']['gatewayReference'];
                            $transactionState = "paymentSuccessfull"; //funds reserved need to finalize in the admin box                                
                            
                            if( isset($getTransactionResponse['soapResponse']['merchantReference']) && !empty($getTransactionResponse['soapResponse']['merchantReference']) ) {
                                
                                
                            }
                        }
                    }                    
                }
                else {
                    $errorMessage = $getTransactionResponse['soapResponse']['displayMessage'];
                }
            }            
        }
        catch(Exception $e) {
            $errorMessage = $e->getMessage();            
        }    
        
        $this->load->model('checkout/order');
        
        //Now doing db updates for the orders 
        if($transactionState == "paymentSuccessfull")
        {
            $message = 'Payment Successful:'."\r\n";
            $message .= 'Order Id: ' . $this->session->data['order_id'] . "\r\n";
            $message .= 'PayU Reference: ' . $this->request->get['PayUReference'] . "\r\n";
            $message .= 'Sent Merchant Reference: ' . $MerchantReferenceCallBack . "\r\n";
            $message .= 'Gateway Reference: ' . $gatewayReference . "\r\n";

            $this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('payuRedirectPaymentPage_successorder_status_id'),$message, true);            
            $this->redirect($this->url->link('checkout/success', '', 'SSL'));            
        }    
        else if($transactionState == "failure")
        {
            $this->data['breadcrumbs'] = array();
            if(!empty($this->data['heading_title'])) {
                $this->data['heading_title'] = "Payment Failed";
            }
            $this->data['notification_message'] = $errorMessage;
            $this->data['continue'] = $this->url->link('checkout/checkout');
            
            $message = "Payment failed. Response: ".$errorMessage;
            
            $this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('payuRedirectPaymentPage_failorder_status_id'),$message, true);
            
            $templateName = "payuRedirectPaymentPage_notification";            
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
    
    public function cancel() {
        $orderid=$this->session->data['order_id']; 
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($orderid);
        
        $this->data['breadcrumbs'] = array();
        $this->data['heading_title'] = "Payment Cancelled";
        $this->data['notification_message'] = "You have cancelled your order";
        $this->data['continue'] = $this->url->link('checkout/checkout');
        
        $message = "Payment cancelled on PayU payment page";

        $this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('payuRedirectPaymentPage_cancelorder_status_id'),$message, true);

        $templateName = "payuRedirectPaymentPage_notification";            
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
?>
