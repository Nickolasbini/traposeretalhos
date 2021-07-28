<div id="modal3" class="modal">
    <div class="modal-data content-of-modal">
    	<div class="modal-header">
        	<span class="close-post-edition" title="<?php echo ucfirst(translate('close')) ?>">&times;</span>
			<h2 class="center-text"><?php echo ucfirst(translate('edit post')) ?></h2>
		</div>
		<div class="wrapper-of-inputs">
			<label for="title-of-text">Edit title</label>
			<input type="text" id="title-of-text" class="inputs-of-post-modal center-text"></input>
		</div>
		<div class="wrapper-of-inputs">
			<label for="description-of-text">Edit description of post</label>
			<textarea id="description-of-text" class="inputs-of-post-modal center-text"></textarea>
		</div>
		<div class="wrapper-of-inputs">
			<label class="photos-of-post">Edit photo</label>
			<textarea class="edit-post-content center-text" class="inputs-of-post-modal center-text"></textarea>
		</div>
		<div class="max-text-limit-alert" style="display: none;text-align: right;width: 90%;margin: auto;padding: 2%;"><?php echo ucfirst(translate('maximum number of words reached')) ?></div>
		<div class="post-operation-buttons hidden">
			<a id="cancel-post-update" class="comments-button">
				<?php echo ucfirst(translate('cancel')) ?>
			</a>
			<a id="save-post-update" class="comments-button">
				<?php echo ucfirst(translate('confirm')) ?>
			</a>
		</div>
    </div>
</div>

<script type="text/javascript">

	$(document).off("input", ".inputs-of-post-modal");
	$(document).on("input",".inputs-of-post-modal",function(){
		var sizeOfComment = $(this).val().length;
		if(sizeOfComment < 399){
			$(this).removeClass('not-allowed');
			$('.max-text-limit-alert').hide();
		}
		if(sizeOfComment == 0){
			if($('.post-operation-buttons').hasClass('hidden') == false)
				$('.post-operation-buttons').addClass('hidden');
			return;
		}else if(sizeOfComment > 399){
			// show message
			$('.max-text-limit-alert').show();
			$(this).addClass('not-allowed');
			if($('.post-operation-buttons').hasClass('hidden') == false)
				$('.post-operation-buttons').addClass('hidden');
			return;
		}else{
			if($('.post-operation-buttons').hasClass('hidden'))
				$('.post-operation-buttons').removeClass('hidden');
		}
	});
	
	$(document).off("click", "#cancel-post-update");
	$(document).on("click","#cancel-post-update",function(){
		closeModal('modal3');
	});
	$(document).off("click", ".close-post-edition");
	$(document).on("click",".close-post-edition",function(){
		closeModal('modal3');
	});
	$(document).off("click", "#save-post-update");
	$(document).on("click","#save-post-update",function(){
		var edditedPost = $('.edit-post-content').val();
		$.ajax({
			url: 'post/save',
			type: 'POST',
			data: {id: postId, postTitle: postTitleValue, postDescription: edditedPost},
			success: function(data){
				data = data.replace('70', '');
				result = JSON.parse(data);
		    	if(result['success'] == true){
		    		closeModal('modal3');
		    		alert(result['message']);
		    	}else{
		    		alert(result['message']);
		    	}
			}
		});
	});
</script>

<style type="text/css">
	.close-post-edition{
		left: 87%!important;
	}
	.wrapper-of-inputs{
		display: flex;
		margin-top: 10%;
		flex-direction: column;
	}
	.wrapper-of-inputs > label{
		margin-bottom: 5%;
		text-align: center;
		font-size: 20px;
		font-style: italic;
		color: #6c6c6c;
	}
	.inputs-of-post-modal{
		padding: 5%;
		text-align: left;
		border: 0.5px solid #bdbdbd;
		background: #e3e3e333;
		border-radius: 5px;
		width: 90%;
		font-size: 16px;
	}
	.inputs-of-post-modal:focus{
		outline: none !important;
	    box-shadow: 0 0 10px #bdbdbd;
	}
	.post-operation-buttons{
		display: flex;
		justify-content: right;
		margin: auto;
	}
	.post-operation-buttons a{
		margin: 15px 5px 0 5px;
	}
	.close-post-edition{
	    color: #aaa;
	    font-size: 28px;
	    font-weight: bold;
	    position: absolute;
	    left: 85%;
	}
	.close-post-edition:hover,
	.close-post-edition:focus {
	    color: black;
	    text-decoration: none;
	    cursor: pointer;
	}
</style>