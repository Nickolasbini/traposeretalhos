<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
	<title><?= $title ?></title>

	<!-- importing jquery -->
	<script src="Source/Resourses/External/jquery/jquery-3.5.1.js"></script>
	<!-- importing FontAwesome -->
	<link href="Source/Resourses/External/fontawesome/css/all.css" rel="stylesheet"></link>
	<link href="Source/Resourses/CSS/modal.css" rel="stylesheet"></link>
</head>
<header>
	<nav id="top-navigation-bar">
		<div class="user-menu-options">
			<div class="banner-option"  title="<?php echo ucfirst(translate('select your language')) ?>">
			<img src="Source/Resourses/External/icons/<?php echo $_SESSION['userLanguage'] ?>.svg">
			<select class="language-selector">
				<option id="pt"><?php echo ucfirst(translate('portuguese')); ?></option>
				<option id="en"><?php echo ucfirst(translate('english')); ?></option>
				<option id="es"><?php echo ucfirst(translate('spanish')); ?></option>
			</select>
			</div>
			<a class="homepage-icon" href="<?php echo '/'.URL['urlDomain']; ?>" title="<?php echo ucfirst(translate('go to homepage')); ?>">
	    		<i class="fas fa-home"></i>
	    	</a>
		</div>

		<div class="header-logo">
			<img src="Source/Resourses/External/icons/logo-line&needle.svg">
			<h1><?php echo APP['appName'] ?></h1>
		</div>
	</nav>
	<div class="header-items">
		    <ul class="top-menu-options">
			    <li id="account-type" title="<?php echo ucfirst(translate('your occupation or know how')) ?>">
			    	<a><?php echo ucfirst(translate('type of account')); ?></a>
			    </li>
			    <li id="personal-data" title="<?php echo ucfirst(translate("let's get ot know each other")) ?>">
			    	<a><?php echo ucfirst(translate('personal data')); ?></a>
			    </li>
			    <li id="account-confirmation" title="<?php echo ucfirst(translate('final steps')) ?>">
			    	<a><?php echo ucfirst(translate('confirmation')); ?></a>
			    </li>
		    </ul>
	    </div>
