<?php 
class ControllerPaymentPayuRedirectPaymentPage extends Controller {
	private $error = array(); 

	public function index() {
        
        $this->load->language('payment/payuRedirectPaymentPage');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payuRedirectPaymentPage', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
        
        
        
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['error_warning'] = '';
        $this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');        
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
        
        //bootstrap section
		require (preg_replace("/\/++/","/", dirname(__FILE__)."/../../../system/config.payu-rpp.php"));
        $payuOpenCartConfig = PayuOpenCartConfig::getConfig();
        
        $this->data['payuRedirectPaymentPage_systemToCall'] = $this->config->get('payuRedirectPaymentPage_systemToCall');
        if($this->data['payuRedirectPaymentPage_systemToCall'] == "") {
            $this->data['payuRedirectPaymentPage_systemToCall'] = "staging";
        }
        
        $this->data['payuRedirectPaymentPage_safekey'] = $this->config->get('payuRedirectPaymentPage_safekey'); 
        $this->data['payuRedirectPaymentPage_username'] = $this->config->get('payuRedirectPaymentPage_username'); 
        $this->data['payuRedirectPaymentPage_password'] = $this->config->get('payuRedirectPaymentPage_password'); 
        $this->data['payuRedirectPaymentPage_transactionType'] = $this->config->get('payuRedirectPaymentPage_transactionType'); 
        $this->data['payuRedirectPaymentPage_paymentMethod'] = $this->config->get('payuRedirectPaymentPage_paymentMethod'); 
        $this->data['payuRedirectPaymentPage_enableLogging'] = $this->config->get('payuRedirectPaymentPage_enableLogging'); 
        $this->data['payuRedirectPaymentPage_enableExtendedDebug'] = $this->config->get('payuRedirectPaymentPage_enableExtendedDebug'); 
        $this->data['payuRedirectPaymentPage_selectedCurrency'] = $this->config->get('payuRedirectPaymentPage_selectedCurrency'); 
        $this->data['payuRedirectPaymentPage_defaultOrderNumberPrepend'] = $this->config->get('payuRedirectPaymentPage_defaultOrderNumberPrepend'); 
        $this->data['payuRedirectPaymentPage_returnURL'] = $this->config->get('payuRedirectPaymentPage_returnURL');
        $this->data['payuRedirectPaymentPage_cancelURL'] = $this->config->get('payuRedirectPaymentPage_cancelURL'); 
        
        $this->data['payuRedirectPaymentPage_cancelorder_status_id'] = $this->config->get('payuRedirectPaymentPage_cancelorder_status_id'); 
        $this->data['payuRedirectPaymentPage_failorder_status_id'] = $this->config->get('payuRedirectPaymentPage_failorder_status_id'); 
        $this->data['payuRedirectPaymentPage_successorder_status_id'] = $this->config->get('payuRedirectPaymentPage_successorder_status_id'); 
        $this->data['payuRedirectPaymentPage_preorder_status_id'] = $this->config->get('payuRedirectPaymentPage_preorder_status_id'); 
        
        $this->data['payuRedirectPaymentPage_status'] = $this->config->get('payuRedirectPaymentPage_status');
        
        $this->data['payuRedirectPaymentPage_textboxReadonlyString'] = " ";        
        $this->data['payuRedirectPaymentPage_sort_order'] = $this->config->get('payuRedirectPaymentPage_sort_order');
        $this->data['payuRedirectPaymentPage_status'] = $this->config->get('payuRedirectPaymentPage_status');
        $this->data['payuRedirectPaymentPage_payTitle'] = $this->config->get('payuRedirectPaymentPage_payTitle');
        
        
        if(strtolower($this->data['payuRedirectPaymentPage_systemToCall']) == "staging") {        
            $this->data['payuRedirectPaymentPage_safekey'] = $payuOpenCartConfig['PayuRedirectPaymentPage']['safekey']; 
            $this->data['payuRedirectPaymentPage_username'] = $payuOpenCartConfig['PayuRedirectPaymentPage']['username']; 
            $this->data['payuRedirectPaymentPage_password'] = $payuOpenCartConfig['PayuRedirectPaymentPage']['password'];             
            $this->data['payuRedirectPaymentPage_enableLogging'] = $this->config->get('payuRedirectPaymentPage_enableLogging'); 
            $this->data['payuRedirectPaymentPage_enableExtendedDebug'] = $this->config->get('payuRedirectPaymentPage_enableExtendedDebug'); 
            $this->data['payuRedirectPaymentPage_selectedCurrency'] = $payuOpenCartConfig['PayuRedirectPaymentPage']['supportedCurrencies'];             
            $this->data['payuRedirectPaymentPage_returnURL'] = $this->config->get('payuRedirectPaymentPage_returnURL');
            $this->data['payuRedirectPaymentPage_cancelURL'] = $this->config->get('payuRedirectPaymentPage_cancelURL');
            $this->data['payuRedirectPaymentPage_textboxReadonlyString'] = " readonly='readonly' ";
        }
        
        if(empty($this->data['payuRedirectPaymentPage_payTitle'])) {            
            $this->data['payuRedirectPaymentPage_payTitle'] = "Credit Card (Processed securely by PayU)";
        }
        
        if(empty($this->data['payuRedirectPaymentPage_defaultOrderNumberPrepend'])) {
            $this->data['payuRedirectPaymentPage_defaultOrderNumberPrepend'] = $this->config->get('config_name')." Order Number: ";
        }

        //populating default status orders if required
        if(empty($this->data['payuRedirectPaymentPage_cancelorder_status_id'])) {
            $this->data['payuRedirectPaymentPage_cancelorder_status_id'] = 7;
        }
        if(empty($this->data['payuRedirectPaymentPage_failorder_status_id'])) {
            $this->data['payuRedirectPaymentPage_failorder_status_id'] = 10;
        }
        if(empty($this->data['payuRedirectPaymentPage_successorder_status_id'])) {
            $this->data['payuRedirectPaymentPage_successorder_status_id'] = 2;
        }
        if(empty($this->data['payuRedirectPaymentPage_preorder_status_id'])) {
            $this->data['payuRedirectPaymentPage_preorder_status_id'] = 1;
        }
        
        $urlDirBase = $this->config->get('config_url')."/index.php";
        $tempArray = explode('://', $urlDirBase);
        if(isset($tempArray[1])) {
            $urlDirBase = preg_replace("/\/++/","/", $tempArray[1]);
            $urlDirBase = $tempArray[0].'://'.$tempArray[1];
        }
        
        if(empty($this->data['payuRedirectPaymentPage_returnURL'])) {            
            $this->data['payuRedirectPaymentPage_returnURL'] = $urlDirBase."?route=payment/payuRedirectPaymentPage/response";;
        }
        if(empty($this->data['payuRedirectPaymentPage_cancelURL'])) {            
            $this->data['payuRedirectPaymentPage_cancelURL'] = $urlDirBase."?route=payment/payuRedirectPaymentPage/cancel";;
        }   
        
        $this->data['payuRedirectPaymentPage_safekey_production'] = $this->config->get('payuRedirectPaymentPage_safekey_production');
        $this->data['payuRedirectPaymentPage_safekey_staging'] = $payuOpenCartConfig['PayuRedirectPaymentPage']['safekey']; ;
        
        $this->data['payuRedirectPaymentPage_username_production'] = $this->config->get('payuRedirectPaymentPage_username_production');
        $this->data['payuRedirectPaymentPage_username_staging'] = $payuOpenCartConfig['PayuRedirectPaymentPage']['username'];
        
        $this->data['payuRedirectPaymentPage_password_production'] = $this->config->get('payuRedirectPaymentPage_password_production');
        $this->data['payuRedirectPaymentPage_password_staging'] = $payuOpenCartConfig['PayuRedirectPaymentPage']['password'];
        
        $this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/payuRedirectPaymentPage', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/payuRedirectPaymentPage', 'token=' . $this->session->data['token'], 'SSL');		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('localisation/order_status');		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$this->load->model('localisation/geo_zone');										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		$this->template = 'payment/payuRedirectPaymentPage.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/payuRedirectPaymentPage')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
        //bootstrap section
        require (preg_replace("/\/++/","/", dirname(__FILE__)."/../../../system/config.payu-rpp.php"));
        $payuOpenCartConfig = PayuOpenCartConfig::getConfig();
        
        $this->request->post['payuRedirectPaymentPage_enableLogging'] = 'false';
        $this->request->post['payuRedirectPaymentPage_enableExtendedDebug'] = 'false';
        
        if(strtolower($this->request->post['payuRedirectPaymentPage_systemToCall']) == "staging") {        
            $this->request->post['payuRedirectPaymentPage_safekey'] = $payuOpenCartConfig['PayuRedirectPaymentPage']['safekey']; 
            $this->request->post['payuRedirectPaymentPage_username'] = $payuOpenCartConfig['PayuRedirectPaymentPage']['username']; 
            $this->request->post['payuRedirectPaymentPage_password'] = $payuOpenCartConfig['PayuRedirectPaymentPage']['password'];             
            $this->request->post['payuRedirectPaymentPage_enableLogging'] = 'true';
            $this->request->post['payuRedirectPaymentPage_enableExtendedDebug'] = 'true';            
        }
        
        if(empty($this->request->post['payuRedirectPaymentPage_payTitle'])) {
            $this->request->post['payuRedirectPaymentPage_payTitle'] = "Credit Card (Processed securely by PayU)";
        }
        
        $urlDirBase = $this->config->get('config_url')."/index.php";
        $tempArray = explode('://', $urlDirBase);
        if(isset($tempArray[1])) {
            $urlDirBase = preg_replace("/\/++/","/", $tempArray[1]);
            $urlDirBase = $tempArray[0].'://'.$tempArray[1];
        }        
        if(empty($this->request->post['payuRedirectPaymentPage_returnURL'])) {            
            $this->request->post['payuRedirectPaymentPage_returnURL'] = $urlDirBase."?route=payment/payuRedirectPaymentPage/response";;
        }
        if(empty($this->request->post['payuRedirectPaymentPage_cancelURL'])) {            
            $this->request->post['payuRedirectPaymentPage_cancelURL'] = $urlDirBase."?route=payment/payuRedirectPaymentPage/cancel";;
        }
        
        foreach($this->request->post as $key => $value) {
            if( (!is_array($value))  ) {
                $tempArray = explode('_',$key,2);
                if($tempArray[0] == 'payuRedirectPaymentPage') {                
                    $newKey = $key."_".$this->request->post['payuRedirectPaymentPage_systemToCall'];
                    $this->request->post[$newKey] = $value;
                }            
            }        
        }   
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
