<?php $this->insert('user-header', ['title' => ucfirst(translate('homepage'))]) ?>

<section class="top-user-announcement">
	<div class="top-user-announcement-wrapper">
		<div class="title-wrapper">
			<h2><?= ucfirst(translate('top professional')) ?></h2>
		</div>
		<div class="top-user-card-wrapper">
			<div class="top-user-card" title="<?php echo ucfirst(translate('see my profile')) ?>">
				<a href="#" class="user-personal-page">
					<img src="Source/Resourses/External/icons/browser.svg">
				</a>
				<img src="http://localhost/traposeretalhos/Source/Files/img/users/585c87f174214afac6a1f6cbf380ade5-1637055196.jpeg" class="top-user-photo">
				<h3 class="user-name">Victoria</h3>
				<div class="top-user-rate">
					<img src="Source/Resourses/External/icons/rate-star.svg" class="rate-star">
					<img src="Source/Resourses/External/icons/rate-star.svg" class="rate-star">
					<img src="Source/Resourses/External/icons/rate-star.svg" class="rate-star">
				</div>
				<h6 class="user-description">
					Eu sou a Victoria, trabalho como costureira a 45 anos e meio, sei fazer de tudo!!
				</h6>
			</div>
		</div>
	</div>
</section>

<section class="current-professionals-data">
	<h4><?php echo ucfirst(translate('be welcome, what do you seek?')) ?></h4>
	<div class="professionals-data">
		<?php $rolesData = json_decode(file_get_contents(TMPPATH['files'].'roles.txt'), true); ?>
		<?php foreach($rolesData as $role){ ?>
			<div class="professional-card <?= $role['roleName'] ?>">
			<span class="professionals-number"></span>
			<img src="Source/Resourses/External/icons/<?= $role['iconUrl'] ?>">
			<h3><?php echo ucfirst(translate($role['roleName'])) ?></h3>
			<h6><?php echo ucfirst(translate($role['description'])) ?></h6>
			</div>
		<?php } ?>
	</div>
</section>

<?php $this->insert('user-footer') ?>

<script type="text/javascript">

	$(document).ready(function(){
	    feedProfessionalsNumber();
	});

  function feedProfessionalsNumber(){
  	  $.ajax({
		     	url: 'personrole/getrolesdata',
		  	  type: 'POST',
		  	  dataType: 'JSON',
		  	  success: function(response){
		  	  	  if(response.success == true){
		  	  	  	  var data = response.content;
		  	  	  	  for(var roleName in data){
		  	  	  	  	  $('.'+roleName).find('.professionals-number').text(data[roleName]);
		  	  	  	  }
		  	  	  }
		  	  }
		  });
  }

	function encodeImgtoBase64(element) {
      var img = element.files[0];
      var reader = new FileReader();
      reader.onloadend = function() {
        $("#convertImg").attr("href",reader.result);
        $("#convertImg").text(reader.result);
        $("#displayImg").attr("src", reader.result);
      }
      reader.readAsDataURL(img);
    }

</script>

<style type="text/css">
	.top-user-announcement-wrapper{
		width: 90%;
		margin: auto;
		padding: 3%;
	}
	.title-wrapper{
		padding: 1%;
		background: rgba(0, 0, 0, 0.67);
		border-radius: 5px;
		margin: 2%;
	}
	.title-wrapper > h2{
		color: #ffffff;
		margin: unset;
	}
	.top-user-card-wrapper{
		display: flex;
		overflow-x: scroll;
		width: 90%;
		margin: auto;
	}
	.top-user-card{
		padding: 2%;
		background: #ffffff;
		border-radius: 5px;
		text-align: center;
		min-width: 200px;
		margin: 0 1% 0 1%;
		margin: auto;
		width: 250px;
	}
	.user-personal-page{
		display: flex;
	}
	.user-personal-page > img{
		height: 35px;
		width: 35px;
	}
	.top-user-photo{
		height: 100px;
		width: 100px;
		border-radius: 100px;
		margin-top: -20px;
	}
	.top-user-rate{
		margin-bottom: -20px;
	}
	.rate-star{
		height: 15px;
		width: 15px;
	}
	.user-description{
		font-size: 18px;
		margin-bottom: 5%;
	}
	/* Professionals homepage number data and description */
	.current-professionals-data{
		width: 90%;
		margin: auto;
	}
	.current-professionals-data > h4{
		text-align: center;
		font-size: 2.5em;
	}
	.professionals-data{
		display: flex;
		flex-wrap: wrap;
		justify-content: space-around;
		width: 80%;
		margin: auto;
	}
	.professional-card{
		display: flex;
		flex-direction: column;
		padding: 2%;
		background: #ffffff;
		box-shadow: 0 2px rgba(0, 0, 0, 0.25);
		width: 300px;
		height: 300px;
		margin: 2%;
		cursor: pointer;
	}
	.professional-card:hover{
		transition: 0.2s;
		transform: scale(1.1);
	}
	.professionals-number{
		text-align: right;
		color: rgba(218, 221, 90, 0.8);
		text-decoration: underline;
		font-size: 40px;
	}
	.professional-card > img{
		width: 100px;
		height: 100px;
		margin-top: -10%;
	}
	.professional-card > h3{
		font-size: 25px;
		margin-bottom: -5px;
	}
	.professional-card > h6{
		font-size: 20px;
	}

	  /* cards options */
	  @media only screen and (max-width: 600px){
		  	.professional-card{
		  		  padding: 5%;
		  		  margin-bottom: 15%;
		  	}
	  	  .professionals-data h6{
	  	  	  font-size: 15px;
	  	  }
	  }
</style>