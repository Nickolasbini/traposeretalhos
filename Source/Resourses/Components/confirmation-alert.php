<div id="confirmation-alert" class="confirmation-modal">
    <form class="confirmation-modal-content">
	    <div class="container">
	    	<div class="confirmation-close" title="<?php echo ucfirst(translate('cancel')) ?>" style="float: right;"><i class="fas fa-times"></i></div>
	        <h1 class="confirmation-alert-title"></h1>
	        <p class="confirmation-alert-message"></p>
	    
	        <div class="clearfix">
	            <button type="button" class="confirmation-buttons cancelbutton"></button>
	            <button type="button" class="confirmation-buttons confirmbutton"></button>
	        </div>
	    </div>
    </form>
    <div class="overlay"></div>
</div>

<script type="text/javascript">
	$( document ).ready(function() {
	    setButtonsMessage("<?php echo ucfirst(translate('cancel')) ?>", "<?php echo ucfirst(translate('remove')) ?>");
		setTitleAndMessage("<?php echo ucfirst(translate('are you sure?')) ?>", "<?php echo ucfirst(translate('are you sure you want to proced?')) ?>");
	});

	// hides on X click
	$('.confirmation-close').off('click').click(function(){
		closeConfirmationAlert();
	});
	$('.cancelbutton').off('click').click(function(){
		closeConfirmationAlert();
	});
	$('.overlay').off('click').click(function(){
		closeConfirmationAlert();
	});

	// opens the confirmationAlert
	function openConfirmationAlert(){
		$('body').css('overflow', 'hidden');
		$('#confirmation-alert').show();
	}
	// closes the confirmation alert
	function closeConfirmationAlert(){
		$('body').css('overflow', 'auto');
		$('#confirmation-alert').hide();
	}

	// insets the Title and Message of confirmationAlert
	function setTitleAndMessage(title = null, message = null){
		$('.confirmation-alert-title').text(title);
		$('.confirmation-alert-message').text(message);
	}
	// insets the Title and Message of confirmationAlert
	function setButtonsMessage(cancelButtonMessage = null, confirmButtonMessage = null){
		$('.cancelbutton').text(cancelButtonMessage);
		$('.confirmbutton').text(confirmButtonMessage);
	}

	// cleans confirmation
	function cleanConfirmationAlert(){
		$('.confirmation-alert-title').text();
		$('.confirmation-alert-message').text();
	}
</script>