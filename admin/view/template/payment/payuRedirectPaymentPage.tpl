<?php echo $header; ?>
    
<input type="hidden" id="payuRedirectPaymentPage_safekey_production_id" name="_hidden_payuRedirect_safekey" value="<?php echo $payuRedirectPaymentPage_safekey_production ?>" />
<input type="hidden" id="payuRedirectPaymentPage_safekey_staging_id" name="_hidden_payuRedirect_safekey_staging" value="<?php echo $payuRedirectPaymentPage_safekey_staging ?>" />

<input type="hidden" id="payuRedirectPaymentPage_username_production_id" name="_hidden_payuRedirect_username_production" value="<?php echo $payuRedirectPaymentPage_username_production ?>" />
<input type="hidden" id="payuRedirectPaymentPage_username_staging_id" name="_hidden_payuRedirect_username_staging" value="<?php echo $payuRedirectPaymentPage_username_staging ?>" />

<input type="hidden" id="payuRedirectPaymentPage_password_production_id" name="_hidden_payuRedirect_password_production" value="<?php echo $payuRedirectPaymentPage_password_production ?>" />
<input type="hidden" id="payuRedirectPaymentPage_password_staging_id" name="_hidden_payuRedirect_password_staging" value="<?php echo $payuRedirectPaymentPage_password_staging ?>" />

<input type="hidden" id="payuRedirectPaymentPage_enableLogging_production_id" name="_hidden_payuRedirect_enableLogging_production" value="FALSE" />
<input type="hidden" id="payuRedirectPaymentPage_enableLogging_staging_id" name="_hidden_payuRedirect_enableLogging_staging" value="TRUE" />

<input type="hidden" id="payuRedirectPaymentPage_enableExtendedDebug_production_id" name="_hidden_payuRedirect_enableExtendedDebug_production" value="FALSE" />
<input type="hidden" id="payuRedirectPaymentPage_enableExtendedDebug_staging_id" name="_hidden_payuRedirect_enableExtendedDebug_staging" value="TRUE" />

<script type="text/javascript">
    function whichSystemToCallOnChange(nameOfSystem) {
        var nameOfSystemLower = nameOfSystem.toLowerCase();
        document.getElementById("payuRedirectPaymentPage_safekey_id").value = document.getElementById("payuRedirectPaymentPage_safekey_"+nameOfSystemLower+"_id").value;
        document.getElementById("payuRedirectPaymentPage_username_id").value = document.getElementById("payuRedirectPaymentPage_username_"+nameOfSystemLower+"_id").value;
        document.getElementById("payuRedirectPaymentPage_password_id").value = document.getElementById("payuRedirectPaymentPage_password_"+nameOfSystemLower+"_id").value;
        
        if(nameOfSystemLower == "staging") {
            document.getElementById("payuRedirectPaymentPage_safekey_id").readOnly = true;
            document.getElementById("payuRedirectPaymentPage_username_id").readOnly = true;
            document.getElementById("payuRedirectPaymentPage_password_id").readOnly = true;
        }                        
        else {
            document.getElementById("payuRedirectPaymentPage_safekey_id").readOnly = false;
            document.getElementById("payuRedirectPaymentPage_username_id").readOnly = false;
            document.getElementById("payuRedirectPaymentPage_password_id").readOnly = false;
        }
    }
