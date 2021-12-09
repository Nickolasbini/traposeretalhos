<?php $this->insert('user-header', ['title' => ucfirst(translate('posts'))]) ?>

<h1 class="s" style="text-align: center">Adicionar uma postagem</h1>

<div style="cursor: pointer" id="new-post">+</div>

<?php $this->insert('user-footer') ?>

<div id="modal5" class="modal">
	<div class="modal-data content-of-modal">
    	<div class="modal-header">
        	<span class="close" title="<?php echo ucfirst(translate('close')) ?>">&times;</span>
			<h2 class="modal-title center-text"><?php echo ucfirst(translate('new post')) ?></h2>
		</div>
		<div class="content-wrapper center-text new-post-content">
			<div class="required input-wrapper">
				<label><?php echo ucfirst(translate('categories')); ?></label>
				<select id="category-of-post">
				<?php 
					foreach($categories as $category){
						echo '<option data-id="'.$category['id'].'">'.$category['categoryTranslation'].'</option>';
					} 
				?>
				</select>
			</div>
			<div class="required input-wrapper">
				<label><?php echo ucfirst(translate('post title')); ?></label>
				<input id="postTitle" type="text">
			</div>
			<div class="required input-wrapper">
				<label><?php echo ucfirst(translate('post description')); ?></label>
				<input id="postDescription" type="text">
			</div>
			<div class="required photo-wrapper">
				<label><?php echo ucfirst(translate('add a post photo')); ?></label>
				<img id="selected-photo" width="180px;" height="250px" style="margin: auto; border-radius: 15px;" src="Source/Resourses/External/icons/account.svg">
				<input id="photo-chooser-input" class="profilePhoto" type="file" name="profile-picute" style="display: none;">
				<div class="photo-icon" title="<?php echo ucfirst(translate('choose a photo')); ?>">
					<div id="add-photo-button">
						<i class="fas fa-plus-circle"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-options">
			<a class="modal-options-buttons cancel-post"><?php echo ucfirst(translate('cancel')); ?></a>
			<a class="modal-options-buttons save-post"><?php echo ucfirst(translate('save')); ?></a>
		</div>
    </div>
</div>
<script src="Source/Resourses/JS-functions/modal.js"></script>
<script type="text/javascript">
		
	$('#new-post').on('click', function(){
		openModal('modal5');
	});

	$('#photo-chooser-input').on('input', function(){
		openFile('selected-photo');
	});

	// to set img preview
	function openFile(imgElementId = null){
		if(imgElementId == null)
			return;
	    var input = event.target;
	    var reader = new FileReader();
	        reader.onload = function(){
	        var dataURL = reader.result;
	        var output = document.getElementById(imgElementId);
	        output.src = dataURL;
	    };
	    reader.readAsDataURL(input.files[0]);
	    defaultImage = false;
	};

	$('.save-post').on('click', function(){
		var postTitle 		= $('#postTitle').val();
		var postDescription = $('#postDescription').val();
		var category 		= $('#category-of-post option:selected').attr('data-id');
		var postPhoto 		= $('#selected-photo').attr('src');		
		$.ajax({
			url: 'post/save',
			type: 'POST',
			data: {postTitle: postTitle, postDescription: postDescription, category: category, postPhoto: postPhoto},
			dataType: 'JSON',
			success: function(data){
				closeModal('modal5');
				openToast(data.message);
			}
		});
	});

	$('#selected-photo').on('click', function(){ 
		$('#photo-chooser-input').trigger('click');
	});
	$('#add-photo-button').on('click', function(){ 
		$('#photo-chooser-input').trigger('click');
	});

</script>

<style type="text/css">
	.new-post-content{
		display: flex;
		flex-direction: column;
		justify-content: center;
		width: 80%;
		margin: auto;
	}
	#new-post{
		font-size: 30px;
		margin: auto;
		text-align: center;
		background: white;
		width: 10%;
		border-radius: 15px;
	}
	.input-wrapper{
		display: flex;
		justify-content: space-between;
	}
	.input-wrapper>label{
		width: 40%;
		margin: 2%;
	}
	.input-wrapper>input, .input-wrapper>select{
		width: 60%;
		margin: 2%;
	}
	.photo-wrapper{
		display: flex;
		flex-direction: column;
		margin-top: 5%;
	}
	.photo-wrapper>label{
		margin: 2%;
	}
	#selected-photo{
		margin: 2%;
	}
	.photo-icon{
		display: block;
		margin-top: 2%;
	}
	#add-photo-button{
		font-size: 30px;
	}
</style>