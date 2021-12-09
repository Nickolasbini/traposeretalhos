<div class="remove-icon" style="display:none;">
	<i id="remove-action" class="fas fa-minus-circle" title="<?php echo ucfirst(translate('remove')); ?>"></i>
</div>

<script type="text/javascript">
	$(document).off("click","#remove-action");
	$(document).on("click","#remove-action",function(){
		setIds('remove-icon-click-cancel', 'remove-icon-click-accept');
		setTitleAndMessage("<?php echo ucfirst(translate('remove work photo')) ?>", "<?php echo ucfirst(translate('do you really want to remove this photo?')) ?>");
		openConfirmationAlert();
	});
</script>