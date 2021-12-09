<?php $this->insert('user-header', ['title' => ucfirst(translate('my personal page'))]) ?>
<link href="/<?= URL['urlDomain'] ?>/Source/Resourses/CSS/carousel.css" rel="stylesheet"></link>
<section class="my-data-wrapper" data-professionalId="<?= $person['id']; ?>">
	<div class="main-data-wrapper">
		<div class="background-picture" style="background-image: url(<?= $person['role']['backgroundPhoto']['webPath'] ?>);">
			<div class="profile-photo">
				<a href="/<?= URL['urlDomain'] ?>/myaccount">
					<img width="350px" src="<?= $person['profilePhoto'] ?>" >
				</a>
			</div>
		</div>
		<div class="centralized">
			<h5 class="score"><?= $person['role']['personScoreStars'] ?></h5>
			<h4 class="profile-name"><?= $person['fullName'] ?></h4>
		</div>
		<div class="role-icon">
			<?php 
				foreach(json_decode($_SESSION['roles'], true) as $role){ 
					if($role['id'] == $person['role']['roleId']){
						echo '<img class="role-icon" src="/'.URL['urlDomain'].'/Source/Resourses/External/icons/'.$person['role']['roleIconURL'].'">';
						break;
					}
				} ?>
		</div>
	</div>
	<div class="about-profile">
		<div class="about-data">
			<div class="about-me gray-border">
				<h6 class="left-position title-of-category"><?php echo ucfirst(translate('about me')); ?>:</h6>
				<p class="person-description meddium-text">
					<?php 
						if(isset($_SESSION['personId']) && $_SESSION['personId'] == $person['id']){
							echo '<i class="fas fa-plus-circle" style="font-size:2.5em;"></i>';
						}
					?>
					<?= $person['personDescription']; ?>
				</p>
			</div>
			<div class="my-habilities gray-border">
				<h6 class="left-position title-of-category"><?php echo ucfirst(translate('my skills')); ?>:</h6>
				<p class="person-habilities meddium-texts">
					<?php 
						if(isset($_SESSION['personId']) && $_SESSION['personId'] == $person['id']){
							echo '<i class="fas fa-plus-circle" style="font-size:2.5em;"></i>';
						}
					?>
					<?= $person['personHabilities']; ?>
				</p>
			</div>
		</div>
	</div>
	<div class="slideshow-wrapper">
		<div class="left-position"><?php echo ucfirst(translate('my works')); ?>:</div>
		<div class="slideshow-container"></div>
		<div class="dots-position"></div>
	</div>
	<div class="add-new-works" title="<?php echo ucfirst(translate('Add works to be displayed here')); ?>" style="display: none;">
		<i class="fas fa-plus-circle"></i>
	</div>

	<?php if(!isset($_SESSION['personId']) || (isset($_SESSION['personId']) && is_null($_SESSION['userRole'])) ){ ?>
		<div class="message-btn" title="<?php echo ucfirst(translate('send a message')); ?>">
			<div class="new-message-flag new-message" style="display: none;"></div>
			<i class="far fa-comment-alt"></i>
		</div>
		<div class="my-messages">
			<div class="header-of-message">
				<img width="45px" src="<?= $person['profilePhoto'] ?>">
				<div class="name-of-target-person">
					<?= $person['fullName'] ?>
				</div>
				<div class="message-icon" title="<?php echo ucfirst(translate('close')); ?>">
					<i class="fas fa-times"></i>
				</div>
			</div>
			<div class="body-of-message">
				<p class="center-text"><?php echo ucfirst(translate('no previous messages')); ?></p>
			</div>
			<div class="message-footer-options">
				<div class="add-photo-to-message">
					<input class="message-file" type="file" style="display: none;">
					<i class="far fa-images"></i>
				</div>
				<input type="text" class="message-text" placeholder="..."></input>
				<div class="send-message-to-target" title="<?php echo ucfirst(translate('send message')); ?>">
					<i class="fas fa-caret-right"></i>
				</div>
			</div>
		</div>
	<?php } ?>
