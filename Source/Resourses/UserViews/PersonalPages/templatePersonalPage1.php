<?php $this->insert('user-header', ['title' => ucfirst(translate('new'))]) ?>
<link href="/<?= URL['urlDomain'] ?>/Source/Resourses/CSS/carousel.css" rel="stylesheet"></link>
<section class="my-data-wrapper">
	<div class="main-data-wrapper">
		<div class="background-picture" style="background-image: url(<?= $person['role']['backgroundPhoto']['webPath'] ?>);">
			<div class="profile-photo">
				<img src="<?= $person['profilePhoto'] ?>">
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
					<?= $person['personDescription']; ?>
				</p>
			</div>
			<div class="my-habilities gray-border">
				<h6 class="left-position title-of-category"><?php echo ucfirst(translate('my skills')); ?>:</h6>
				<p class="person-habilities meddium-texts">
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

	<div class="message-btn" title="<?php echo ucfirst(translate('send a message')); ?>">
		<i class="far fa-comment-alt"></i>
	</div>
</section>
<script src="/<?= URL['urlDomain'] ?>/Source/Resourses/JS-functions/slidshow-function.js"></script>
<script type="text/javascript">
	
	$( document ).ready(function() {
	    getWorks();
	});

	// gets this personal page works in order to feed carousel of works
	function getWorks(){
		$.ajax({
			url: '/<?= URL['urlDomain'] ?>/personalpage/getmyworks',
			data: {personalPageId: <?= $person['role']['personalPageId'] ?>},
			type: 'POST',
			dataType: 'JSON',
			success: function(result){
				if(result['seccess'] == false){
					return;
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
						html += '<img src="'+works[i]['webPath']+'" style="width:100%" class="responsive-img">';
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
		    		console.log('nothing');
		    	}
			},
			complete: function(){
				showSlides(slideIndex);
			}
		});
	}

	$('.message-btn').on('click', function(){
		alert('will start chat');
	});

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
	.profile-photo > img{
		width: 50%;
		border-radius: 50%;
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
		max-height: 500px;
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

	footer{
		margin-top: 5%!important;
	}
</style>

<?php $this->insert('user-footer') ?>
