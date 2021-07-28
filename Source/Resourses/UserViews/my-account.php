<?php $this->insert('user-header', ['title' => $title]) ?>

<h1 class="text-title page-centralized-title">My account details:</h1>

<section class="my-data">
	<div class="user-data-icons-wrapper">
		<img id="edit-account" class="user-data-icons" title="<?php echo ucfirst(translate('edit your account')) ?>" src="Source/Resourses/External/icons/edit.svg">
		<img id="show-preview" class="user-data-icons" title="<?php echo ucfirst(translate('show your account preview')) ?>" src="Source/Resourses/External/icons/show-preview.svg">
	</div>
	<div class="my-photo" title="<?php echo ucfirst(translate('my profile photo')) ?>">
		<a href="<?= $content['profilePhoto'] ?>" target="_blank">
		    <img src="<?= $content['profilePhoto'] ?>"/>
		</a>
	</div>
	<div class="main-data">
		<h2 class="name"><?= $content['name']. ' '. $content['lastName']; ?></h2>
		<div class="user-main-data">	
			<div class="flex-row">
				<label><?php echo ucfirst(translate('gender')) ?>:</label>
				<span class="sex"><img style="cursor: unset;" src="Source/Resourses/External/icons/<?= $content['sexIcon']; ?>"></span>
			</div>
			<div class="flex-row">
				<label><?php echo ucfirst(translate('date of birth')) ?>:</label>
				<span class="dateOfBirth"><?= $content['dateOfBirth']; ?></span>
			</div>
			<div class="flex-row">
				<label><?php echo ucfirst(translate('email')) ?>:</label>
				<span class="email"><?= $content['email']; ?></span>
			</div>
			<div class="flex-row">
				<label><?php echo ucfirst(translate('address')) ?>:</label>
				<span class="address"><?= $content['address']; ?></span>
			</div>
			<div class="flex-row">
				<label><?php echo ucfirst(translate('address number')) ?>:</label>
				<span class="addressNumber"><?= $content['addressNumber']; ?></span>
			</div>
			<div class="flex-row">
				<label><?php echo ucfirst(translate('city')) ?>:</label>
				<span class="city"><?= $content['cityName']; ?></span>
			</div>
			<div class="flex-row">
				<label><?php echo ucfirst(translate('CEP')) ?>:</label>
				<span class="cep"><?= $content['cep']; ?></span>
			</div>
		</div>
	</div>
	<div class="user-description">
		<div class="user-self-description">
			<h4 class="text-title"><?php echo ucfirst(translate('about me')); ?>:</h4>
			<p class="personDescription"><?= $content['personDescription']; ?></p>
		</div>
		<div class="user-habilities">
			<h4 class="text-title"><?php echo ucfirst(translate('my habilities')); ?>:</h4>
			<p class="personHabilities"><?= $content['personHabilities']; ?></p>
		</div>
	</div>

	<div class="map-location">
		<h4 class="text-title"><?php echo ucfirst(translate('my default location')); ?>:</h4>
		<div id="real-time-map" class="map-wrapper"></div>
	</div>
</section>
<!-- the modal -->
<div id="modal1" class="modal">
	<div class="modal-content">
		<div class="modal-header">
        	<span class="close">&times;</span>
			<h2 class="modal-title center-text"><?php echo ucfirst(translate('edit your account')); ?></h2>
		</div>
        <div class="modal-data"></div>
        <div class="modal-options">
        	<a class="cancel-modal"><?php echo ucfirst(translate('cancel')); ?></a>
        	<a class="confirm-modal"><?php echo ucfirst(translate('save')); ?></a>
        </div>
    </div>
</div>

