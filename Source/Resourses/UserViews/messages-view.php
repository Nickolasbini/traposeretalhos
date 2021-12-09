<?php $this->insert('user-header', ['title' => ucfirst(translate('my messages'))]) ?>

<section class="messages-area">
	<div class="post-title">
		<?php echo ucfirst(translate('my messages')) ?>
	</div>

	<div class="messages-wrapper">
		<div class="slide-conversations">
			<i class="fas fa-bars"></i>
		</div>
		<div class="conversations closed-slider-of-messages">
			<?php foreach($fatherMessages as $message){ ?>
				<?php $srcfOfMessageIcon = $_SESSION['personId'] == $message['ownerOfMessage']['id'] ? $message['targetPerson']['profilePhoto'] : $message['ownerOfMessage']['profilePhoto']; ?>
				<div class="option-of-message" dataId="<?= $message['id']; ?>">
					<img width="50px" height="50px" src="<?php echo $srcfOfMessageIcon? $srcfOfMessageIcon : '/'.URL['urlDomain'].'/Source/Resourses/External/icons/account.svg' ?>">					<div class="message-previews">
						<span><?= $message['ownerOfMessage']['fullName'] ?></span>
						<span><?php echo count($message['child']) > 0 ? $message['messageText']   : $message['messageText']; ?></span>
					</div>
					<div class="datePreview"><?php echo count($message['child']) > 0 ? $message['dateOfMessage'] : $message['dateOfMessage']; ?></div>
				</div>
			<?php } ?>
		</div>
		<div id="messages-box" class="messagesOfConversation">
			<div class="chatMessageHeader">
				<h4></h4>
			</div>
			<div class="chatMessageContent"></div>
			<div class="chatMessageFooter">
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
	</div>

</section>


<?php $this->insert('user-footer') ?>

