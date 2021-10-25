<?php $this->insert('user-header', ['title' => ucfirst(translate('new'))]) ?>
<link href="/<?= URL['urlDomain'] ?>/Source/Resourses/CSS/carousel.css" rel="stylesheet"></link>
<section>
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
			<div class="about-me">
				<h6 class="left-position"><?php echo ucfirst(translate('about me')); ?>:</h6>
				<p class="person-description centralized meddium-text">
					<?= $person['personDescription']; ?>
				</p>
			</div>
			<div class="my-habilities">
				<h6 class="left-position"><?php echo ucfirst(translate('my skills')); ?>:</h6>
				<p class="person-habilities centralized meddium-texts">
					<?= $person['personHabilities']; ?>
				</p>
			</div>
		</div>
	</div>
	<div class="slideshow-wrapper">
		<div class="slideshow-container"></div>
		<div class="dots-position"></div>
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
						html += '<img src="'+works[i]['webPath']+'" style="width:100%">';
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

</script>

<?php $this->insert('user-footer') ?>
