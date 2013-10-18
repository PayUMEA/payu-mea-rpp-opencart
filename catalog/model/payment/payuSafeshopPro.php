<?php 
class ModelPaymentPayuSafeshopPro extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('payment/payuSafeshopPro');
		
        /*
        
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payu_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('payu_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payu_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true; 
		} else {
			$status = false;
		}
         * 
         */	
        
		$method_data = array();
	
		
      		$method_data = array( 
        		'code'       => 'payuSafeshopPro',
        		'title' => $this->config->get('payuSafeshopPro_payTitle'),
				'sort_order' => $this->config->get('payuSafeshopPro_sort_order')
      		);
            
        
   
    	return $method_data;
  	}
	
	
	
}
?>