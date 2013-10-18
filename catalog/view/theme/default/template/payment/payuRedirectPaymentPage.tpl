<div class="buttons">
  <div class="right"><input type="button" value="Pay" id="button-confirm" class="button" /></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		url: 'index.php?route=payment/payuRedirectPaymentPage/send',
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
				location = json['success'];
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

	
	