</header>
<body>
	<section class="first-section">
		<h2 class="creation-of-account-title"><?php echo ucfirst(translate('select an option')); ?></h2>
		<section class="section-wrapper">
			<div class="wrapper-of-content">
				<div class="account-types">
					<a class="item-of-type" data-role="serviceSuplier"><?php echo ucfirst(translate('service supplier')); ?></a>
					<a class="item-of-type" data-role="storeOrSeller"><?php echo ucfirst(translate('store or seller')); ?></a>
					<a class="item-of-type" data-role="client"><?php echo ucfirst(translate('client')); ?></a>
				</div>
				<h3 class="my-type-of-account" style="display: none;">I am a <h4 class="show-type-to-user"></h4></h3>
				<h5 class="my-type-of-account-description">I like to inovate</h5>
				<a class="option-button next-button-section-one">Next <div style="margin-left: -5%;"><i class="fas fa-angle-right"></i><i class="fas fa-angle-right"></i></div></a>
			</div>
		</section>
	</section>

	<section class="second-section" style="display: none;">
		<h2 class="creation-of-account-title"><?php echo ucfirst(translate('tell us more about you')); ?></h2>
		<section class="section-wrapper">
			<div class="input-wrapper">
				<label><?php echo ucfirst(translate('your full name')) ?>*</label>
				<input class="required" type="text">
			</div>
			<div class="input-wrapper small-input">
				<label><?php echo ucfirst(translate('birth date')) ?>*</label>
				<input class="required" type="date">
			</div>
			<div class="input-wrapper small-input" style="width: 10%;">
				<label><?php echo ucfirst(translate('sex')) ?>*</label>
				<select class="required">
					<option>M</option>
					<option>F</option>
					<option>U</option>
				</select>
			</div>
			<div class="input-wrapper">
				<label><?php echo ucfirst(translate('about you')) ?>*</label>
				<textarea class="role-required" type="text"></textarea>
			</div>
			<div class="input-wrapper">
				<label><?php echo ucfirst(translate('your habilities')) ?>*</label>
				<textarea class="role-required" type="text"></textarea>
			</div>
			<div class="input-wrapper small-input">
				<label><?php echo ucfirst(translate('CEP')) ?>*</label>
				<input id="cep" class="required" type="text">
				<a id="search-for-cep" class="field-button"><?php echo ucfirst(translate('search')); ?></a>
			</div>
			<div class="text-wrapper" style="margin: 5% 0 5% 0;">
				<label>or</label>
			</div>
			<div class="google-map-wrapper">
				<div id="google-map-element" style="width: 100%; height: 100%;"></div>
			</div>
			<div class="text-wrapper">
				<label>confirm your address</label>
			</div>
			<div class="input-wrapper">
				<label>street</label>
				<input class="required" type="text">
			</div>
			<div class="input-wrapper">
				<label>neighborhood</label>
				<input class="required" type="text">
			</div>
			<div class="input-wrapper small-input">
				<label>number</label>
				<input class="required" type="text">
			</div>
			<div class="input-wrapper">
				<label>country</label>
				<select class="required" id="countries-list">
					<option value="no option" selected="selected"><?php echo ucfirst(translate('plese, select your country')) ?></option>
					<?php foreach($countries as $country){ ?>
						<option class="country-option" data-countryId="<?= $country['id']?>" value="<?= $country['name'] ?>">
							<?= $country['name'] ?>
						</option>
					<?php } ?>
				</select>
			</div>
			<div id="states" class="input-wrapper" style="display: none;">
				<label>state</label>
				<select class="required" id="states-list"></select>
			</div>
			<div id="cities" class="input-wrapper" style="display: none;">
				<label>city</label>
				<select class="required" id="cities-list"></select>
			</div>
			<div class="input-wrapper">
				<img id="selected-photo" width="300px;" style="margin: auto;" src="Source/Resourses/External/icons/account.svg">
			</div>
			<div class="input-wrapper">
				<label>select a profile picture</label>
				<br>
				<input id="photo-chooser-input" type="file" name="profile-picute" class="marginate-center">
			</div>
			<a class="option-button next-button-section-two">Next
				<div style="margin-left: -5%;">
					<i class="fas fa-angle-right"></i>
					<i class="fas fa-angle-right"></i>
				</div>
			</a>
		</section>
	</section>

	<br><br><br><br><br><br><br><br>
</body>
</html>

<div id="modal1" class="modal">
	<div class="modal-data content-of-modal">
    	<div class="modal-header">
        	<span class="close" title="<?php echo ucfirst(translate('close')) ?>">&times;</span>
			<h2 class="modal-title center-text"><?php echo ucfirst(translate('what is your specialization')); ?>?</h2>
		</div>
		<div class="role-img-handle" style="display: none;">
			<img title="<?php echo ucfirst(translate('this is your specialization type')) ?>" src="Source/Resourses/External/icons/seamstress.svg">
		</div>
		<div class="content-wrapper center-text account-types">
			<a class="role-name" role-name-data="seamstress"><?php echo ucfirst(translate('seamstress')) ?></a>
			<a class="role-name" role-name-data="tailor"><?php echo ucfirst(translate('tailor')) ?></a>
			<a class="role-name" role-name-data="dressmaker"><?php echo ucfirst(translate('dressmaker')) ?></a>	
		</div>
		<div class="modal-options">
			<a id="cancel" class="modal-options-buttons danger cancel-modal">
				<?php echo ucfirst(translate('cancel')) ?>
			</a>
			<a id="save-user-role" class="modal-options-buttons safe confirm-modal">
				<?php echo ucfirst(translate('confirm')) ?>
			</a>
		</div>
    </div>
</div>

