<?php 
class ControllerPaymentPayuSafeshopPro extends Controller {
	private $error = array(); 

	public function index() {
        
        $this->load->language('payment/payuSafeshopPro');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payuSafeshopPro', $this->request->post);
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
        require ("./../system/config.payu-safeshop-pro.php");
        $payuOpenCartConfig = PayuOpenCartConfig::getConfig();
		
		//var_dump($payuOpenCartConfig);
        
        $this->data['payuSafeshopPro_systemToCall'] = $this->config->get('payuSafeshopPro_systemToCall');
        if($this->data['payuSafeshopPro_systemToCall'] == "") {
            $this->data['payuSafeshopPro_systemToCall'] = "staging";
        }
        
        $this->data['payuSafeshopPro_safekey'] = $this->config->get('payuSafeshopPro_safekey'); 
        $this->data['payuSafeshopPro_username'] = $this->config->get('payuSafeshopPro_username'); 
        $this->data['payuSafeshopPro_password'] = $this->config->get('payuSafeshopPro_password'); 
        $this->data['payuSafeshopPro_transactionType'] = $this->config->get('payuSafeshopPro_transactionType'); 
        $this->data['payuSafeshopPro_paymentMethod'] = $this->config->get('payuSafeshopPro_paymentMethod'); 
        $this->data['payuSafeshopPro_enableLogging'] = $this->config->get('payuSafeshopPro_enableLogging'); 
        $this->data['payuSafeshopPro_enableExtendedDebug'] = $this->config->get('payuSafeshopPro_enableExtendedDebug'); 
        $this->data['payuSafeshopPro_selectedCurrency'] = $this->config->get('payuSafeshopPro_selectedCurrency'); 
        $this->data['payuSafeshopPro_defaultOrderNumberPrepend'] = $this->config->get('payuSafeshopPro_defaultOrderNumberPrepend'); 
        $this->data['payuSafeshopPro_successURL'] = $this->config->get('payuSafeshopPro_successURL');
        $this->data['payuSafeshopPro_failURL'] = $this->config->get('payuSafeshopPro_failURL'); 
        
        $this->data['payuSafeshopPro_cancelorder_status_id'] = $this->config->get('payuSafeshopPro_cancelorder_status_id'); 
        $this->data['payuSafeshopPro_failorder_status_id'] = $this->config->get('payuSafeshopPro_failorder_status_id'); 
        $this->data['payuSafeshopPro_successorder_status_id'] = $this->config->get('payuSafeshopPro_successorder_status_id'); 
        $this->data['payuSafeshopPro_preorder_status_id'] = $this->config->get('payuSafeshopPro_preorder_status_id'); 
        
        $this->data['payuSafeshopPro_status'] = $this->config->get('payuSafeshopPro_status');
        
        $this->data['payuSafeshopPro_textboxReadonlyString'] = " ";        
        $this->data['payuSafeshopPro_sort_order'] = $this->config->get('payuSafeshopPro_sort_order');
        $this->data['payuSafeshopPro_status'] = $this->config->get('payuSafeshopPro_status');
        $this->data['payuSafeshopPro_payTitle'] = $this->config->get('payuSafeshopPro_payTitle');
        $this->data['payuSafeshopPro_safepayRefCheck'] = $this->config->get('payuSafeshopPro_safepayRefCheck');        
        
        if(strtolower($this->data['payuSafeshopPro_systemToCall']) == "staging") {        
            $this->data['payuSafeshopPro_safekey'] = $payuOpenCartConfig['PayuSafeshopPro']['safekey']; 
            $this->data['payuSafeshopPro_enableLogging'] = $this->config->get('payuSafeshopPro_enableLogging'); 
            $this->data['payuSafeshopPro_enableExtendedDebug'] = $this->config->get('payuSafeshopPro_enableExtendedDebug'); 
            $this->data['payuSafeshopPro_selectedCurrency'] = $payuOpenCartConfig['PayuSafeshopPro']['supportedCurrencies'];             
            $this->data['payuSafeshopPro_successURL'] = $this->config->get('payuSafeshopPro_successURL');
            $this->data['payuSafeshopPro_failURL'] = $this->config->get('payuSafeshopPro_failURL');
            $this->data['payuSafeshopPro_textboxReadonlyString'] = " readonly='readonly' ";
        }
        
        if(empty($this->data['payuSafeshopPro_payTitle'])) {
            $this->data['payuSafeshopPro_payTitle'] = "Credit Card (Processed securely by PayU)";
        }
        
        if(empty($this->data['payuSafeshopPro_defaultOrderNumberPrepend'])) {
            $this->data['payuSafeshopPro_defaultOrderNumberPrepend'] = $this->config->get('config_name')." Order Number: ";
        }

        //populating default status orders if required
        if(empty($this->data['payuSafeshopPro_cancelorder_status_id'])) {
            $this->data['payuSafeshopPro_cancelorder_status_id'] = 7;
        }
        if(empty($this->data['payuSafeshopPro_failorder_status_id'])) {
            $this->data['payuSafeshopPro_failorder_status_id'] = 10;
        }
        if(empty($this->data['payuSafeshopPro_successorder_status_id'])) {
            $this->data['payuSafeshopPro_successorder_status_id'] = 2;
        }
        if(empty($this->data['payuSafeshopPro_preorder_status_id'])) {
            $this->data['payuSafeshopPro_preorder_status_id'] = 1;
        }
        
        $urlDirBase = $this->config->get('config_url')."/index.php";
        $tempArray = explode('://', $urlDirBase);
        if(isset($tempArray[1])) {
            $urlDirBase = preg_replace("/\/++/","/", $tempArray[1]);
            $urlDirBase = $tempArray[0].'://'.$tempArray[1];
        }
        
        if(empty($this->data['payuSafeshopPro_successURL'])) {            
            $this->data['payuSafeshopPro_successURL'] = $urlDirBase."?route=payment/payuSafeshopPro/response";;
        }
        if(empty($this->data['payuSafeshopPro_failURL'])) {            
            $this->data['payuSafeshopPro_failURL'] = $urlDirBase."?route=payment/payuSafeshopPro/response";;
        }   
        
        $this->data['payuSafeshopPro_safekey_production'] = $this->config->get('payuSafeshopPro_safekey_production');
        $this->data['payuSafeshopPro_safekey_staging'] = $payuOpenCartConfig['PayuSafeshopPro']['safekey'];
        
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
			'href'      => $this->url->link('payment/payuSafeshopPro', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/payuSafeshopPro', 'token=' . $this->session->data['token'], 'SSL');		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('localisation/order_status');		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$this->load->model('localisation/geo_zone');										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		$this->template = 'payment/payuSafeshopPro.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/payuSafeshopPro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
        //bootstrap section
        require ("./../system/config.payu-safeshop-pro.php");
        $payuOpenCartConfig = PayuOpenCartConfig::getConfig();
        
        $this->request->post['payuSafeshopPro_enableLogging'] = 'false';
        $this->request->post['payuSafeshopPro_enableExtendedDebug'] = 'false';
        
        if(strtolower($this->request->post['payuSafeshopPro_systemToCall']) == "staging") {
            $this->request->post['payuSafeshopPro_safekey'] = $payuOpenCartConfig['PayuSafeshopPro']['safekey'];                 
            $this->request->post['payuSafeshopPro_enableLogging'] = 'true';
            $this->request->post['payuSafeshopPro_enableExtendedDebug'] = 'true';
        }
        
        if(empty($this->request->post['payuSafeshopPro_payTitle'])) {
            $this->request->post['payuSafeshopPro_payTitle'] = "Credit Card (Processed securely by PayU)";
        }
        
        $urlDirBase = $this->config->get('config_url')."/index.php";
        $tempArray = explode('://', $urlDirBase);
        if(isset($tempArray[1])) {
            $urlDirBase = preg_replace("/\/++/","/", $tempArray[1]);
            $urlDirBase = $tempArray[0].'://'.$tempArray[1];
        }        
        if(empty($this->request->post['payuSafeshopPro_returnURL'])) {            
            $this->request->post['payuSafeshopPro_returnURL'] = $urlDirBase."?route=payment/payuSafeshopPro/response";;
        }
        if(empty($this->request->post['payuSafeshopPro_failURL'])) {            
            $this->request->post['payuSafeshopPro_failURL'] = $urlDirBase."?route=payment/payuSafeshopPro/response";;
        }
        
        foreach($this->request->post as $key => $value) {
            if( (!is_array($value))  ) {
                $tempArray = explode('_',$key,2);
                if($tempArray[0] == 'payuSafeshopPro') {                
                    $newKey = $key."_".$this->request->post['payuSafeshopPro_systemToCall'];
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