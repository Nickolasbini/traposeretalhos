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
					<a class="item-of-type" data-role=""><?php echo ucfirst(translate('client')); ?></a>
				</div>
				<h3 class="my-type-of-account" style="display: none;">I am a <h4 class="show-type-to-user"></h4></h3>
				<h5 class="my-type-of-account-description">I like to inovate</h5>
				<a class="option-button next-button-section-one">Next
					<div style="margin-left: -5%;">
						<i class="fas fa-angle-right"></i>
						<i class="fas fa-angle-right"></i>
					</div>
				</a>
			</div>
		</section>
	</section>

	<section class="second-section" style="display: none;">
		<h2 class="creation-of-account-title"><?php echo ucfirst(translate('tell us more about you')); ?></h2>
		<section class="section-wrapper">
			<div class="input-wrapper">
				<label><?php echo ucfirst(translate('your full name')) ?>*</label>
				<input class="required fullName" type="text">
				<label class="name-tip" style="margin: 0; display: none;"><?php echo ucfirst(translate('special letters are not valid. Ex: @, # ...')) ?></label>
			</div>
			<div class="input-wrapper small-input">
				<label><?php echo ucfirst(translate('birth date')) ?>*</label>
				<input class="required dateOfBirth" type="date">
			</div>
			<div class="input-wrapper small-input" style="width: 10%;">
				<label><?php echo ucfirst(translate('sex')) ?>*</label>
				<select class="required sex">
					<option>M</option>
					<option>F</option>
					<option>U</option>
				</select>
			</div>
			<div class="input-wrapper small-input">
				<label><?php echo ucfirst(translate('CEP')) ?>*</label>
				<input id="cep" class="required cep" type="text">
				<a id="search-for-cep" class="field-button"><?php echo ucfirst(translate('search')); ?></a>
			</div>
			<div class="text-wrapper need-cep-to-be-informed">
				<label>confirm your address</label>
			</div>
			<div class="input-wrapper need-cep-to-be-informed">
				<label>country</label>
				<select class="required country" id="countries-list">
					<option value="no option" selected="selected"><?php echo ucfirst(translate('plese, select your country')) ?></option>
					<?php foreach($countries as $country){ ?>
						<option class="country-option countryID-<?= $country['id'] ?>" data-countryId="<?= $country['id']?>" value="<?= $country['name'] ?>">
							<?php echo isset($country['translation']) ? ucfirst($country['translation'][$_SESSION['userLanguage']]) : ucfirst($country['name']); ?>
						</option>
					<?php } ?>
				</select>
			</div>
			<div id="states" class="input-wrapper" style="display: none;">
				<label>state</label>
				<select class="required state" id="states-list"></select>
			</div>
			<div id="cities" class="input-wrapper" style="display: none;">
				<label>city</label>
				<select class="required city" id="cities-list"></select>
			</div>
			<div class="input-wrapper need-cep-to-be-informed">
				<label>street</label>
				<input class="required street" type="text">
			</div>
			<div class="input-wrapper need-cep-to-be-informed">
				<label>neighborhood</label>
				<input class="required neighborhood" type="text">
			</div>
			<div class="input-wrapper small-input need-cep-to-be-informed">
				<label>number</label>
				<input class="required addressNumber" type="text">
			</div>
			<div class="input-wrapper" title="<?php echo ucfirst(translate('choose a photo')); ?>">
				<label>select a profile picture</label>
				<img id="selected-photo" width="300px;" height="300px" style="margin: auto; border-radius: 50%;" src="Source/Resourses/External/icons/account.svg">
				<input id="photo-chooser-input" class="profilePhoto" type="file" name="profile-picute" style="display: none;">
			</div>
			<div class="input-wrapper" title="<?php echo ucfirst(translate('choose a photo')); ?>">
				<div id="add-photo-button">
					<i class="fas fa-plus-circle"></i>
				</div>
			</div>
			<a class="option-button next-button-section-two">Next
				<div style="margin-left: -5%;">
					<i class="fas fa-angle-right"></i>
					<i class="fas fa-angle-right"></i>
				</div>
			</a>
		</section>
	</section>

	<section class="third-section" style="display: none;">
		<h2 class="creation-of-account-title"><?php echo ucfirst(translate('contact information')); ?></h2>
		<section class="section-wrapper">
			<div class="input-wrapper">
				<label>email</label>
				<input class="required email" type="email">
			</div>
			<div class="input-wrapper">
				<label>password</label>
				<input class="required password" type="password">
			</div>
			<div class="input-wrapper">
				<label>repeat password</label>
				<input class="required repeatPassword" type="password">
			</div>
			<a class="option-button next-button-section-three"><?php echo ucfirst(translate('complete account')); ?></a>
		</section>
	</section>

	<?php $this->insert('user-footer') ?>

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
			<?php
				foreach(json_decode($roles, true) as $role){
					if(!$role['isUsedOnMap'])
						continue;
			?>
				<a class="role-name" role-id="<?= $role['id']; ?>" role-icon="<?= $role['iconUrl']; ?>"><?php echo ucfirst(translate($role['roleName'])) ?></a>
			<?php } ?>
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
<script type="text/javascript">
	var accountData = {};

	// to gather all data needed
	$(document).ready(function() {
		insertSection('section1');
		$('.need-cep-to-be-informed').hide();
	});

	$('#selected-photo').on('click', function(){ 
		$('#photo-chooser-input').trigger('click');
	});
	$('#add-photo-button').on('click', function(){ 
		$('#photo-chooser-input').trigger('click');
	});

	// search for cep and complete data accordingly to return
	$('#search-for-cep').on('click', function(){
		alert('put a loader here');
		var cep = $('#cep').val();
		$.ajax({
			url: 'trytofindcep',
			type: 'POST',
			data: {cep: cep},
			dataType: 'JSON',
			success: function(data){
				if(data.content != null){
					$('.need-cep-to-be-informed').show();
					alert('found something');
					fillFieldsWithCEPData(data.content);
					return;
				}
				alert('please enter a valid CEP');
			},
			complete: function(){
				alert('the loader end');
			}
		});
	});

	// fill fields with gathered data
	var city = null;
	var neighborhood = null;
	var street = null;
	var stateISO = null;
	function fillFieldsWithCEPData(data = null){
		if(data == null || data.length < 1){
			alert('an error occured');
			return;
		}
		city 		 = data['cityName'];
		neighborhood = data['neighborhood'];
		street 		 = data['streetName'];
		stateISO     = data['stateCode'];
		$('.street').val(street);
		$('.street').removeClass('must-complete');
		$('.neighborhood').val(neighborhood);
		$('.neighborhood').removeClass('must-complete');
		var dataFound = null;
		$.ajax({
			url: 'state/getcountrybystate',
			type: 'POST',
			data: {stateISO: stateISO},
			dataType: 'JSON',
			success: function(data){
				if(data.success){
					dataFound = data;
				}
			},
			complete: function(){
				if(dataFound == null){

					return;
				}
				$('.countryID-'+dataFound.data.id).attr('selected','selected');
				$('#countries-list').change();
			}
		});

	}

	/* Section one */
	$('#account-type').addClass('selected-on-menu');
	var userRole = null;
	$('#save-user-role').on('click', function(){
		userRole = $('.selected-button').attr('role-id');
		if(userRole == null)
			userRole = 'unknow';
		$('#account-type').removeClass('selected-on-menu');
		$('#personal-data').addClass('selected-on-menu');
		$('#personal-data').click();
		accountData['role'] = userRole;
	});
	var pathForImg = '<?= URL['iconsPath'] ?>';
	$('.role-name').hover(function(){
		$('.role-name').addClass('not-selected');
		$(this).removeClass('not-selected');
		if($('.role-name').hasClass('selected-button') == true)
			return;
		var roleIcon = $(this).attr('role-icon');
		$('.role-img-handle > img').attr('src', pathForImg+roleIcon);
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
		var roleIcon = roleNameElement.attr('role-icon');
		$('.role-img-handle > img').attr('src', pathForImg+roleIcon);
		$('.role-img-handle').show();
		$(document).scrollTop($('#modal1').height());
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
				accountData['role'] = userRole;
			break;
			case 'client':
				userRole = 'client';
				$('#account-type').removeClass('selected-on-menu');
				$('#personal-data').addClass('selected-on-menu');
				$('#personal-data').click();
				accountData['role'] = null;
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
		if(currentSection == 'section2'){
			$('.second-section').remove();
		}
	}
	// brings section two
	function insertSection(sectionName){
		if(sectionName == 'section1'){
			$('.first-section').show();
		}
		if(sectionName == 'section2'){
			$('.second-section').show();
		}
		if(sectionName == 'section3'){
			$('.third-section').show();
		}
		$(document).scrollTop(0);
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
						for(i = 0; i < states.length; i++){
							statesOptions += '<option class="stateName-'+states[i]['isoCode']+'" data-stateId="'+states[i]['id']+'" value="'+states[i]['isoCode']+'">'+states[i]['isoCode']+'</option>';
						}
						$('#states-list').append(statesOptions);
					}
				}
			},
			complete: function(){
				$('.stateName-'+stateISO).attr('selected', 'selected');
				$('#states-list').change();
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
						for(i = 0; i < cities.length; i++){
							citiesOptions += '<option class="cityISOCode-'+cities[i]['isoCode']+'" data-cityId="'+cities[i]['id']+'" value="'+cities[i]['name']+'">'+capitalize(cities[i]['regionName'])+'</option>';
						}
						$('#cities-list').append(citiesOptions);
					}
				}
			},
			complete: function(){
				$.ajax({
					url: 'city/gathercitybyname',
					type: 'POST',
					data: {cityName: city},
					dataType: 'JSON',
					success: function(response){
						if(response.success == true){
							var cityData = response.data;
							var isoCodeOfCity = cityData.isoCode;
							$('.cityISOCode-'+isoCodeOfCity).attr('selected', 'selected');
							$('#cities-list').change();
						}
					}
				});
			}
		});
	});

	var defaultImage = true;
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

	var invalidWords = ['@', '#', '!', '?', '/', '|', ',', ';', '&', '*', '+', '-', '(', ')', ':', '.', ':', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
	$('.fullName').on('input', function(){
		var value = $(this).val();
		value = value.charAt(value.length - 1);
		for(i = 0; i < invalidWords.length; i++){
			if(value == invalidWords[i]){
				$('.name-tip').show();
			}
		}
	});

	var dataNames = ['fullName', 'dateOfBirth', 'sex', 'cep', 'street', 'neighborhood', 'addressNumber', 'country', 'state', 'city', 'profilePhoto'];
	$('.next-button-section-two').on('click', function(){
		var hasNeededFields = true;
		var requiredFields = $('.second-section').find('.required');
		requiredFields.removeClass('must-complete');
		requiredFields.each(function(){
			var value = $(this).val()
			if(value == ''){
				$(this).addClass('must-complete');
				hasNeededFields = false;
			}
		});

		if(hasNeededFields == false){
			$(document).scrollTop(0);
			alert('please, enter the required fields');
			return;
		}

		for(i = 0; i < dataNames.length; i++){
			if(dataNames[i] == 'fullName'){
				var name = $('.'+dataNames[i]).val();
				if(name.length < 5){
					$(document).scrollTop($('.fullName'));
					$('.fullName').focus();
					alert('name is too short, minimum length is five');
					return;
				}
				accountData['fullName'];
				continue;
			}
			if(dataNames[i] == 'profilePhoto'){
				var imgSrc = null;
				if(defaultImage == false){
					imageSrc = $('#selected-photo').attr('src');
				}
				accountData['profilePhoto'] = imageSrc;
				continue;
			}
			if(dataNames[i] == 'dateOfBirth'){
				var dateChosen = $('.dateOfBirth').val();
				var age = calculateAge(new Date(dateChosen));
				if(age <= 18){
					alert('must be older than seventeen years old');
					$(document).scrollTop('.dateOfBirth');
					return;
				}
			}
			accountData[dataNames[i]] = $('.'+dataNames[i]).val();
		}

		$.ajax({
			url: 'person/verifyname',
			type: 'POST',
			data: {name: accountData.fullName},
			dataType: 'JSON',
			success: function(response){
				if(response.success == true){
					if(response.isInUse == true){
						alert('name is already in use, please try another one');
						$(document).scrollTop('.fullName');
						return;
					}
				}
			}
		});
		// calling next section		
		removeFormerSection('section2');
		$('#personal-data').removeClass('selected-on-menu');
		$('#account-confirmation').addClass('selected-on-menu');
		insertSection('section3');
	});

	// section 3
	$('.next-button-section-three').on('click', function(){
		var hasNeededFields = true;
		var required = $('.third-section').find('.required');
		required.each(function(){
			var value = $(this).val();
			if(value == ''){
				$(this).addClass('must-complete');
				hasNeededFields = false;
			}
		});
		if(hasNeededFields == false){
			$(document).scrollTop(0);
			alert('please, enter the required fields');
			return;
		}

		var password = $('.password').val();
		if(password != $('.repeatPassword').val()){
			alert('password does no match');
			return;
		}

		accountData['email']     = $('.email').val();
		accountData['password']  = $('.password').val();

		$.ajax({
			url: 'person/save',
			type: 'POST',
			data: {accountData: accountData},
			dataType: 'JSON',
			success: function(response){
				if(response.success == true){
					// open the confirmation informing an email will be sent
				}else{
					alert(response.message);
					return;
				}
			}
		});
	});
	
	function capitalize(text) {
	    return text.charAt(0).toUpperCase() + text.slice(1);
	}

	// calculates the age from a date until now
	function calculateAge(dob) { 
	    var diff_ms = Date.now() - dob.getTime();
	    var age_dt = new Date(diff_ms); 
	    return Math.abs(age_dt.getUTCFullYear() - 1970);
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
		font-family: Arial, Helvetica, sans-serif;
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
	.master-section{

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

	#add-photo-button{
		width: 25%;
		margin: auto;
		font-size: 3em;
		color: rgba(0, 0, 0, 0.67);
	}
	#add-photo-button:hover{
		color: rgba(218, 221, 90, 0.78);
	}

	.must-complete{
		border: 1px solid red;
	}
</style>