</script>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> PayU Redirect Payment Page</h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>l
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
            
            <tr>
                <td><span class="required">*</span> Payment Title </td>
                <td><input type="text" name="payuRedirectPaymentPage_payTitle" value="<?php echo $payuRedirectPaymentPage_payTitle; ?>"  style="width:100%" /></tr>
            </tr>
            
            <tr>
                <td>Staqing / Production</td>
                <td> 			      
                    <select name="payuRedirectPaymentPage_systemToCall" onChange="whichSystemToCallOnChange(this.options[selectedIndex].value);">                
                        <option value="production" <?php echo ($payuRedirectPaymentPage_systemToCall == 'production' ? ' selected="selected"' : '')?>> Production</option>                
                        <option value="staging" <?php echo ($payuRedirectPaymentPage_systemToCall == 'staging' ? ' selected="selected"' : '')?>> Staging</option>                
                    </select>
                </td>
            </tr>
            
            <tr>
                <td><span class="required">*</span> SafeKey </td>
                <td><input type="text" name="payuRedirectPaymentPage_safekey" id="payuRedirectPaymentPage_safekey_id" value="<?php echo $payuRedirectPaymentPage_safekey; ?>" <?php echo $payuRedirectPaymentPage_textboxReadonlyString; ?>  style="width:100%"  /></tr>
            </tr>
            <tr>
                <td><span class="required">*</span> SOAP Username</td>
                <td><input type="text" name="payuRedirectPaymentPage_username" id="payuRedirectPaymentPage_username_id" value="<?php echo $payuRedirectPaymentPage_username; ?>" <?php echo $payuRedirectPaymentPage_textboxReadonlyString; ?>   style="width:100%"  /></tr>
            </tr>
            <tr>
                <td><span class="required">*</span> SOAP Password </td>
                <td><input type="text" name="payuRedirectPaymentPage_password" id="payuRedirectPaymentPage_password_id" value="<?php echo $payuRedirectPaymentPage_password; ?>" <?php echo $payuRedirectPaymentPage_textboxReadonlyString; ?>   style="width:100%"  /></tr>
            </tr>            
            <tr>
                <td><span class="required">*</span> Transaction Type</td>
                <td><input type="text" name="payuRedirectPaymentPage_transactionType" value="PAYMENT" readonly /></tr>
            </tr>
            <tr>
                <td><span class="required">*</span> Payment methods </td>
                <td><input type="text" name="payuRedirectPaymentPage_paymentMethod" value="CREDITCARD" readonly /></tr>
            </tr>
            <tr>
                <td><span class="required">*</span>Billing Currency</td>
                <td><input type="text" name="payuRedirectPaymentPage_selectedCurrency" value="<?php echo $payuRedirectPaymentPage_selectedCurrency; ?>" readonly /></tr>
            </tr>
            <tr>
                <td><span class="required">*</span>PayU Invoice Description Prepend</td>
                <td><input type="text" name="payuRedirectPaymentPage_defaultOrderNumberPrepend" value="<?php echo $payuRedirectPaymentPage_defaultOrderNumberPrepend; ?>"  style="width:100%"  /></tr>
            </tr>
            <tr>
                <td><span class="required">*</span>Return URL</td>
                <td><input type="text" name="payuRedirectPaymentPage_returnURL" value="<?php echo $payuRedirectPaymentPage_returnURL; ?>"  style="width:100%"  /></tr>
            </tr>
            <tr>
                <td><span class="required">*</span>Cancel URL</td>
                <td><input type="text" name="payuRedirectPaymentPage_cancelURL" value="<?php echo $payuRedirectPaymentPage_cancelURL; ?>"  style="width:100%"  /></tr>
            </tr>
            
          <?php /*  
          
          <tr>
            <td><?php echo $entry_total; ?></td>
            <td><input type="text" name="payu_total" value="<?php echo $payu_total; ?>" /></td>
          </tr>
          
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="payu_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $payu_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected">
				     <?php echo $geo_zone['name']; ?>
			    </option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          */ ?>
          <tr>
            <td>Sort Order</td>
            <td><input type="text" name="payuRedirectPaymentPage_sort_order" value="<?php echo $payuRedirectPaymentPage_sort_order; ?>" size="1" /></td>
          </tr>
          
          <tr>
            <td>Order Status before sending to PayU</td>
            <td><select name="payuRedirectPaymentPage_preorder_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $payuRedirectPaymentPage_preorder_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          
          <tr>
            <td>Order Status on successful payment</td>
            <td><select name="payuRedirectPaymentPage_successorder_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $payuRedirectPaymentPage_successorder_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          
          <tr>
            <td>Order Status on payment error/decline</td>
            <td><select name="payuRedirectPaymentPage_failorder_status_id"> 
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $payuRedirectPaymentPage_failorder_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          
          <tr>
            <td>Order Status on payment cancel</td>
            <td><select name="payuRedirectPaymentPage_cancelorder_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $payuRedirectPaymentPage_cancelorder_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          
          
          <tr>
            <td>Status</td>
            <td><select name="payuRedirectPaymentPage_status">
                <?php if ($payuRedirectPaymentPage_status) { ?>
                <option value="1" selected="selected">Enabled</option>
                <option value="0">Disabled</option>
                <?php } else { ?>
                <option value="1">Enabled</option>
                <option value="0" selected="selected">Disabled</option>
                <?php } ?>
              </select></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 