<script type="text/javascript">
	var myId = "<?= $_SESSION['personId'] ?>";
	var selectedMessage = null;
	$( document ).ready(function() {
		if($('.option-of-message')){
			$('.option-of-message').first().addClass('currently-oppened');
			selectedMessage = $('.option-of-message').first().attr('dataid');
			fillChatDashboard();
		}
	    //getMessagesList();
	});

	$('.option-of-message').on('click', function(){
		var messageId = $(this).attr('dataid');
		if(messageId == selectedMessage){
			return;
		}
		selectedMessage = messageId;
		$('.option-of-message').removeClass('currently-oppened');
		$(this).addClass('currently-oppened');
		fillChatDashboard();
	})

	// looks for messages on each 30 secods
	function lookForMessages(){
	    myVar = setInterval(fillChatDashboard, 30000);
	}

	var targetPersonId = null;
	var docOverflow = 0;
	var positionOfNotSeen = null;
	var formerNumberOfMessages = 0;
	function fillChatDashboard(){
		openLoader();
		positionOfNotSeen = null;
		$.ajax({
			url: '/<?= URL['urlDomain'] ?>/message/fetchdatabyid',
			type: 'POST',
			data: {fatherMessageId: selectedMessage},
			dataType: 'JSON',
			success: function(result){
				if(result.success == false){
					openToast(response.message);
					return;
				}
				var numberOfMessages = result.numberOfMessages;
				if(numberOfMessages <= formerNumberOfMessages){
					return;
				}
				var messages = result.messages;
				targetPersonId  = messages[0]['ownerOfMessage']['id'] == myId ? messages[0]['targetPerson']['id']       : messages[0]['ownerOfMessage']['id']
				var titleOfChat = messages[0]['ownerOfMessage']['id'] == myId ? messages[0]['targetPerson']['fullName'] : messages[0]['ownerOfMessage']['fullName'];
				$('.chatMessageHeader').find('h4').html(titleOfChat);
				var html = '';
				var position = 0;
				for(i = 0; i < messages.length; i++){
					var isMine = messages[i]['ownerOfMessage']['id'] == myId ? true : false;
					var className = isMine == true ? 'align-right' : 'align-left';
					if(isMine == false && messages[i]['hasSeen'] == null){
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
				$('.chatMessageContent').html(html);
			},
			complete: function(){
				$('.message-text').val('');
				openLoader(false);
				var scrollPositionOfNotSeen = $('.message-of-conversation-wrapper').height();
				scrollPositionOfNotSeen = scrollPositionOfNotSeen * positionOfNotSeen;
				var notSeenTag = $('.wasNotSeenYet').first();
				notSeenTag.addClass('highlighter');
				if(scrollPositionOfNotSeen > 0){
					$('.chatMessageContent').scrollTop(scrollPositionOfNotSeen);
					setAsSeen();
				}else{
					$('.chatMessageContent').scrollTop($('.chatMessageContent')[0].scrollHeight);
				}
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

				if($('.conversations').hasClass('open-slider') == true){
					$('.conversations').removeClass('open-slider');
				}
			}
		});
	}

	function setAsSeen(){
		$.ajax({
			url: '/<?= URL['urlDomain'] ?>/message/setasseen',
			type: 'POST',
			data: {fatherMessageId: selectedMessage},
			dataType: 'JSON',
			complete: function(){
				$('.highlighter').removeClass('highlighter');
			}
		});
	}

	$('.send-message-to-target').on('click', function(){
		var messageText = $('.message-text').val();
		if(messageText == ''){
			return;
		}
		sendMessage(messageText);
	});
	$(document).on('keypress',function(e) {
	    if(e.which == 13) {
	    	var messageText = $('.message-text').val();
			if(messageText == ''){
				return;
			}
	        sendMessage(messageText);
	    }
	});

	$(document).on('mouseover', '.message-of-conversation', function(){
		//$(this).parent().find('.removeThisMessage').show();
	});

	var hasPhoto = false;
	var messagesGathered = null;
	function sendMessage(messageText){
		docOverflow = $(document).scrollTop();
		openLoader();
		$.ajax({
			url: '/<?= URL['urlDomain'] ?>/message/sendmessage',
			type: 'POST',
			data: {targetPersonId: targetPersonId, messageText: messageText, hasPhoto: hasPhoto},
			dataType: 'JSON',
			success: function(result){
				if(result.success == false){
					openToast(result.message);
					return
				}
				messagesGathered = result.message;
				$('.message-text').val('');
			},
			complete: function(){
				$('.chatMessageContent').scrollTop($('.chatMessageContent').height());
				openLoader(false);
				openToast(messagesGathered);
				fillChatDashboard();
			}
		});
	}

	$('.slide-conversations').on('click',function(){
		$('.conversations ').toggleClass('open-slider');
	});
</script>

<style type="text/css">
	.post-title{
		padding: 2%;
		width: 90%;
		background: rgba(0, 0, 0, 0.67);
		margin: auto;
		border-radius: 5px;
		color: #fff;
		text-align: center;
		font-size: 25px;
		margin-bottom: 5%;
	}
	.messages-wrapper{
		display: flex;
		width: 80%;
		margin: auto;
		border: 1px solid gray;
		height: 500px;
	}
	.conversations{
		display: flex;
		flex-direction: column;
		width: 40%;
		border: 1px solid gray;
	}
	.option-of-message{
		cursor: pointer;
		padding: 5%;
		display: flex;
		justify-content: space-between;
		border: 0.5px solid #9b9b9b45;
	}
	.option-of-message:hover{
		background: #fff;
	}
	.option-of-message > img{
		border-radius: 50px;
		margin-right: 5%;
	}
	.datePreview{
		color: #797575;
	}
	.message-previews{
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		margin-left: -10%;
	}
	.messagesOfConversation{
		border-left: 5px solid #cecece;
		width: 60%;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
	}
	.message-footer-options *{
		margin-top: auto;
		margin-bottom: auto;
	}
	.chatMessageHeader{
		height: 15%;
		border-bottom: 1px solid gray;
		text-align: center;
	}
	.chatMessageContent	{
		height: 80%;
		padding: 1%;
		overflow-y: scroll;
	}
	.chatMessageFooter{
		display: flex;
		padding: 3%;
		justify-content: space-around;
		border-top: 1px solid #9d9595;
		height: 5%;
	}
	.message-text{
		background: #80808026;
	    width: 60%;
	    border-radius: 15px;
	    padding: 5px;
	    border: 0.5px solid #80808045;
	}
	.message-text:hover{
		opacity: 0.8;
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
	.send-message-to-target{
		width: 15%;
		text-align: center;
		cursor: pointer;
		margin-top: auto;
		margin-bottom: auto;
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
	.add-photo-to-message{
		display: none;
		width: 15%;
    	text-align: center;
    	cursor: pointer;
	}
	.add-photo-to-message:hover{
		color: #d7d5d580;
	}
	input {border:0;outline:0;}
	input:focus {outline:none!important;}

	.align-left{
		float: left;
	}
	.align-right{
		float: right;
	}
	.currently-oppened{
		opacity: 0.8;
		background: #8a8888ba;
	}
	.removeThisMessage{
		float: right;
		margin-left: -15px;
		cursor: pointer;
	}
	.highlighter{
		background: #80808091;
	}
	.slide-conversations{
		display: none;
	}
	@media only screen and (max-width: 600px) {
		.closed-slider-of-messages{
			background: gray;
			width: 2%;
			overflow-x: hidden;
		}

		.openMessages{
			width: 98%;
		}
		.openend-slider-of-message{
			z-index: 10000;
		}
		.messagesOfConversation{
			width: 100%;
		}
		.slide-conversations{
			display: block;
			position: absolute;
			background: #fff;
			z-index: 1000;
			padding: 5px;
			border-radius: 0 0 5px 0;
			cursor: pointer;
		}
		.open-slider{
			position: absolute;
			width: auto;
			z-index: 1;
			background: #fff;
		}
		.datePreview{
			width: 30%;
		}
	}
</style>