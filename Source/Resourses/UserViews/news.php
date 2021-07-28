<?php $this->insert('user-header', ['title' => ucfirst(translate('new'))]) ?>

<section class="posts-area">
	<div class="post-title">
		<?php echo ucfirst(translate('news')) ?>
	</div>
	<?php foreach($posts as $post){ ?>
	<div class="post-card" data-post-id="<?= $post['postId'] ?>">
		<div class="post-card-first-level">
			<div class="profile-photo-wrapper">
				<img src="<?= $post['profilePhoto'] ?>">		
			</div>
			<div class="section-vertical-wrapper post-person-data">
				<span class="post-owner-name"><?= $post['name'] ?></span>
				<span class="post-owner-address"><?= $post['city&State'] ?></span>
				<span class="post-owner-classification"><?= $post['roleData']['personScoreStars'] ?></span>			
			</div>
			<div class="section-vertical-wrapper post-options">
				<div class="post-card-top-options">
					<?php if($post['hasRole']){ ?>
						<img src="Source/Resourses/External/icons/<?= $post['roleData']['roleIconURL'] ?>" title="<?php echo ucfirst(translate($post['roleData']['roleName'])) ?>">
						<a href="<?= $post['roleData']['personalPageURL'] ?>" title="<?php echo ucfirst(translate('my professional page')) ?>">
							<img src="Source/Resourses/External/icons/browser.svg" href="<?= $post['roleData']['personalPageURL'] ?>">
						</a>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="post-content">
			<img src="<?= $post['profilePhoto'] ?>">
			<div class="content-of-post">
				<h3 class="title-of-post-card">
					<?php echo $post['postTitle']; ?>
				</h3>
				<h6 class="post-text">
					<?php 
						if(strlen($post['postDescription']) > 50){
							for($i = 0; $i < strlen($post['postDescription']); $i++){
								echo $post['postDescription'][$i];
							}
							echo ' ...<br>';
							echo '<a class="see-all-text" data-descriptionText="" title="'.ucfirst(translate('read all the text')).'">'.ucfirst(translate('read more')).'</a>';
						}else{
							echo $post['postDescription'];
						} 
					?>	
				</h6>
			</div>
			<div class="post-option">
				<?php 
					$personId = isset($_SESSION['personId']) ? $_SESSION['personId'] : null;
					if(!is_null($personId) && $post['id'] == $personId){
				?>
				<img class="edit-post" src="Source/Resourses/External/icons/edit.svg" title="<?php echo ucfirst(translate('edit')) ?>">
				<img class="remove-post" src="Source/Resourses/External/icons/remove.svg" title="<?php echo ucfirst(translate('remove')) ?>">
				<?php } ?>
			</div>
		</div>
		<div class="post-comments">
			<div class="post-title-wrapper">
				<?php echo ucfirst(translate('comments')) ?>
				<div class="number-of-comments" data-numberOfComments="<?= $post['postNumberOfComments'] ?>" style="cursor: pointer;">
					<?= $post['postNumberOfComments'] ?> - <?php echo ucfirst(translate('comments')) ?>
				</div>
			</div>
		</div>
		<div class="post-comments-content">
			<?php if($post['postNumberOfComments'] > 0){ ?>
				<a class="see-comments comments-button"><?php echo ucfirst(translate('see comments')) ?></a>
			<?php }else{ ?>
				<a class="see-comments comments-button"><?php echo ucfirst(translate('see comments')) ?></a>
			<?php } ?>
			<a class="add-comments comments-button"><?php echo ucfirst(translate('add comments')) ?></a>
		</div>
	</div>
	<?php } ?>
	<div class="pagination" style="<?php if(is_null($pages)) echo 'padding: 0px' ?>">
		<?= $pages ?>
	</div>
</section>

<!-- the modal -->
<div id="modal1" class="modal">
	<div class="modal-data content-of-modal">
    	<div class="modal-header">
        	<span class="close" title="<?php echo ucfirst(translate('close')) ?>">&times;</span>
			<h2 class="modal-title center-text"><?php echo ucfirst(translate('post comments')) ?></h2>
		</div>
		<div class="content-wrapper center-text"></div>
    </div>
</div>
<?php include "Source/Resourses/Components/edit-comment-modal.php" ?>
<?php include "Source/Resourses/Components/edit-post-modal.php" ?>
<script src="Source/Resourses/JS-functions/modal.js"></script>
<script type="text/javascript">
	var a = $('.post-content img');
	var path = 'http://localhost/traposeretalhos/Source/Files/img/';
	var names = ['pants.jpg', 'shirt.jpg'];
	i = 0;
	a.each(function(){
		$(this).attr('src', path+names[i]);
		i++;
	});

	// open the AddComments modal in order to add a new comment 
	 var postId = null;
	 var postElement = null;
	$('.add-comments').off('click').click(function(){
		$('.content-wrapper').addClass('center-text');
		postElement = $(this).parents('.post-card');
		postId = $(this).parents('.post-card').attr('data-post-id');
		$('.modal-title').html("<?php echo ucfirst(translate('add a comment')) ?>");
		var html = '<textarea id="new-comment-text" class="comment-text-wrapper" style="width:90%;" placeholder="<?php echo ucfirst(translate('type here')) ?>"></textarea>';
		html += '<div class="max-text-limit-alert" style="display: none;text-align: right;width: 90%;margin: auto;padding: 2%;"><?php echo ucfirst(translate('maximum number of words reached')) ?></div>';
		html += '<div class="options-new-comments hidden"><a id="cancel-new-comment" class="comments-button">'+"<?php echo ucfirst(translate('cancel')) ?>"+'</a><a id="save-new-comment" class="comments-button">'+"<?php echo ucfirst(translate('confirm')) ?>"+'</a></div>';
		$('.content-wrapper').html(html);
		openModal();
	});
	$(document).off("click", "#save-new-comment");
	$(document).on("click","#save-new-comment",function(){
		var commentText = $('#new-comment-text').val();
		$.ajax({
			url: 'comment/save',
			type: 'POST',
			data: {postId: postId, userComment: commentText},
			success: function(data){
				data = data.replace('70', '');
				result = JSON.parse(data);
		    	if(result['success'] == true){
		    		closeModal();
		    		alert(result['message']);
		    		updateNumberOfThisComment(postId);
		    	}else{
		    		if(result['performLogin'] == true){
		    			window.location = "<?= '/'.URL['urlDomain']?>/login";
		    		}else{
			    		alert(result['message']);
		    		}
		    	}
			}
		});
		closeModal();
	});
	$(document).off("click", "#cancel-new-comment");
	$(document).on("click","#cancel-new-comment",function(){
		closeModal();
	});
	// show options buttons on at least one data
	$(document).off("input", "#new-comment-text");
	$(document).on("input","#new-comment-text",function(){
		// shows the buttons in order to save them
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

	// edits the comment
	var commentId = null;
	var formerComment = null;
	var positionAtModal = null;
	$(document).off("click","#edit-comment");
	$(document).on("click","#edit-comment",function(){
		positionAtModal = $('.modal-data').scrollTop();
		closeModal();
		formerComment = $(this).parent().parent().find('.comment-text-wrapper').text();
		$('.edit-comment').val(formerComment);
		commentId = $(this).parents('.comments-of-people').attr('data-commentid');
		openModal('modal2');
	});

	// removes the comment
	var postIdOfComment = null;
	var commentIdToRemove = null;
	$(document).off("click","#remove-comment");
	$(document).on("click","#remove-comment",function(){
		postIdOfComment = $('.content-wrapper').attr('data-postId');
		commentIdToRemove = $(this).parents('.comments-of-people').attr('data-commentId');
		closeModal();
		openConfirmationAlert();
	});
	// confirmationAlert button clicks
	$(document).off('click', '.confirmbutton');
	$(document).on('click', '.confirmbutton', function(){
		if($('.removePost-now').length > 0){
			removePost(postId);
		}else{
			removeComment(commentIdToRemove, postIdOfComment);
		}
		closeConfirmationAlert();
	});
	$(document).off('click', '.cancelbutton');
	$(document).on('click', '.cancelbutton', function(){
		gatherCommentsData(postIdOfComment);
	});
	function removeComment(commentId, postId){
		$.ajax({
			url: 'comment/remove',
			type: 'POST',
			data: {commentId: commentId},
			dataType: 'JSON',
			success: function(result){
		    	if(result['success'] == true){
		    		gatherCommentsData(postId);
		    		updateNumberOfThisComment(postId);
		    		alert(result['message']);
		    	}else{
		    		alert(result['message']);
		    	}
			}
		});
	}

	// gather data via ajx of Post by its Id and feed the modal
	var idOfPost = null;
	$(document).off("click",".see-comments");
	$(document).on("click",".see-comments",function(){
		$('.content-wrapper').removeClass('center-text');
		postElement = $(this).parents('.post-card');
		idOfPost = $(this).parents('.post-card').attr('data-post-id');
		gatherCommentsData(idOfPost);
	});
	// opens the comments list
	$('.number-of-comments').off('click').click(function(){
		var numberOfComments = $(this).attr('data-numberOfComments');
		if(numberOfComments == 0)
			return;
		$('.see-comments').click();
	});
    // responsible by requesting the comments data related to this Post
	function gatherCommentsData(postId = null){
		$('.content-wrapper').attr('data-postId', postId);
		$('.modal-title').html("<?php echo ucfirst(translate('post comments')) ?>");
		$.ajax({
			url: 'comment/getcomments',
			type: 'POST',
			data: {postId: postId},
			dataType: 'JSON',
			success: function(result){
		    	if(result['success'] == true){
		    		feedCommentsModal(result['content']);
		    	}else{
		    		alert(result['message']);
		    	}
			},
		});
	}
	// make the ajax request for the click on the post 'seeComment' button
	function feedCommentsModal(commentsArray){
		var htmlOfComment = '';
		for(i = 0; i < commentsArray.length; i++){
			htmlOfComment += '<div class="comments-of-people" data-commentId="'+commentsArray[i]['id']+'">';
			var profilePhotoPath = commentsArray[i]['person']['profilePhoto'];
			if(profilePhotoPath == null){
				profilePhotoPath = "<?= URL['iconsPath'].'account.svg' ?>";
			}
			htmlOfComment += '<div class="comment-profile-img-wrapper"><img src="'+profilePhotoPath+'">'+commentsArray[i]['person']['fullName']+'</div>';
			htmlOfComment += '<div class="comment-section"><div class="comment-dates">';
			if(commentsArray[i]['dateOfLastUpdate'] == null){
				htmlOfComment += '<a><?php echo ucfirst(translate('created at')); ?>: '+commentsArray[i]['dateOfCreation']+'</a>';
			}else{
				htmlOfComment += '<a><?php echo ucfirst(translate('last updated at')); ?>: '+commentsArray[i]['dateOfLastUpdate']+'</a>';
			}
			htmlOfComment += '</div><div class="comment-text-wrapper">';
			htmlOfComment += commentsArray[i]['userComment']+'</div>';
			htmlOfComment += '<div class="comment-options">';
			var likes = commentsArray[i]['likes'];
			likes = likes == null || likes == 0 ? '' : likes;
			var isLikedByUser = commentsArray[i]['userIsOwnerOfLike'];
			isLikedByUser = isLikedByUser == true ? 'selected-now' : '';
			var heartState = isLikedByUser == 'selected-now' ? '3' : '1';
			htmlOfComment += '<a class="like-it '+isLikedByUser+'"><img class="heart-icon" src="Source/Resourses/External/icons/heart-'+heartState+'.svg"><div class="total-of-likes">'+likes+'</div></a>';
			if(commentsArray[i]['isOwner'] == true){
				htmlOfComment += '<a id="remove-comment" class="comments-button" title="<?php echo ucfirst(translate('remove your comment')); ?>"><?php echo ucfirst(translate('remove')); ?></a>';
				htmlOfComment += '<a id="edit-comment" class="comments-button" title="<?php echo ucfirst(translate('edit your comment')); ?>"><?php echo ucfirst(translate('edit')); ?></a></div>';
			}
			htmlOfComment += '</div></div></div>';
		} 
		$('.content-wrapper').html(htmlOfComment);
		if(positionAtModal != null)
			$('.modal-data').scrollTop(positionAtModal);
		openModal();
	}
	// updates the number of comments of a certain Post
	function updateNumberOfThisComment(postId){
		$.ajax({
			url: 'comment/gettotalofcomment',
			type: 'POST',
			data: {postId: postId},
			dataType: 'JSON',
			success: function(result){
				postElement.find('.number-of-comments').attr('data-numberofcomments', result);
				var text = result+' - <?php echo ucfirst(translate('comments')) ?>';
				postElement.find('.number-of-comments').text(text);
				if(result > 0){
					postElement.find('.see-comments').show();
				}
			},
			error: function(){
				window.location.reload();
			}
		});
	}

	$(document).off("mouseover",".like-it");
	$(document).on("mouseover",".like-it",function(){
		if($(this).hasClass('selected-now') == true)
			return;
		$(this).find('.heart-icon').addClass('fadeOutSlowly');
		$(this).find('.heart-icon').on("animationend", function(){
	        $(this).attr('src', 'Source/Resourses/External/icons/heart-2.svg');
		    $(this).removeClass('fadeOutSlowly');
	    });
	});
	$(document).off("mouseout",".like-it");
	$(document).on("mouseout",".like-it",function(){
		if($(this).hasClass('selected-now') == true)
			return;
		$(this).find('.heart-icon').addClass('fadeOutSlowly');
		$(this).find('.heart-icon').on("animationend", function(){
	        $(this).attr('src', 'Source/Resourses/External/icons/heart-1.svg');
	    	$(this).removeClass('fadeOutSlowly');
	    });
	});
	$(document).off("click",".like-it");
	$(document).on("click",".like-it",function(){
		var commentId = $(this).parents('.comments-of-people').attr('data-commentid');
		var element = $(this);
		if($(this).hasClass('selected-now') == true){
			$(this).removeClass('selected-now');
			manageCommentsLike(commentId, null, element);
			return;
		}
		$(this).addClass('selected-now');
		$(this).find('.heart-icon').attr('src', 'Source/Resourses/External/icons/heart-3.svg');
		manageCommentsLike(commentId, true, element);
	});

	// update like number
	function manageCommentsLike(commentId, addNew, element){
		$.ajax({
			url: 'comment/managelikes',
			type: 'POST',
			data: {commentId: commentId, add: addNew},
			dataType: 'JSON',
			success: function(result){
				if(result.success == false && result.performLogin == true){
					window.location = "<?= '/'.URL['urlDomain']?>/login";
					return;
				}
				alert(result.message);
				var numberOfLikes = result.likesNumber;
				if(numberOfLikes == 0){
					numberOfLikes = '';
				}
				element.find('.total-of-likes').html(numberOfLikes);
				alert(result.likesNumber);
				alert('loader ended');
			},
		});
	}

	// post options
	var postId = null;
	var postElement = null;
	$(document).off("click",".edit-post");
	$(document).on("click",".edit-post",function(){
		postElement = $(this).parents('.post-card');
		postId = postElement.attr('data-post-id');
		var postTitle = $(this).parents('.post-content').find('.title-of-post-card').text().replace(/^\s+|\s+$/gm,'');
		var postText = $(this).parents('.post-content').find('.post-text').text().replace(/^\s+|\s+$/gm,'');
		$('#title-of-text').val(postTitle);
		$('#description-of-text').text(postText);
		openModal('modal3');
		getPostData(postId);
	});
	$(document).off("click",".remove-post");
	$(document).on("click",".remove-post",function(){
		postElement = $(this).parents('.post-card');
		postId = $(this).parents('.post-card').attr('data-post-id');
		$(this).addClass('removePost-now');
		setTitleAndMessage("<?php echo ucfirst(translate('remove post')) ?>", "<?php echo ucfirst(translate('do you really want to remove this post?')) ?>");
		openConfirmationAlert();
	});
	function removePost(postId){
		$.ajax({
			url: 'post/remove',
			type: 'POST',
			data: {id: postId},
			dataType: 'JSON',
			success: function(result){
		    	if(result['success'] == true){
		    		postElement.remove();
		    		alert(result['message']);
		    	}else{
		    		alert(result['message']);
		    	}
			}
		});
	}

	function getPostData(postId){
		$.ajax({
			url: 'post/getdata',
			type: 'POST',
			data: {id: postId},
			dataType: 'JSON',
			success: function(result){
		    	if(result['success'] == true){
		    		alert(result['message']);
		    	}else{
		    		alert(result['message']);
		    	}
			}
		});
	}

</script>

<style type="text/css">
	.fadeOutSlowly{
	    animation: black-heart 0.1s;
	}
	.fadeInSlowly{
		animation: red-heart 0.2s;
	}
	@keyframes black-heart{
	    0%{
	    	opacity: 1;
	    }
	    100%{
	    	opacity: 0;
	    }
	}
	@keyframes red-heart{
	    50%{
	    	opacity: 0.2;
	    }
	    100%{
	    	opacity: 1;
	    }
	}
	.edit-post{
		border-radius: unset!important;
	}
	.post-title{
		padding: 2%;
		width: 90%;
		background: rgba(0, 0, 0, 0.67);
		margin: auto;
		border-radius: 5px;
		color: #fff;
		text-align: center;
		font-size: 25px;
	}
	.post-card{
		margin: 10% 0 10% 0;
	}
	.post-card-first-level{
		display: flex;
		width: 80%;
		margin: auto;
		padding: 2%;
		background: rgba(255, 255, 255, 0.94);
	}
	.section-vertical-wrapper{
		display: flex;
		flex-direction: column;
	}
	.post-person-data{
		margin-left: 5%;
		margin-top: auto;
		margin-bottom: auto;
		width: 50%;
	}
	.post-owner-name{
		color: #9D9930;
	}
	.post-person-data span{
		margin: unset!important;
	}
	.post-card-top-options{
		display: flex;
		justify-content: space-around;
		border-radius: 10px;
		background: #EBEAEA;
		margin-top: auto;
		margin-bottom: auto;
	}
	.post-card-top-options>img{
		width: 40%;
		margin: 5%;
	}
	.post-card-top-options>a{
		width: 40%;
		margin: 5%;
	}
	.profile-photo-wrapper{
		margin-bottom: auto;
		width: 10%;
	}
	.profile-photo-wrapper>img{
		width: 100%;
		border-radius: 50%;
		margin-top: auto;
		margin-bottom: auto;
	}
	.post-options{
		margin-left: auto;
		margin-bottom: auto;
		width: 15%;
	}
	.post-content{
		display: flex;
		width: 80%;
		margin: auto;
		background-color: rgba(193, 194, 183, 0.52);
		padding: 2%;
	}
	.post-content img{
		width: 25%;
		border-radius: 10px;
	}
	.content-of-post{
		width: 60%;
		margin-left: auto;
		margin-top: auto;
		margin-bottom: auto;
	}
	.content-of-post > h3{
		font-size: 20px;
		font-style: italic;
		color: #6c6c6c;
	}
	.content-of-post > h6{
		font-size: 16px;
		font-weight: inherit;
	}
	.post-option img{
		width: 30px!important;
	    margin-bottom: auto;
	    opacity: 0.5;
	}
	.post-option img:hover{
		opacity: unset;
		transition: 0.5s;
	}
	.post-comments{
		display: flex;
		width: 80%;
		margin: auto;
		padding: 2%;
		background: rgba(0, 0, 0, 0.67);
		text-align: left;
		color: #ffffff;
		font-size: 20px;
	}
	.post-comments-content{
		display: flex;
		width: 80%;
		padding: 2%;
		margin: auto;
		background: rgba(255, 255, 255, 0.94);
	}
	.comments-of-people{
		width: 100%;
		display: flex;
		justify-content: space-between;
		margin: 5% 0 5% 0;
	}
	.comments-of-people img{
		width: 100px;
		border-radius: 50%;
	}
	.comment-text-wrapper{
		margin: auto;
		border-radius: 5px;
		text-indent: 5%;
		padding: 1%;
		border: 0.5px solid #bdbdbd;
		height: 50%;
		background: #e3e3e333;

	}
	.post-title-wrapper{
		width: 100%;
		display: flex;
		justify-content: space-between;
	}
	.pagination{
		width: 80%;
		margin: auto;
		margin-top: -5%;
		border-radius: 5px;
		background: #fff;
		padding: 2%;
		text-align: right;
		font-size: 20px;
	}
	.pagination a,span{
		text-decoration: none;
		margin: 2%;
		color: #000000;
		cursor: pointer;
		padding: 1%;
		border-radius: 5px;
	}
	.pagination span{
		background: rgba(218, 221, 90, 0.78);
	}
	.pagination span:hover{
		background: #dddddd;
	}
	.pagination a:hover{
		text-decoration: underline;
	}
	.see-all-text{
		margin: unset!important;
		font-size: 20px;
		color: #7B7820F2;
		text-decoration: underline;
	}
	.comment-profile-img-wrapper{
		width: 10%;
		text-align: center;
	}
	.comment-profile-img-wrapper>img{
		width: 100%;
	}
	.options-new-comments{
		display: flex;
		justify-content: right;
		width: 90%;
		padding: 1%;
		margin: auto;
	}
	.options-new-comments > a{
		margin: 15px 5px 0 5px;
	}
	.hidden{
		display: none;
	}
	.comment-section{
		width: 80%;
		margin-right: auto;
		margin-left: auto;
	}
	.comment-dates{
		text-align: right;
	}
	.comment-dates > a{
		padding: 5px;
		opacity: 0.5;
	}
	.comment-options{
		text-align: right;
		margin-top: 2%;
	}
	.comment-options > a{
		padding: 1%!important;
		font-size: 15px!important;
		margin-left: 2%;
	}
	#new-comment-text{
		padding: 5%;
		text-align: left;
		margin-top: 10%;
		border: 0.5px solid #bdbdbd;
		background: #e3e3e333;
		border-radius: 5px;
		width: 90%;
	}
	#new-comment-text:focus{
 		outline: none !important;
	    box-shadow: 0 0 10px #bdbdbd;
	}
	.like-it{
		float: left;
		margin-left: unset;
		margin-top: -1%;
	}
	.part-1-like:hover{
		/*transition: 1s;
		opacity: 0.5;
		background: red;*/
	}

	.like-it > img{
		width: 25px;
		height: 25px;
		border-radius: unset;
	}
	.total-of-likes{
		margin-top: -45%;
	}

	@keyframes show-red-heart{
		from {
			background-color: red
		}
		to {
			background-color: yellow;
		}
	}
</style>

<?php $this->insert('user-footer') ?>