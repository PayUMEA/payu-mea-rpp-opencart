<?php 
if(isset($error)) {
    echo "Error: ".$error;
}
else {    
    echo str_ireplace('</form','<div class="buttons"><div class="right"><input type="button" value="Pay" id="button-confirm" class="button" /></div></div></form',$payuFormData);
?>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		url: 'index.php?route=payment/payuSafeshopPro/send',
		type: 'post',
        dataType: 'json',		
		beforeSend: function() {
			$('#button-confirm').hide();
			$('#button-confirm').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> Contacting Payment Gateway... </div>');
		},
		complete: function() {
			
		},				
		success: function(json) {
			if (json['success']) {
                <?php if(isset($payuFormName)) { echo "$('#".$payuFormName."').submit();"; } ?>
			}
            if (json['error']) {
				alert(json['error']);
                $('#button-confirm').show();
                $('.attention').remove();
			}
		}
	});
});
//--></script>
<?php
}
?>