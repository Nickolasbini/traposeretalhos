<?php $this->insert('user-header', ['title' => $title]) ?>

<h1 class="text-title page-centralized-title">My account details:</h1>

<section class="my-data">
	<div class="user-data-icons-wrapper">
		<img id="edit-account" class="user-data-icons" title="<?php echo ucfirst(translate('edit your account')) ?>" src="Source/Resourses/External/icons/edit.svg">
	</div>
	<div class="my-photo" title="<?php echo ucfirst(translate('my profile photo')) ?>">
		<a href="<?php echo $content['profilePhoto'] != '' ? $content['profilePhoto'] : '/'.URL['urlDomain'].'/Source/Resourses/External/icons/account.svg'; ?>" target="_blank">
		    <img src="<?php echo $content['profilePhoto'] != '' ? $content['profilePhoto'] : '/'.URL['urlDomain'].'/Source/Resourses/External/icons/account.svg' ; ?>"/>
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
			<p class="personDescription">
				<?php
				    if(is_null($content['personDescription'])){
				    	echo '<div class="no-person-description no-data-add-button" field-data-name="personDescription" title="'.ucfirst(translate('add data')).'"><i class="fas fa-plus-circle"></i></div>';
				    }else{
				    	echo $content['personDescription'];
				    }
				?>
			</p>
		</div>
		<div class="user-habilities">
			<h4 class="text-title"><?php echo ucfirst(translate('my abilities')); ?>:</h4>
			<p class="personHabilities">
				<?php
				    if(is_null($content['personHabilities'])){
				    	echo '<div class="no-person-abilities no-data-add-button" field-data-name="personHabilities" title="'.ucfirst(translate('add data')).'"><i class="fas fa-plus-circle"></i></div>';
				    }else{
				    	echo $content['personHabilities'];
				    }
				?>
			</p>
		</div>
	</div>

	<div class="map-location">
		<h4 class="text-title"><?php echo ucfirst(translate('my default location')); ?>:</h4>
		<!-- <div id="real-time-map" class="map-wrapper"></div> -->
		<img id="static-map" src="Source/Resourses/External/icons/map-placeholder.webp"/>
		<?php if(!isset($_SESSION['usedLocationIQAPI'])){ ?>
			<div class="reLocateMe" title="<?php echo ucfirst(translate('get my location')); ?>">
				<img src="<?= URL['iconsPath']; ?>location-marker.svg"><a><?php echo ucfirst(translate('find me')); ?></a>
			</div>
		<?php } ?>
		<div class="chooseFoundLocations" style="display: none;"><?php echo ucfirst(translate('see other locations')); ?></div>
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

<div id="modal2" class="modal">
	<div class="modal-content optionForLocation-modalcontent">
		<div class="modal-header optionForLocation-modalheader">
        	<span class="close optionForLocation-close">&times;</span>
			<h2 class="modal-title center-text optionForLocation-modaltitle"><?php echo ucfirst(translate('possible location to be chosen')); ?></h2>
		</div>
        <div class="modal-data optionForLocation-modaldata"></div>
        <div class="modal-options">
        	<a class="cancel-modal"><?php echo ucfirst(translate('cancel')); ?></a>
        	<a class="confirm-modal"><?php echo ucfirst(translate('save')); ?></a>
        </div>
    </div>
</div>

<div id="modal3" class="modal">
	<div class="modal-content optionForLocation-modalcontent">
		<div class="modal-header optionForLocation-modalheader">
        	<span class="close optionForLocation-close">&times;</span>
			<h2 class="modal-title center-text optionForLocation-modaltitle"><?php echo ucfirst(translate('add data')); ?></h2>
		</div>
        <div class="modal-data content-to-be-altered"></div>
        <div class="modal-options">
        	<a class="cancel-modal comments-button"><?php echo ucfirst(translate('cancel')); ?></a>
        	<a class="confirm-modal comments-button edit-data-simple-modal"><?php echo ucfirst(translate('save')); ?></a>
        </div>
    </div>