</section>

<div id="modal1" class="modal">
	<div class="modal-data content-of-modal">
    	<div class="modal-header">
        	<span class="close" title="<?php echo ucfirst(translate('close')) ?>">&times;</span>
			<h2 class="modal-title center-text"><?php echo ucfirst(translate('add a new work')) ?></h2>
		</div>
		<div class="content-wrapper center-text">
			<div class="wrapper-of-inputs">
				<label><?php echo ucfirst(translate('write a title for your photo')); ?></label>	
				<input class="description-of-work" type="text" placeholder="..." maxlength="28">
			</div>
			<label class="label-of-photo"><?php echo ucfirst(translate('enter a photo')); ?></label>
			<div class="wrapper-of-inputs highlighterGreen photo-add" title="<?php echo ucfirst(translate('choose a photo')); ?>">
				<img id="selected-work-photo" src="">
				<div class="title-of-photo" style="display: none;"></div>
				<i class="fas fa-plus-circle"></i>	
			</div>
			<input class="input-of-work-photo" type="file" style="display: none;">
		</div>
		<div class="edit-comment center-text" style="display: none;">
			<a id="cancel-new-comment-eddition" class="comments-button"><?php echo ucfirst(translate('cancel')) ?></a>
			<a id="save-editted-comment" class="comments-button"><?php echo ucfirst(translate('confirm')) ?></a>
		</div>
    </div>
</div>
<div id="modal5" class="modal">
	<div class="modal-data content-of-modal">
    	<div class="modal-header">
        	<span class="close" title="<?php echo ucfirst(translate('close')) ?>">&times;</span>
			<h2 class="modal-title center-text"><?php echo ucfirst(translate('add data')) ?></h2>
		</div>
		<div class="content-wrapper center-text">
			<div class="wrapper-of-inputs personDataInput aboutYou">
				<label><?php echo ucfirst(translate('add a decription about you')); ?></label>	
				<textarea class="about-meInput" placeholder="..."></textarea>
			</div>
			<div class="wrapper-of-inputs personDataInput yourSkills">
				<label><?php echo ucfirst(translate('add your skills and experiences')); ?></label>	
				<textarea class="my-skillsInput" type="text" placeholder="..."></textarea>
			</div>
		</div>
		<div class="edit-comment center-text" style="display: none;">
			<a id="cancel-new-comment-eddition" class="comments-button"><?php echo ucfirst(translate('cancel')) ?></a>
			<a id="save-new-person-data" class="comments-button"><?php echo ucfirst(translate('confirm')) ?></a>
		</div>
    </div>
</div>

