<div id="modal2" class="modal">
    <div class="modal-data content-of-modal">
    	<div class="modal-header">
        	<span class="close-edit" title="<?php echo ucfirst(translate('close')) ?>">&times;</span>
			<h2 class="center-text"><?php echo ucfirst(translate('edit comments')) ?></h2>
		</div>
		<textarea class="edit-comment center-text"></textarea>
		<div class="max-text-limit-alert" style="display: none;text-align: right;width: 90%;margin: auto;padding: 2%;"><?php echo ucfirst(translate('maximum number of words reached')) ?></div>
		<div class="options-new-comments hidden"><a id="cancel-new-comment-eddition" class="comments-button"><?php echo ucfirst(translate('cancel')) ?></a><a id="save-editted-comment" class="comments-button"><?php echo ucfirst(translate('confirm')) ?></a></div>
    </div>
</div>

<script type="text/javascript">

	$(document).off("input", ".edit-comment");
	$(document).on("input",".edit-comment",function(){
		var sizeOfComment = $(this).val().length;
		if(sizeOfComment < 399){
			$(this).removeClass('not-allowed');
			$('.max-text-limit-alert').hide();
		}
		if(sizeOfComment == 0){
			if($('.options-new-comments').hasClass('hidden') == false)
				$('.options-new-comments').addClass('hidden');
			return;
		}else if(sizeOfComment > 399){
			// show message
			$('.max-text-limit-alert').show();
			$(this).addClass('not-allowed');
			if($('.options-new-comments').hasClass('hidden') == false)
				$('.options-new-comments').addClass('hidden');
			return;
		}else{
			if($('.options-new-comments').hasClass('hidden'))
				$('.options-new-comments').removeClass('hidden');
		}
	});
	
	$(document).off("click", "#cancel-new-comment-eddition");
	$(document).on("click","#cancel-new-comment-eddition",function(){
		closeModal('modal2');
		openModal();
	});
	$(document).off("click", ".close-edit");
	$(document).on("click",".close-edit",function(){
		closeModal('modal2');
		openModal();
	});
	$(document).off("click", "#save-editted-comment");
	$(document).on("click","#save-editted-comment",function(){
		var edditedComment = $('.edit-comment').val();
		$.ajax({
			url: 'comment/save',
			type: 'POST',
			data: {id: commentId, postId: idOfPost, userComment: edditedComment},
			success: function(data){
				data = data.replace('70', '');
				result = JSON.parse(data);
		    	if(result['success'] == true){
		    		closeModal('modal2');
		    		openModal();
		    		alert(result['message']);
		    		gatherCommentsData(idOfPost);
		    	}else{
		    		alert(result['message']);
		    	}
			}
		});
	});
</script>

<style type="text/css">
	.edit-comment{
		padding: 5%;
		text-align: left;
		margin-top: 10%;
		border: 0.5px solid #bdbdbd;
		background: #e3e3e333;
		border-radius: 5px;
		width: 90%;
	}
	.edit-comment:focus{
		outline: none !important;
	    box-shadow: 0 0 10px #bdbdbd;
	}
	.options-new-comments{
		width: unset!important;
		padding: unset!important;
	}
	.close-edit{
	    color: #aaa;
	    font-size: 28px;
	    font-weight: bold;
	    position: absolute;
	    left: 85%;
	}
	.close-edit:hover,
	.close-edit:focus {
	    color: black;
	    text-decoration: none;
	    cursor: pointer;
	}
</style>