<script src="Source/Resourses/JS-functions/modal.js"></script>
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBU-TEmfQj4HU2Janr2QIECDF2ciY1HvRY&callback=initMap"></script>
<script type="text/javascript">
	// to gather all data needed
	$(document).ready(function() {

	});

	$('#cep').on('input', function(){
		var cep = $(this).val();
		$.ajax({
			url: 'trytofindcep',
			type: 'POST',
			data: {cep: cep},
			dataType: 'JSON',
			success: function(data){
				if(data.content != null){
					alert('found something');
				}
			}
		});
	});

	/* Section one */
	$('#account-type').addClass('selected-on-menu');
	var userRole = null;
	$('#save-user-role').on('click', function(){
		userRole = $('.selected-button').attr('role-name-data');
		if(userRole == null)
			userRole = 'unknow';
		$('#account-type').removeClass('selected-on-menu');
		$('#personal-data').addClass('selected-on-menu');
		$('#personal-data').click();
	});
	var pathForImg = '<?= URL['iconsPath'] ?>';
	$('.role-name').hover(function(){
		$('.role-name').addClass('not-selected');
		$(this).removeClass('not-selected');
		if($('.role-name').hasClass('selected-button') == true)
			return;
		var roleName = $(this).attr('role-name-data');
		$('.role-img-handle > img').attr('src', pathForImg+roleName+'.svg');
		$('.role-img-handle').show();
	});
	$('.role-name').mouseout(function(){
		$('.role-name').removeClass('not-selected');
		$('.role-name').removeClass('not-selected');
	});
	$('.role-name').on('click', function(){
		$('.role-name').removeClass('selected-button');
		$(this).addClass('selected-button');
		changeIconOfWorker($(this));
	});
	// change the photo of worker icon by click on option
	function changeIconOfWorker(roleNameElement = null){
		var roleName = roleNameElement.attr('role-name-data');
		$('.role-img-handle > img').attr('src', pathForImg+roleName+'.svg');
		$('.role-img-handle').show();
	}
	// sets correspondent user accoun type
	function handleViewsByType(typeOfUser){
		switch(typeOfUser){
			case 'serviceSuplier':
			alert('please, select your type of service suplier');
			$('.role-img-handle').hide();
			openModal();
			break;

			case 'storeOrSeller':
			userRole = 'seller';
			$('#account-type').removeClass('selected-on-menu');
			$('#personal-data').addClass('selected-on-menu');
			$('#personal-data').click();
			break;

			case 'client':
			userRole = 'client';
			$('#account-type').removeClass('selected-on-menu');
			$('#personal-data').addClass('selected-on-menu');
			$('#personal-data').click();
			break;

			default:
			alert('an error occured');
			break;
		}
	}

	// proceed to next page or ask user his type of service
	$('.next-button-section-one').on('click', function(){
		var selectedElement = $('.last-selection');
		if(selectedElement.length == 0){
			alert('oh no, you must inform something');
			return;
		}
		var type = selectedElement.attr('data-role');
		handleViewsByType(type);
	});
	var defaultText = "<?php echo ucfirst(translate('I am a')) ?>";
	$('.item-of-type').hover(function(){
		$('.item-of-type').removeClass('last-selection');
		$(this).addClass('last-selection');
		var titleOfType = $(this).text();
		$('.my-type-of-account').text(defaultText+' '+titleOfType);
		$('.my-type-of-account').fadeIn('slow');
	});
	/* section one end*/

	/* section two */
	function removeFormerSection(currentSection = null){
		if(currentSection == 'personal-data'){
			$('.first-section').remove();
		}
	}
	// brings section two
	function insertSection(sectionName){
		if(sectionName == 'section2'){
			//$('body').load('Source/Resourses/Components/new-account-section2.php');
			$('.second-section').show();
		}
	}
	$('#personal-data').on('click', function(){
		if($(this).hasClass('selected-on-menu') == false){
			alert('you can not');
			return;
		}
		if(userRole == null){
			alert('something went wrong');
			return;
		}
		removeFormerSection('personal-data');
		insertSection('section2');
	});

	$('#fetch-by-map').on('click', function(){
		initMap();
	});

	var countryId = null;
	var countryName = null;
	$('#countries-list').on('change', function(){
		countryId = $(this).find(":selected").attr('data-countryId');
		countryName = $(this).val();
		if(countryName == 'no option'){
			alert('choose something');
			$('#states').hide();
			return;
		}
		$('#states').show();
		$('#states-list').html('');
		$.ajax({
			url: 'state/getstatesofcountry',
			type: 'POST',
			data: {countryId: countryId},
			dataType: 'JSON',
			success: function(data){
				if(data.success){
					if(data.numberOfStates > 0){
						var statesOptions = '<option value="no option" selected="selected"><?php echo ucfirst(translate('plese, select your state')) ?></option>'
						var states = data.content;
						console.log(states);
						for(i = 0; i < states.length; i++){
							console.log(states[i]);
							statesOptions += '<option data-stateId="'+states[i]['id']+'" value="'+states[i]['isoCode']+'">'+states[i]['isoCode']+'</option>';
						}
						$('#states-list').append(statesOptions);
					}
				}
			}
		});
	});

	var stateId = null;
	var stateName = null;
	$('#states-list').on('change', function(){
		stateId = $(this).find(":selected").attr('data-stateId');
		stateName = $(this).val();
		if(stateName == 'no option'){
			alert('choose something');
			$('#cities').hide();
			return;
		}
		$('#cities').show();
		$('#cities-list').html('');
		$.ajax({
			url: 'city/getcitiesofstate',
			type: 'POST',
			data: {stateId: stateId},
			dataType: 'JSON',
			success: function(data){
				if(data.success){
					if(data.numberOfCities > 0){
						var citiesOptions = '<option value="no option" selected="selected"><?php echo ucfirst(translate('plese, select your city')) ?></option>'
						var cities = data.content;
						console.log(cities);
						for(i = 0; i < cities.length; i++){
							citiesOptions += '<option data-cityId="'+cities[i]['id']+'" value="'+cities[i]['name']+'">'+cities[i]['name']+'</option>';
						}
						console.log(citiesOptions);
						$('#cities-list').append(citiesOptions);
					}
				}
			}
		});
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
	};




	// googler map related methods
	function initMap(){
		var latitudeCookie  = "<?= isset($_COOKIE['latitude'])  ? $_COOKIE['latitude']  : '' ?>";
		var longitudeCookie = "<?= isset($_COOKIE['longitude']) ? $_COOKIE['longitude'] : '' ?>";
		// checking if it has cookies
		if(latitudeCookie != '' && longitudeCookie != ''){
			var location = {
				lat:parseFloat(latitudeCookie),
				lng:parseFloat(longitudeCookie)
			}
			var options = {
				center: location,
				zoom: 18
			}
			map = new google.maps.Map(document.getElementById('google-map-element'), options);
			new google.maps.Marker({
			    position: location,
			    map,
			});
		}else{
			var latitudeOfSession = <?= $_SESSION['userLatitude'] ?>;
			var longitudeOfSession = <?= $_SESSION['userLongitude'] ?>;
			var location = {
				lat:parseFloat(latitudeOfSession),
				lng:parseFloat(longitudeOfSession)
			}
			var options = {
				center: location,
				zoom: 18
			}
			if(navigator.geolocation){
				navigator.geolocation.getCurrentPosition((loc) => {
					location.lat = loc.coords.latitude;
					location.lng = loc.coords.longitude;
					var currentLocation =  {
						lat: location.lat,
						lng: location.lng
					}
					// write current position to the map
					map = new google.maps.Map(document.getElementById('google-map-element'), options);
					// inserting a marker
					new google.maps.Marker({
					    position: currentLocation,
					    map,
					});

					updateLocationCokies(location.lat, location.lng);
				},
				(err) => {
					map = new google.maps.Map(document.getElementById('google-map-element'), options);
				}
				)
			}else{
				map = new google.maps.Map(document.getElementById('google-map-element'), options)
			}
		}
	}
	function updateLocationCokies(latitude, longitude){
		$.ajax({
		  	url: 'updatecookies/usergeolocation',
		  	type: 'POST',
		  	data: {latitude: latitude, longitude: longitude},
		  	success: function(result){
		    	console.log(result);
		    }
		});
	}
</script>

<style type="text/css">
	body{
		background: #F0F0F0;
	}
	a, select, img{
		cursor: pointer;
	}
	*{
		font-family: Sen;
	}
	header{
		margin-bottom: 5%;
	}

	/* The user option to click */
	.user-menu-options{
		display: flex;
		width: 100%;
		justify-content: space-between;
		background: #F5F4F4;
		border-bottom: 1px solid #C4C4C4;
	}
	/* The banner and language selectors */
	.banner-option{
		display: flex;
	}
	.banner-option>img{
		width: 50px;
	}
	.banner-option>a{
		margin: auto;
		margin-left: 5px;
	}
	/* The selector related CSS */
	.language-selector{
		border: none;
		background: #F5F4F4;
	}
	.language-selector {
	  -moz-appearance: none; /* for Firefox */
	  -webkit-appearance: none;/* for Chrome */
	}
	.language-selector::-ms-expand {
	  display: none;/* For IE10 */
	}
	.homepage-icon{
		font-size: 50px;
		color: rgba(0, 0, 0, 0.33);
	}
	/* The logo */
	.header-logo{
		display: flex;
		justify-content: center;
		padding: 5%;
	}
	.header-logo>img{
		cursor: default!important;
		width: 15%;
	}
	.header-logo>h1{
		color: rgba(0, 0, 0, 0.33);
	}
	/* The clicable option to navigate the system*/
	.header-items{
		background: rgba(0, 0, 0, 0.67);
	}
	.header-items>ul{
		display: flex;
		justify-content: space-evenly;
		height: 70px;
	}
	.header-items>ul>li{
		list-style: none;
		margin: auto;
	}
	.header-items>ul>li>a{
		text-decoration: none;
		color: #FFFFFF;
		font-size: 25px;
	}
	.header-items>ul>li>a:hover:not(.selected-on-menu>a){
		text-decoration: underline;
	}
	.homepage-icon>i{
		font-size: 40px;
	}
	/* The selected item on menu CSS */
	.selected-on-menu{
		display: flex;
		height: 70px;
		text-align: center;
		background: rgba(218, 221, 90, 0.78);
		padding: 0 15px;
	}
	.selected-on-menu>li{
		width: 30%;
	}
	.selected-on-menu>a{
		color: #ffffff!important;
		width: 100%;
		margin: auto;
	}
	/* contents */
	.creation-of-account-title{
		text-align: center;
	}
	.section-wrapper{
		display: flex;
		flex-direction: column;
		width: 60%;
		margin: auto;
		text-align: center;
		background: #fff;
		border-radius: 5px;
		border: 0.5px solid #c4c4c4;
		padding: 5%;
	}
	.my-type-of-account{
		color: rgba(0, 0, 0, 0.33);
	}
	.account-types{
		display: flex;
		justify-content: space-between;
	}
	.account-types a{
		width: 28%;
		padding: 2%;
		text-decoration: none;
		color: #313131;
		border-radius: 5px;
		border: 0.5px solid #c4c4c4;
		letter-spacing: 1px;
		background: #F0F0F0;
	}
	.account-types a:hover{
		background: rgba(218, 221, 90, 0.78);
		color: #ffffff;
		transition: 0.5s;
	}

	.last-selection{
		background: rgba(218, 221, 90, 0.78)!important;
		color: #ffffff!important;
		transition: 0.5s;
	}
	.option-button{
		padding: 2%;
		background: rgba(218, 221, 90, 0.78);
		border-radius: 5px;
		display: flex;
		width: 30%;
		margin: auto;
		justify-content: space-evenly;
		color: #fff;
		text-decoration: none;
		font-size: 25px;
		margin-top: 15%;
	}
	.role-img-handle{
		width: 15%;
		margin: auto;
		margin-bottom: auto;
		text-align: center;
		background: #eee;
		margin-bottom: 5%;
		border-radius: 5px;
		border: 1px solid #d9d3d3;
		padding: 5%;
	}
	.role-img-handle > img{
		width: 100%;
	}

	.selected-button{
		background: rgba(218, 221, 90, 0.78)!important;
		color: #ffff!important;
	}
	.not-selected{
		opacity: 0.5;
	}

	.input-wrapper{
		display: flex;
		justify-content: center;
		flex-direction: column;
		width: 80%;
		margin: auto;
		margin-bottom: 5%;
	}
	.input-wrapper label{
		margin: 5%;
	}
	.small-input{
		width: 30%;
		text-align: center;
	}
	.small-input > input{
		text-align: center;
	}
	.marginate-center{
		margin: auto;
	}

	.google-map-wrapper{
		width: 500px;
		height: 500px;
		margin: auto;
		margin-bottom: 5%;
	}

	#search-for-cep{
		border: 1px solid gray;
		border-radius: 15px;
		width: 50%;
		margin: auto;
		margin-top: 10%;
	}
</style>