<script src="Source/Resourses/JS-functions/modal.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		gatherCitiesForSelect();
	});
	$('#edit-account').on('click', function(){
		feedEditMyAccountModal();
		openModal();
	});

	$('#show-preview').on('click', function(){

	});

	function feedEditMyAccountModal(){
		var elementOfModal = ''
		<?php
			$dataToEdit = ['sex', 'dateOfBrith', 'address', 'addressNumber', 'city', 'cep'];
		    foreach($content as $dataKey => $personData){ 
		    	if(!in_array($dataKey, $dataToEdit))
		    		continue;
		?>
			var keyName	   = "<?= $dataKey; ?>";
			var labelValue = "<?php echo ucfirst(translate($dataKey)); ?>";
			var personData = "<?= $personData ?>";
			elementOfModal += '<div class="row">';
			if(keyName == 'sex'){
				elementOfModal  = '<select class="edit-sex">'
				elementOfModal += '<option>M</option>';
				elementOfModal += '<option>F</option>';
				elementOfModal += '<option>U</option>';
				elementOfModal += '</select>';
			}else if(keyName == 'city'){
				elementOfModal  = '<select class="edit-city"></select>';
			}else{
				elementOfModal += '<label for="edit-'+dataKey+'">'+dataKey+'</label>';
				elementOfModal += '<input id="edit-'+dataKey+'" value="'+personData+'">';
			}
			elementOfModal += '</div>';
		<?php  } ?>
		$('.modal-data').append(elementOfModal);
		var modalDescription = '<div class="modal-data-description"></div>';
		var modalHabilities  = '<div class="modal-data-habilities"></div> ';
		$('.modal-data').append(modalDescription);
		$('.modal-data').append(modalHabilities);
	}

	var citiesArray = [];
	function gatherCitiesForSelect(){
		$.ajax({
		  	url: 'city/getcitiesofcountry',
		  	dataType: 'JSON',
		  	success: function(result){
		    	citiesArray = result;
		    }
		});
	}

	// since the method is a file, it can not have PHP variables, do something about it by sending via parameters everey thing necessary for the methods to work
	feedMap();

	// function which populates map accordinally to Cookies or asks for the location, and update Cookie
	// * param <string> the name of the element which shall hold the map, a div
	function feedMap(elementName = 'real-time-map', latitudeCookie = null, longitudeCookie = null){
		var latitudeCookie  = "<?= isset($_COOKIE['latitude'])  ? $_COOKIE['latitude']  : '' ?>";
		var longitudeCookie = "<?= isset($_COOKIE['longitude']) ? $_COOKIE['longitude'] : '' ?>";
		if(latitudeCookie !== '' && longitudeCookie !== ''){
			// use the location from cookies
			populateMap(latitudeCookie, longitudeCookie);
		}else{
			// get new location and ask for the sharing for user
			getLocation();
		}
	};

	// asks for user location and populates the map with it
	function getLocation(errorMessagesId = ''){
		var x = document.getElementById("errorMessagesId");
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(showPosition, showError);
	    } else { 
	        x.innerHTML = "Geolocation is not supported by this browser.";
	    }
	}
	// responsible by populating the map
	function showPosition(position) {
		var latitude  = position.coords.latitude;
		var longitude = position.coords.longitude;
		populateMap(latitude, longitude);
	}
	// displays erros of the user and tries to populate map with more unacurretad coordinates
	function showError(error) {
	  switch(error.code) {
	    case error.PERMISSION_DENIED:
	        populateMap(<?= $_SESSION['userLatitude'] ?>, <?= $_SESSION['userLongitude'] ?>);
	        x.innerHTML = "User denied the request for Geolocation."
	        break;
	    case error.POSITION_UNAVAILABLE:
	        populateMap(<?= $_SESSION['userLatitude'] ?>, <?= $_SESSION['userLongitude'] ?>);
	        x.innerHTML = "Location information is unavailable."
	        break;
	    case error.TIMEOUT:
	        populateMap(<?= $_SESSION['userLatitude'] ?>, <?= $_SESSION['userLongitude'] ?>);
	        x.innerHTML = "The request to get user location timed out."
	        break;
	    case error.UNKNOWN_ERROR:
	    	populateMap(<?= $_SESSION['userLatitude'] ?>, <?= $_SESSION['userLongitude'] ?>);
	        x.innerHTML = "An unknown error occurred."
	        break;
	   }
	}
	// populate the map with provided data
	// will request creation of cookie with location in case it does not exist yet
	function populateMap(latitude, longitude, elementId = 'real-time-map'){
		var userLanguage = <?php echo json_encode($_SESSION['userLanguage']); ?>;
		userLanguage = userLanguage.toLowerCase();
		if(userLanguage == 'ptbr')
			userLanguage = 'pt-BR';
		var mapElement = '<iframe src="https://embed.waze.com/'+userLanguage+'/';
		mapElement += 'iframe?zoom=16&lat='+latitude;
		mapElement += '&lon='+longitude;
		mapElement += '&pin=1&ct=livemap" class="map" allowfullscreen></iframe>';
		$('#real-time-map').append(mapElement);
		// call the cookies to be set only if necessary
		var latitudeCookie  = "<?= isset($_COOKIE['latitude'])  ? $_COOKIE['latitude']  : '' ?>";
		var longitudeCookie = "<?= isset($_COOKIE['longitude']) ? $_COOKIE['longitude'] : '' ?>";
		if(latitudeCookie === '' && longitudeCookie === ''){
			$.ajax({
			  	url: 'updatecookies/usergeolocation',
			  	type: 'POST',
			  	data: {latitude: latitude, longitude: longitude},
			  	success: function(result){
			    	console.log(result);
			    }
			});
		}
	}
</script>

 <style type="text/css">
	/* User data Icons*/
	.user-data-icons-wrapper{
		position: absolute;
		right: 20%;
	}
	.user-data-icons{
		width: 30px;
		height: 30px;
		margin-right: 10px;
	}
	/* user data related CSS */
	.my-data{
		background: #ffffff;
		width: 60%;
		margin: auto;
		padding: 2%;
		border-radius: 5%;
	}
	.my-photo{
		text-align: center;
	}
	.my-photo > a > img{
		width: 50%;
		border-radius: 50%;
	}
	.main-data{
		margin-bottom: 10%;
	}
	.main-data > h2{
		text-align: center;
		margin: 5% 0 5% 0;
	}
	.user-main-data{
		width: 70%;
		margin: auto;
	}
	.flex-row{
		display: flex;
		justify-content: space-between;
		margin: 5%;
	}
	.flex-row > label{
		text-decoration: underline;
	}
	.user-description{
		margin: 5%;
	}
	.user-description div{
		margin: 5%;
		margin-bottom: 20%;
	}
	.user-description p{
		margin-top: 5%;
	}
	.sex > img{
		width: 25px;
	}

	.page-centralized-title{
		width: 90%;
		margin: auto;
		margin-bottom: 5%;
	}

	/* Map related CSS */
	.map-wrapper{
		display: flex;
		justify-content: center;
		margin: auto;
		height: 400px;
		width: 90%;
		padding: 5%;
	}
	@media (max-width: 600px)
	{
	    .map-wrapper{
	        height: 700px;
	    }
	}

	.map{
		width: 100%;
		height: 100%;
	}
</style>