</div>

<div id="modal4" class="modal">
	<div class="modal-content to-edit optionForLocation-modalcontent">
		<div class="modal-header optionForLocation-modalheader">
        	<span class="close optionForLocation-close">&times;</span>
			<h2 class="modal-title center-text optionForLocation-modaltitle"><?php echo ucfirst(translate('edit account')); ?></h2>
		</div>
        <div class="modal-data contents-of-edit-account"></div>
        <div class="modal-options">
        	<a class="cancel-modal comments-button"><?php echo ucfirst(translate('cancel')); ?></a>
        	<a id="save-edition" class="confirm-modal comments-button"><?php echo ucfirst(translate('save')); ?></a>
        </div>
    </div>
</div>

<script src="Source/Resourses/JS-functions/modal.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		gatherCitiesForSelect();
		var latitudeCookie  = "<?= isset($_COOKIE['latitude'])  ? $_COOKIE['latitude']  : '' ?>";
		var longitudeCookie = "<?= isset($_COOKIE['longitude']) ? $_COOKIE['longitude'] : '' ?>";
		if(latitudeCookie !== '' && longitudeCookie !== ''){
			// use the location from cookies
			callStaticMap(latitudeCookie, longitudeCookie);
		}else{
			// get new location and ask for the sharing for user
			getLocation();
		}

	});

	$('.reLocateMe').on('click', function(){
		getLocationByAPI();
	});

	var possibleLocations = null;
	function getLocationByAPI(){
		var fullAddress = "<?= $content['fullAddress']; ?>";
		$.ajax({
		  	url: 'apicontroller/getdatafromlocationiq',
		  	type: 'POST',
		  	data: {addressToSearch: fullAddress},
		  	dataType: 'JSON',
		  	success: function(result){
		    	if(result.success == false){
		    		openToast(result.message);
		    		return;
		    	}
		    	possibleLocations = result.content;
		    	var location = possibleLocations[1];
		    	callStaticMap(location.latitude, location.longitude);
		    	openToast("<?php echo ucfirst(translate('default location changed')); ?>");
		    	if(result.numberOfLocations > 1){
		    		populateOtherLocations(possibleLocations);
		    	}
		    },
		    complete: function(){
		    	$('.reLocateMe').hide();
		    	$('.chooseFoundLocations').show();
		    },
		});
	}

	// feed modal with possible map possibilities
	function populateOtherLocations(possibleLocations){
		var html = '';
		for(i = 0; i < possibleLocations.length; i++){
			var latitude = possibleLocations[i].latitude;
			var longitude = possibleLocations[i].longitude;
		    var src = 'https://maps.googleapis.com/maps/api/staticmap?center='+latitude+','+longitude+'&zoom=16&size=600x300&maptype=roadmap&markers=color:blue|label:S|'+latitude+','+longitude+'&key=AIzaSyChHsH5OFnAtmXBqldHQDqQAKLYUX-hmhw';
			html += '<img class="optionForLocation" data-latitude="'+latitude+'" data-longitude="'+longitude+'" style="margin:2%" title="<?= ucfirst(translate('click here to choose location')); ?>" src="'+src+'"/>';
		}
		$('#modal2').find('.modal-data').html(html);
		openModal('modal2');
	}

	// responsible by choosing new location
	$(document).off("click", ".optionForLocation");
	$(document).on("click", ".optionForLocation", function() {
		var latitude  = $(this).attr('data-latitude');
		var longitude = $(this).attr('data-longitude');
		callStaticMap(latitude, longitude);
		closeModal();
	});

	// calls static map accordingly to latitude and longitude and update cookies
	function callStaticMap(latitude, longitude){
		var src = 'https://maps.googleapis.com/maps/api/staticmap?center='+latitude+','+longitude+'&zoom=16&size=600x300&maptype=roadmap&markers=color:blue|label:S|'+latitude+','+longitude+'&key=AIzaSyChHsH5OFnAtmXBqldHQDqQAKLYUX-hmhw';
		$('#static-map').attr('src', src);
		$.ajax({
		  	url: 'updatecookies/usergeolocation',
		  	type: 'POST',
		  	data: {latitude: latitude, longitude: longitude}
		});
	}

	$('#edit-account').on('click', function(){
		var html = '';
		html += '<div class="edit-field"><label><?php echo ucfirst(translate('gender')); ?></label><select class="edit-gender full-width"><option value="M">M</option><option value="F">F</option><option value="U">U</option></select></div>';
		html += '<div class="edit-field"><label><?php echo ucfirst(translate('date of birth')); ?></label><input class="edit-dateOfBirth full-width" type="date" value="<?= $content['dateOfBirth'] ?>"></div>';
		html += '<div class="edit-field"><label><?php echo ucfirst(translate('email')); ?></label><input class="edit-email full-width" type="text" value="<?= $content['email'] ?>"></div>';
		html += '<div class="edit-field"><label><?php echo ucfirst(translate('name')); ?></label><input class="edit-name full-width" type="text" value="<?= $content['fullName'] ?>"></div>';
		html += '<div class="edit-field"><label><?php echo ucfirst(translate('about me')); ?></label><input class="edit-personDescription full-width" type="text" value="<?= $content['personDescription'] ?>"></div>';
		html += '<div class="edit-field"><label><?php echo ucfirst(translate('my abilities')); ?></label><input class="edit-personHabilities full-width" type="text" value="<?php echo $content['personHabilities'] ?>"></div>';
		$('.contents-of-edit-account').html(html);
		$(document).find('.edit-gender option[value="<?= $content['sex'] ?>"]').attr('selected', 'selected');
		var dateOfBirth = "<?php echo $content['not-formatted-date']; ?>";
		date = dateOfBirth.split('-');
		var correctDate = date[0] +"-"+ date[1] +"-"+ date[2];
		$(document).find('.edit-dateOfBirth').val(correctDate);
		var description = $('.personDescription').text();
		var abilities   = $('.personHabilities').text();
		$(document).find('.edit-personDescription').val(description.trim());
		$(document).find('.edit-personHabilities').val(abilities.trim());
		openModal('modal4');
	});

	$('#save-edition').on('click', function(){
		var gender = $(document).find('.edit-gender').val();
		var dateOfBirth = $(document).find('.edit-dateOfBirth').val();
		var email = $(document).find('.edit-email').val();
		var name = $(document).find('.edit-name').val();
		var personDescription = $(document).find('.edit-personDescription').val();
		var personHabilities = $(document).find('.edit-personHabilities').val();
		var elements = {
			sex: gender,
			dateOfBirth: dateOfBirth,
			email: email,
			fullName: name,
			personDescription: personDescription,
			personHabilities: personHabilities
		}
		openLoader();
		$.ajax({
		  	url: 'person/updatesomedata',
		  	dataType: 'JSON',
		  	type: 'POST',
		  	data: {dataToUpdate: elements},
		  	success: function(result){
		    	if(result.success == true){
		    		openToast(result.message);
		    	}
		    },
		    complete: function(){
		    	openLoader(false);
		    	window.location.reload();
		    }
		});
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
		console.log(elementOfModal);
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
			getLocation('#static-map');
			console.log(location);
		}
	};

	// asks for user location and populates the map with it
	function getLocation(setToElement = null){
	    if(navigator.geolocation){
			navigator.geolocation.getCurrentPosition((loc) => {
				latitude = loc.coords.latitude;
				longitude = loc.coords.longitude;
				callStaticMap(latitude, longitude);
				openToast("<?php echo ucfirst(translate('default location updated')); ?>");
				if(latitude != '' && longitude != ''){
					$.ajax({
					  	url: 'updatecookies/usergeolocation',
					  	type: 'POST',
					  	data: {latitude: latitude, longitude: longitude},
					  	success: function(result){
					    	console.log(result);
					    }
					});
				}
			},
			(err) => {
				return;
			})
		}else{ 
	        openToast("<?php echo ucfirst(translate('geolocation is not supported by this browser')); ?>");
	        return;
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

	var typeOfAtributeToAdd = null;
	$(document).on('click', '.no-data-add-button', function(){
		typeOfAtributeToAdd = $(this).attr('field-data-name');
		var html = '<input type="text" class="data-of-model">';
		$('.content-to-be-altered').html(html);
		openModal('modal3');
	});

	$('.edit-data-simple-modal').on('click', function(){
		openLoader();
		var value = $('.data-of-model').val();
		$.ajax({
		  	url: 'person/editbyfield',
		  	dataType: 'JSON',
		  	data: {type: typeOfAtributeToAdd, value: value},
		  	type: 'POST',
		  	success: function(result){
		    	if(result.success == true){
		    		openToast(result.message);
		    		if(typeOfAtributeToAdd == 'personHabilities'){
		    			$('.no-person-abilities').remove();
		    			$('.personHabilities').text(value);
		    		}
		    		if(typeOfAtributeToAdd == 'personDescription'){
		    			$('.no-person-description').remove();
		    			$('.personDescription').text(value);
		    		}
		    	}
		    },
		    complete: function(){
		    	openLoader(false);
		    }
		});
	});

	$('#static-map').on('click', function(){
		$(this).toggleClass('zoom-with-overflow');
	});
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
		border-radius: 15px;
	}
	.my-photo{
		text-align: center;
	}
	.my-photo > a > img{
		width: 250px;
		height: 250px;
		border-radius: 150px;
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

	.map-location{
		display: flex;
		flex-direction: column;
	}
	#static-map{
		width: 80%;
		margin: auto;
		margin-top: 5%;
		border-radius: 15px;
		max-width: 80%;
	}
	.reLocateMe{
		width: 20%;
		margin: auto;
    	margin-top: auto;
		text-align: center;
		margin-top: 5%;
		border-radius: 100px;
		padding: 5px;
		border: 1px solid rgba(0, 0, 0, 0.33);
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

	.optionForLocation:hover{
		opacity: 0.5;
	}

	.optionForLocation-close{
		left: 75%!important;
	}
	.optionForLocation-modalheader{
		margin-top: 5%;
		padding-top: 1%;
	}
	.optionForLocation-modalcontent{
		background: #fff;
		width: 60%;
		margin: auto;
	}
	.optionForLocation-modaltitle{
		margin-top: 5%;
		padding-top: 1%;
	}
	.optionForLocation-modaldata{
		background: #fff;
		width: 80%;
		margin: auto;
		text-align: center;
		display: flex;
		flex-direction: column;
		overflow-y: scroll;
		max-height: 80%;
		height: 500px;
	}
	.no-data-add-button{
		text-align: center;
		font-size: 2em;
		cursor: pointer;
	}
	.content-to-be-altered{
		width: 90%;
		margin: auto;
		height: 150px;
		text-align: center;
		display: flex;
	}
	.data-of-model{
		width: 80%;
		margin: auto;
	}
	#modal3 > .modal-content{
		height: 50%;
	}
	#modal4 > .modal-content{
		height: 80%;
	}
	.full-width{
		width: 100%;
	}
	.edit-field{
		padding: 15px;
		width: 50%;
		margin: auto;
	}
	@media only screen and (max-width: 600px) {
		.modal-content{
			margin-top: 20%;
		}
		.modal-options{
			width: 100%;
			text-align: center;
			margin: auto;
			justify-content: space-around;
		}
		.modal-options > a{
			margin: unset;
		}
		.my-photo > a > img{
			width: 100px;
			height: 100px;
			border-radius: 50px;
		}
		.flex-row{
			flex-direction: column;
			text-align: center;
		}
		.my-data{
			width: 95%;
		}
		.user-main-data{
			width: 100%;
		}
		.zoom-with-overflow{
			transform: scale(2.5);
			background: #fff;
			padding: 5px;
		}
	}
</style>