<?php include "Source/Resourses/Components/removeIcon.php" ?>
<script src="/<?= URL['urlDomain'] ?>/Source/Resourses/JS-functions/modal.js"></script>
<script src="/<?= URL['urlDomain'] ?>/Source/Resourses/JS-functions/slidshow-function.js"></script>
<script type="text/javascript">
	
	var myId = "<?php echo isset($_SESSION['personId']) ? $_SESSION['personId'] : 0 ?>";
	var pagePersonId = "<?php echo isset($person['id']) ? $person['id'] : null ?>";
	var isMeUsing = true;
	var personalPageId = "<?= $personalPageId?>";
	$(document).ready(function() {
		<?php if(isset($_person['id'])){ ?>
			loadMessagesOfThisPage();
			lookForMessages();
		<?php } ?>
		getWorks();
	    if(myId != pagePersonId){
	    	$('.remove-icon').remove();
	    	isMeUsing = false;
		    var description = $('.person-description').text();
		    if(description == ''){
		    	$('.about-me').hide();
		    }
		    var habilities  = $('.person-habilities').text();
		    if(habilities == ''){
		    	$('.my-habilities').hide();
		    }
		}else{
			var description = $('.person-description').text();
		    if(description == ''){
		    	$('.about-me').attr('title', "<?php echo ucfirst(translate('add something about you, let your customers know you')); ?>");
		    	$('.about-me').addClass('plusEffect');
		    }else{
		    	$('.person-description').find('i').hide();
		    	$('.about-me').attr('title', "<?php echo ucfirst(translate('click here to change or add info')); ?>");
		    	$('.about-me').css('cursor', 'pointer');
		    	$('.about-meInput').text(description);
		    }
		    var habilities  = $('.person-habilities').text();
		    if(habilities == ''){
		    	$('.my-habilities').attr('title', "<?php echo ucfirst(translate('add your habilities and experiences')); ?>");
		    	$('.my-habilities').addClass('plusEffect');
		    }else{
		    	$('.person-habilities').find('i').hide();
		    	$('.my-habilities').attr('title', "<?php echo ucfirst(translate('click here to change or add info')); ?>");
		    	$('.my-habilities').css('cursor', 'pointer');
		    	$('.my-skillsInput').text(habilities);
		    }
		}
	});

	$(document).on('click', '#remove-icon-click-accept', function(){
		var slides = $(document).find('.mySlides');
		var documentId = null;
		slides.each(function(){
			if($(this).css('display') == 'block'){
				documentId = $(this).find('.imageOfSlideShow').attr('data-id');
			}
		});
		closeConfirmationAlert();
		if(personalPageId == '' || documentId == ''){
			openToast("<?php echo ucfirst(translate('no data found')); ?>");
			return;
		}
		var wasASuccess = false;
		$.ajax({
			url: '/<?= URL['urlDomain'] ?>/personalpage/removework',
			data: {personalPageId: personalPageId, documentId: documentId},
			type: 'POST',
			dataType: 'JSON',
			success: function(result){
				wasASuccess = result;
			},
			complete: function(){
				if(wasASuccess == false){
					openLoader(false);
					openToast(wasASuccess.message);
					return;
				}
				openLoader(false);
				openToast(wasASuccess.message);
				getWorks();
			}
		});
	});

	var elementToGetData = null;
	$('.about-me').on('click', function(){
		if(isMeUsing == false)
			return;
		elementToGetData = 'about-meInput';
		$('#modal5').find('textarea').val('');
		$('.personDataInput').hide();
		$('.personDataInput').val('');
		$('.aboutYou').show();
		$('.edit-comment').hide();
		openModal('modal5');
		var description = $(this).find('.person-description').text();
		description = description.trim();
		$('.about-meInput').val(description);
	});
	$('.my-habilities').on('click', function(){
		if(isMeUsing == false)
			return;
		elementToGetData = 'my-skillsInput';
		$('#modal5').find('textarea').val('');
		$('.personDataInput').hide();
		$('.personDataInput').val('');
		$('.yourSkills').show();
		$('.edit-comment').hide();
		openModal('modal5');
		var skills = $(this).find('.person-habilities').text();
		skills = skills.trim();
		$('.my-skillsInput').val(skills);
	});

	$('.personDataInput > textarea').on('input', function(){
		if(isMeUsing == false)
			return;
		var value = $(this).val();
		if(value == ''){
			$('.edit-comment').hide();
		}else{
			$('.edit-comment').show();
		}
	});

	$('#save-new-person-data').on('click', function(){
		if(isMeUsing == false)
			return;
		closeModal();
		var dataToUpdate = $('.'+elementToGetData).val();
		if(elementToGetData == 'about-meInput'){
			updatePersonRole({personDescription:dataToUpdate});
		}
		if(elementToGetData == 'my-skillsInput'){
			updatePersonRole({personHabilities:dataToUpdate});
		}
	});

	function updatePersonRole(dataToUpdate){
		if(dataToUpdate == '' || isMeUsing == false)
			return;
		openLoader();
		var response = false;
		$.ajax({
			url: '/<?= URL['urlDomain'] ?>/person/updatesomedata',
			data: {dataToUpdate:dataToUpdate},
			type: 'POST',
			dataType: 'JSON',
			success: function(result){
				response = result;
			},
			complete: function(){
				if(response.success == false){
					openLoader(false);
					openToast(response.message);
					return;
				}
				$('.person-description').html(response.content.personDescription);
				$('.person-habilities').html(response.content.personHabilities);
				openLoader(false);
				openToast(response.message);
			}
		});
	}

	var canNotAddNewWorks = false;
	// gets this personal page works in order to feed carousel of works
	function getWorks(openTheLoader = false){
		if(openTheLoader == true){
			openLoader();
		}
		var hasWorks = true;
		$.ajax({
			url: '/<?= URL['urlDomain'] ?>/personalpage/getmyworks',
			data: {personalPageId: <?= $person['role']['personalPageId'] ?>},
			type: 'POST',
			dataType: 'JSON',
			success: function(result){
				if(result['seccess'] == false){
					return;
				}
				if(myId == pagePersonId){
					$('.add-new-works').show();
		    	}
		    	if(result['success'] == true && result['hasWorks'].length > 0){
		    		var html = '';
		    		var dots = '<div style="text-align:center">';
		    		var works = result['hasWorks'];
		    		var totalOfWorks = works.length;
		    		for(i = 0; i < works.length; i++){
		    			var position = i + 1;

		    			html += '<div class="mySlides fade">';
						html += '<div class="numbertext">'+position+' / '+totalOfWorks+'</div>';
						html += '<img data-id="'+works[i]['documentId']+'" src="'+works[i]['webPath']+'" class="responsive-img imageOfSlideShow">';
						html += '<div class="text">'+works[i]['title']+'</div>';
						html += '</div>';

		    			dots += '<span class="dot" onclick="currentSlide('+i+')"></span>';
		    		}
		    		dots += '</div>';

		    		html += '<a class="prev" onclick="plusSlides(-1)">&#10094;</a>';
					html += '<a class="next" onclick="plusSlides(1)">&#10095;</a>';

					$('.slideshow-container').html(html);				
		    		$('.dots-position').html(dots);
		    	}else{
		    		if(myId != pagePersonId){
		    			$('.slideshow-wrapper').hide();
		    		}else{
		    			var pTags = '<p class="highlighterGreen"><?php echo ucfirst(translate('there are no works to be displayed')); ?></p>';
		    			pTags    += '<p class="highlighterGreen"><?php echo ucfirst(translate('please, click bellow to add a photo of a work')); ?></p>';
		    			$('.slideshow-container').html(pTags);
		    		}
		    		hasWorks = false;
		    	}
		    	if(myId == pagePersonId){
		    		$('.add-new-works').css('width', '100%');
			    	$('.add-new-works').css('text-align', 'center');
		    		if(hasWorks == true){
			    		var removeIconHtml = '<div class="remove-icon">';
						removeIconHtml += '<i id="remove-action" class="fas fa-minus-circle" title="<?php echo ucfirst(translate('remove')); ?>"></i>';
						removeIconHtml += '</div>';
			    		$('.slideshow-container').append(removeIconHtml);
		    		}else{
		    			$('.dots-position').hide();
		    		}
		    	}
			},
			complete: function(){
				if(hasWorks){
					showSlides(slideIndex);
				}
				if(openTheLoader == true){
					openLoader(false);
					$('#loader-overlay').hide();
				}
				if($(document).find('.dot').length >= 10){
					$('.add-new-works').hide();
					canNotAddNewWorks = true;
				}
			}
		});
	}

	$(document).on('click', '.add-new-works', function(){
		if(canNotAddNewWorks == true){
			openToast("<?php echo ucfirst(translate('can not add new works')); ?>");
			return;
		}
		$('.description-of-work').val('');
		$('#selected-work-photo').attr('src', '');
		$('#selected-work-photo').hide();
		$('.title-of-photo').html('');
		$('.title-of-photo').hide();
		$('.photo-add').css('width', '20%');
		$('.photo-add').hide();
		$('.edit-comment center-text').hide();
		$('.label-of-photo').hide();
		openModal();
	});

	$(document).on('click', '.photo-add', function(){
		$('.input-of-work-photo').click();
	});	

	$('.description-of-work').on('input', function(){
		var text = $(this).val();
		if(text.length == 28){
			openToast("<?php echo ucfirst(translate('maximun size of description reached')); ?>");
			return;
		}
		$('.title-of-photo').html(text);
		$('.photo-add').show();
		$('.label-of-photo').show();
	});

	$('.input-of-work-photo').on('input', function(){
		openFile('selected-work-photo');
		$('.photo-add').css('width', '80%');
		$('.title-of-photo').show();
		$('.edit-comment').show()
	});

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
	    $('#'+imgElementId).show();
	};

	// saves the new works
	$('#save-editted-comment').on('click', function(){
		var description = $('.description-of-work').val();
		var photo 		= $('#selected-work-photo').attr('src');
		if(description == '' || photo == ''){
			openToast("<?php echo ucfirst(translate('a photo and a title must be informed')); ?>");
			return;
		}
		closeModal();
		openLoader();
		var wasASuccess = false;
		$.ajax({
			url: '/<?= URL['urlDomain'] ?>/personalpage/addwork',
			data: {description: description, photo: photo},
			type: 'POST',
			dataType: 'JSON',
			success: function(response){
				if(response.success == true){
					wasASuccess = true;
				}
				openToast(response.message);
			},
			complete: function(){
				if(wasASuccess == true){
					getWorks(true);
				}else{
					openLoader(false);
				}
			}
		});
	});

	var targetId = null;
	var noConversation = null;
	$('.message-btn').on('click', function(){
		if(myId == 0){
			setIds('do-not-login', 'perform-login');
			setTitleAndMessage("<?php echo ucfirst(translate('please login first')); ?>", "<?php echo ucfirst(translate('you need to login to be able to send a message')); ?>");
			setButtonsMessage("<?php echo ucfirst(translate('cancel')); ?>", "<?php echo ucfirst(translate('login')); ?>");
			openConfirmationAlert();
			return;
		}
		targetId = $('.my-data-wrapper').attr('data-professionalId');
		openMessagesBoard();
	});

	$(document).off('click', '#perform-login');
	$(document).on('click', '#perform-login', function(){
		window.location.href = '/<?= URL['urlDomain'] ?>/login';
	});

	$(document).on('keypress',function(e) {
	    if(e.which == 13) {
	        sendMessage();
	    }
	});
	$('.send-message-to-target').on('click', function(){
		sendMessage();
	});

	function sendMessage(){
		var messageText = $('.message-text').val();
		if(messageText == ''){
			return;
		}
		$.ajax({
			url: '/<?= URL['urlDomain'] ?>/message/sendmessage',
			data: {targetPersonId: targetId, messageText: messageText},
			type: 'POST',
			dataType: 'JSON',
			success: function(result){
				if(result.success == true){
					noConversation = null;
					fillChatDashboard(fatherMessageId, $(document).find('.body-of-message')[0].scrollHeight, null, true);
					formerNumberOfMessages++;
					return;
				}
				openToast(result.message);
			}
		});
	}

	function setAsSeen(){
		$.ajax({
			url: '/<?= URL['urlDomain'] ?>/message/setasseen',
			type: 'POST',
			data: {fatherMessageId: fatherMessageId},
			dataType: 'JSON',
			complete: function(){
				$('.highlighter').removeClass('highlighter');
			}
		});
	}

	$('.message-icon').on('click', function(){
		openMessagesBoard();
	});
	function openMessagesBoard(){
		if($('.my-messages').hasClass('openned-message') == true){
			$('.my-messages').removeClass('openned-message');
			$('.new-message-flag').hide();
		}else{
			$('.my-messages').addClass('openned-message');
			loadMessagesOfThisPage();
		}
	}

	var fatherMessageId = null;
	function loadMessagesOfThisPage(){
		openLoader();
		var responseData = null;
		$.ajax({
			url: '/<?= URL['urlDomain'] ?>/message/getfathermessagebypersonids',
			type: 'POST',
			data: {personToLocate: pagePersonId},
			dataType: 'JSON',
			success: function(response){
				responseData = response;
			},
			complete: function(){
				if(responseData.success == false){
					openLoader(false);
					return;
				}
				fatherMessageId = responseData.content.id;
				fillChatDashboard(fatherMessageId);
				openLoader(false);
			}
		});
	}

	// looks for messages on each 30 secods
	function lookForMessages(){
	    myVar = setInterval(function(){fillChatDashboard(fatherMessageId, null, true)}, 30000);
	}

	var targetPersonId = null;
	var docOverflow = 0;
	var positionOfNotSeen = null;
	var formerNumberOfMessages = 0;
	function fillChatDashboard(fatherMessageId, scrollTo = null, doNotOpenLoader = null, forceMessagesReload = null){
		if(fatherMessageId == null){
			return;
		}
		if(doNotOpenLoader == null){
			openLoader();
		}
		positionOfNotSeen = null;
		$.ajax({
			url: '/<?= URL['urlDomain'] ?>/message/fetchdatabyid',
			type: 'POST',
			data: {fatherMessageId: fatherMessageId},
			dataType: 'JSON',
			success: function(result){
				if(result.success == false){
					openToast(result.message);
					return;
				}
				var numberOfMessages = result.numberOfMessages;
				if(forceMessagesReload == null){
					if(numberOfMessages <= formerNumberOfMessages){
						return;
					}
				}
				formerNumberOfMessages = numberOfMessages;
				// setting a marker of new notification
				if($('.my-messages').hasClass('openned-message') == false){
					$('.new-message-flag').show();
				}
				//openToast("<?php echo ucfirst(translate('you have new messages')); ?>");

				var messages = result.messages;
				targetPersonId  = messages[0]['ownerOfMessage']['id'] == myId ? messages[0]['targetPerson']['id']       : messages[0]['ownerOfMessage']['id']
				var titleOfChat = messages[0]['ownerOfMessage']['id'] == myId ? messages[0]['targetPerson']['fullName'] : messages[0]['ownerOfMessage']['fullName'];
				$('.chatMessageHeader').find('h4').html(titleOfChat);
				var html = '';
				var position = 0;
				for(i = 0; i < messages.length; i++){
					var isMine = messages[i]['ownerOfMessage']['id'] == myId ? true : false;
					var className = isMine == true ? 'align-right' : 'align-left';
					if(isMine == false && messages[i]['hasSeen'] == null && positionOfNotSeen == null){
						className += ' wasNotSeenYet';
						positionOfNotSeen = position;
					}
					html += '<div class="message-of-conversation-wrapper">';
					if(isMine == true){
						html += '<div class="removeThisMessage" style="display:none;"><i class="fas fa-times" title="<?php echo ucfirst(translate('remove')); ?>"></i></div>';
					}
					html += '<h4 class="message-of-conversation '+className+'">';
					html += messages[i]['messageText'];
					html += '<p class="hidden" style="font-size: 0.7em;">'+messages[i]['dateOfMessage']+'</p';
					html += '</h4>';
					html += '</div>';
					position++;
				}
				$('.body-of-message').html(html);
			},
			complete: function(){
				$('.message-text').val('');
				var scrollPositionOfNotSeen = $('.message-of-conversation-wrapper').height();
				scrollPositionOfNotSeen = scrollPositionOfNotSeen * positionOfNotSeen;
				if(doNotOpenLoader == null){
					openLoader(false);
				}
				var notSeenTag = $('.wasNotSeenYet').first();
				notSeenTag.addClass('highlighter');
				if(scrollPositionOfNotSeen > 0){
					$('.body-of-message').scrollTop(scrollPositionOfNotSeen);
				}else{
					$('.body-of-message').scrollTop($('.body-of-message')[0].scrollHeight);
				}
				setAsSeen();
				// subsequent event
				$(document).on("click",".message-of-conversation", function(){
					$('.message-of-conversation-wrapper').css('margin-bottom', 'unset');
					$('.message-of-conversation').parent().find('p').addClass('hidden');
					if($(this).hasClass('is-displaying-date') == false){
						$(this).parent().css('margin-bottom', '10%');
						$(this).parent().find('p').removeClass('hidden');
					}
					$(this).toggleClass('is-displaying-date');
				});

				if(scrollTo != null){
					$(document).find('.body-of-message').scrollTop(scrollTo);
				}
			}
		});
	}

</script>

<style type="text/css">
	
	header{
		margin-bottom: 0!important;
	}

	.my-data-wrapper{
		background: white;
		padding-top: 5%;
		width: 95%;
		margin: auto;
		padding-bottom: 5%;
	}

	.background-picture{
		width: 90%;
		margin: auto;
		background-repeat: round;
		border-radius: 10px;
	}
	.profile-photo{
		margin: auto;
		display: flex;
		width: 45%;
		justify-content: center;
		margin-top: 5%;
		margin-bottom: 5%;
		padding: 2%;
	}
	.profile-photo img{
		width: 400px;
		height: 400px;
		border-radius: 50%;
	}
	@media only screen and (max-width: 1200px) {
		.profile-photo img{
			width: 300px;
			height: 300px;
		}
	}
	@media only screen and (max-width: 700px) {
		.profile-photo img{
			width: 200px;
			height: 200px;
		}
	}
	@media only screen and (max-width: 500px) {
		.profile-photo img{
			width: 130px;
			height: 130px;
		}
	}
	.profile-photo a{
		text-align: center;
	}

	.profile-name{
		color: #6a5c5c;
		font-size: 1.4em;
	}

	.role-icon{
		width: 40px;
		height: 40px;
		float: right;
		margin-right: 5%;
		margin-top: -1%;
	}

	.about-profile{
		border-top: 1px solid #C4C4C4;
		padding: 5%;
		border-bottom: 1px solid #C4C4C4;
		margin: 5%;
	}

	.title-of-category{
		text-align: left;
		font-size: 1.2em;
		font-weight: normal;
	}

	.slideshow-wrapper{
		display: flex;
		flex-direction: column;
		border-radius: 5px;
		width: 90%;
		margin: auto;
	}
	.responsive-img{
		height: 500px;
		width: 700px;
		border-radius: 5px;
	}

	.message-btn{
		position: fixed;
		right: 0;
		bottom: 0;
		margin-right: 1%;
		margin-bottom: 1%;
		border-radius: 50%;
		padding: 1%;
		background: #00D084;
		border: 2px solid #00D084;
		cursor: pointer;
		color: #fff;
	}
	.message-btn:hover{
		transition: 1s;
		background: #078457;
		border: 2px solid #078457;
	}
	.message-btn > i{
		font-size: 2em;
	}
	.my-messages{
		right: 1%;
	    bottom: 0;
	    background: white;
	    width: 25%;
	    height: 50%;
	    z-index: 5;
	    border-radius: 0 5px 0 5px;
	    position: fixed;
	    box-shadow: 2px 2px 2px 2px rgb(0 0 0 / 25%);
	    display: none;
	}
	.header-of-message{
		border-bottom: 1px solid lightgrey;
    	height: 15%;
    	display: flex;
    	justify-content: space-around;
	}
	.header-of-message img{
		border-radius: 50%;
		width: 45px;
		height: 45px;
	}
	.header-of-message *{
		margin-top: auto;
		margin-bottom: auto;
	}
	.body-of-message{
		height: 70%;
		overflow-y: scroll;
	}
	.message-footer-options{
		height: 15%;
		border-top: 1px solid lightgrey;
    	display: flex;
    	width: 100%;
	}
	.add-photo-to-message{
		width: 15%;
    	text-align: center;
    	cursor: pointer;
	}
	.add-photo-to-message:hover{
		color: #d7d5d580;
	}
	.message-icon{
		cursor: pointer;
	}
	.message-icon:hover{
		color: #d7d5d580;
	}
	.message-text{
		background: #80808026;
	    width: 80%;
	    border-radius: 15px	;
	    padding: 5px;
	    border: 0.5px solid #80808045;
	}
	.message-text:hover{
		border: none!important;
	}
	.send-message-to-target{
		width: 15%;
		text-align: center;
		cursor: pointer;
	}
	.send-message-to-target:hover{
		transform: scale(1.5);
		transition: 1s;
		color: #16ad16;
	}
	.message-footer-options *{
		margin-top: auto;
		margin-bottom: auto;
	}
	.add-new-works{
		font-size:3em;
		cursor: pointer;
		margin-top: 5%;
	}
	.add-new-works:hover{
		opacity: 0.5;
		transition: 0.5s;
	}
	@media only screen and (max-width: 800px) {
		.slideshow-container{
			margin-bottom: 20%;
		}
		.responsive-img{
			height: 400px;
			width: 600px;
			border-radius: 5px;
		}
	}
	@media only screen and (max-width: 650px) {
		.slideshow-container{
			margin-bottom: 20%;
		}
		.responsive-img{
			height: 300px;
			width: 400px;
			border-radius: 5px;
		}
	}
	@media only screen and (max-width: 500px) {
		.slideshow-container{
			margin-bottom: 20%;
		}
		.responsive-img{
			height: 200px;
			width: 300px;
			border-radius: 5px;
		}
	}
	@media only screen and (max-width: 400px) {
		.slideshow-container{
			margin-top: 20%;
			margin-bottom: 20%;
		}
		.responsive-img{
			height: 150px;
			width: 250px;
			border-radius: 5px;
		}
	}
	.openned-message{
		display: block!important;
	}

	.align-left{
		float: left;
	}
	.align-right{
		float: right;
	}

	.personDataInput > textarea{
		resize:vertical;
		border-radius: 5px;
		border: 0.5px solid #80808024;
		margin-top: 5%;
		height: 300px;
	}
	.wrapper-of-inputs{
		width: 70%;
		display: flex;
		flex-direction: column;
		justify-content: center;
		margin: auto;
		margin-top: 5%;
		margin-bottom: 5%;
	}
	.description-of-work{
		border: 1px solid #c1c1c147;
		border-radius: 15px;
		text-indent: 10px;
		margin-top: 15px;
	}
	.description-of-work:hover{
		border: 1px solid #795e5e7d;
		transition: 0.5s;
	}
	.photo-add{
		width: 20%;
		font-size: 2em;
		border-radius: 20px;
		color: #fff;
		cursor: pointer;
	}
	.photo-add:hover{
		opacity: 0.8;
		transition: 0.5s;
	}
	#selected-work-photo{
		border-radius: 15px;
		margin-bottom: 10px;
		display: none;
	}
	.title-of-photo{
		margin-top: -65px;
		margin-bottom: 20px;
		background: #000000ad;
		padding: 2px;
		border-radius: 0 0 15px 15px;
	}
	.message-of-conversation-wrapper{
		width: 100%;
		height: 20%;
	}
	.message-of-conversation{
		padding: 15px;
		background: #80808024;
		border-radius: 5px;
		font-weight: normal;
		margin: auto;
		cursor: pointer;
	}
	.currently-oppened{
		opacity: 0.8;
		background: gray;
	}
	.highlighter{
		background: #80808091;
	}

	footer{
		margin-top: 5%!important;
	}

	input {border:0;outline:0;}
	input:focus {outline:none!important;}
</style>

<?php $this->insert('user-footer') ?>
