<?php $this->insert('user-header', ['title' => ucfirst(translate('map'))]) ?>
<body>
	<section class="master-div">
		<div class="left-options">
			<div class="left-filter-top">
				<h5 style="margin: 10px 0 0 10px;"><?php echo ucfirst(translate('filter')); ?></h5>
			</div>
			<h5 style="margin-bottom: 4%; margin-left: 10px;"><?php echo ucfirst(translate('distance')); ?></h5>
			<div class="distance-meter">
				<div class="km-wrapper">
					<input id="distance-meter-start-mark" class="km-calculator" type="number" value="1" placeholder="1">
					<h6 class="km-simbol">Km</h6>
				</div>
				<div class="km-wrapper">
					<input id="distance-meter-end-mark" class="km-calculator" type="number" value="100"placeholder="100">
					<h6 class="km-simbol">Km</h6>
				</div>				
			</div>
			<div class="slidecontainer">
				<!--<div style="display: flex;justify-content: space-between;">
					<span>1</span><span>20</span><span>45</span><span>70</span><span>100</span>
				</div>
				<div style="display: flex;justify-content: space-between;">
					<span>|</span><span>|</span><span>|</span><span>|</span><span>|</span>
				</div-->
			    <input type="range" min="1" max="100" value="0" class="slider" id="myRange">
			    <!--<datalist id="kilometers-list">
				  <option value="0" label="0 km"></option>
				  <option value="10"></option>
				  <option value="20"></option>
				  <option value="30"></option>
				  <option value="40"></option>
				  <option value="50" label="50 km"></option>
				  <option value="60"></option>
				  <option value="70"></option>
				  <option value="80"></option>
				  <option value="90"></option>
				  <option value="100" label="100 km"></option>
				</datalist>-->
			</div>
			
			<div class="divider"></div>

			<h4><?php echo ucfirst(translate('what do you seek')); ?>?</h4>
			<div class="roles-options">
			<?php if(isset($_SESSION['roles'])){
				$roles = json_decode($_SESSION['roles'], true);
				foreach($roles as $data){
					if($data['isUsedOnMap'] != 1)
						continue;
					?>
					<div class="option-of-role">
					<input id="<?= $data['roleName'] ?>" class="roleOption" data-id="<?= $data['id'] ?>" data-creation-date="<?= $data['dateOfCreation'] ?>" type="checkbox">
						<label class="nameOfRole" for="<?= $data['roleName'] ?>"><?php echo ucfirst(translate($data['roleName'])); ?></label>
					</div>
			<?php }} ?>
			</div>

			<div class="best-professional-group" style="display: none;">
				<div class="left-filter-top" style="margin-top: 25px;">
					<h5 style="margin: 10px 0 0 10px;"><?php echo ucfirst(translate('best professionals')); ?></h5>
				</div>
			</div>
		</div>

		<div class="right-options">
			<div class="upper-filter">
				<h5><?php echo ucfirst(translate('filter by')); ?>:</h5>
				<select class="filter-map-by">
					<option value="city"><?php echo ucfirst(translate('city')); ?></option>
					<option value="state" selected="selected"><?php echo ucfirst(translate('state')); ?></option>
					<option value="country"><?php echo ucfirst(translate('country')); ?></option>
				</select>
				<input id="search-field" type="text" value="Paraná">
				<img class="search-icon" style="display: none;" src="<?= URL['iconsPath']; ?>search.svg">
			</div>

			<div class="map-wrapper">
		 		<div id="google-map-element"></div>
			</div>

			<div class="map-legend">
				<?php foreach($roles as $role){ if(!$role['isUsedOnMap']){ continue; } ?>
					<li class="map-legend-item">
						<div style="background: <?= $role['colorOnMap'] ?>;"></div>
						<a><?php echo ucfirst(translate($role['roleName'])) ?></a>
					</li>
	  			<?php } ?>
			</div>
			<div class="reLocateMe">
				<div>
					<img src="<?= URL['iconsPath']; ?>location-marker.svg"><a><?php echo ucfirst(translate('find me')); ?></a>
				</div>
			</div>
		</div>
	</section>

	<section class="all-professionals-section">
		<div class="all-professionals-title">
			<h5>See all our professionals profile</h5>
		</div>
		<div class="roles-cards">
			<?php foreach($roles as $role){ ?>
				<div class="card-of-role" title="see all">
					<img src="<?= URL['iconsPath'] . $role['iconUrl']; ?>">
					<a><?= $role['roleName'] ?></a>
				</div>
			<?php } ?>
		</div>
	</section>	

	<!-- The place to set the error -->
	<p id="demo"></p>
	
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBU-TEmfQj4HU2Janr2QIECDF2ciY1HvRY"></script>
	
	<?php $this->insert('user-footer') ?>

	<div id="modal1" class="modal">
		<div class="modal-data content-of-modal">
	    	<div class="modal-header">
	        	<span class="close" title="<?php echo ucfirst(translate('close')) ?>">&times;</span>
				<h2 class="modal-title center-text"><?php echo ucfirst(translate('choose an option')) ?></h2>
			</div>
			<div class="content-wrapper center-text itens-to-choose"></div>
	    </div>
	</div>
	<?php include "Source/Resourses/Components/edit-comment-modal.php" ?>
	<?php include "Source/Resourses/Components/edit-post-modal.php" ?>
	<script src="Source/Resourses/JS-functions/modal.js"></script>
</body>
<link href="Source/Resourses/CSS/maps-view-css.css" rel="stylesheet"></link>
<script>
	var latitudeCookie  = "<?= isset($_COOKIE['latitude'])  ? $_COOKIE['latitude']  : '' ?>";
	var longitudeCookie = "<?= isset($_COOKIE['longitude']) ? $_COOKIE['longitude'] : '' ?>";
	$(document).ready(function(){
		feedRoleIconArray();
		// create map with all data needed, current location with marker and professionals
		// from around
		if(latitudeCookie == '' || longitudeCookie == ''){
		    askForLocationAndFeedMap();
		}else{
			feedProfessionalsOnMap(latitudeCookie, longitudeCookie);
		}
	});

	// tries to get location from cookies else asks for the location, if it can't
	// get the location from the IP which is kind of innacurate
	function askForLocationAndFeedMap(){
	    if(navigator.geolocation){
			navigator.geolocation.getCurrentPosition((loc) => {
				location.lat = loc.coords.latitude;
				location.lng = loc.coords.longitude;
				// permission granted
				latitudeCookie  = location.lat;
				longitudeCookie = location.lng;
				updateLocationCokies(location.lat, location.lng);
				feedProfessionalsOnMap(latitudeCookie, longitudeCookie);
			},
			(err) => {
				// in case user did not allow the use of its location use the location
				// gathered from the IP which shall not be accurate
				latitudeCookie = <?= $_SESSION['userLatitude'] ?>;
				longitudeCookie = <?= $_SESSION['userLongitude'] ?>;
				updateLocationCokies(location.lat, location.lng);
				feedProfessionalsOnMap(latitudeCookie, longitudeCookie);
			},
				{maximumAge:10000, timeout:5000, enableHighAccuracy: true}
			);
		}else{
			// in case user does not have the possibility of the ask for the location
			// will use the unacurrate location gathered from IP
			latitudeCookie = <?= $_SESSION['userLatitude'] ?>;
			longitudeCookie = <?= $_SESSION['userLongitude'] ?>;
			updateLocationCokies(location.lat, location.lng);
			feedProfessionalsOnMap(latitudeCookie, longitudeCookie);
		}
	}

	var professionalsMarker = [];
	var rolesToNotDisplay = null;
	var currentLocation = {};
	var askIfLocationIsCorrect = null;
	var markerColors = {1: 'red', 2: 'blue', 3: 'green', 4: 'purple', 5: 'yellow'};
	function initMap(loadMarkers = null){
		// checking if it has cookies
		if(latitudeCookie != '' && longitudeCookie != ''){
			// in case it already has a location cookie
			var location = {
				lat:parseFloat(latitudeCookie),
				lng:parseFloat(longitudeCookie)
			}
			var options = {
				center: location,
				zoom: 18
			}
			map = new google.maps.Map(document.getElementById('google-map-element'), options);
			var userPositionMarker = new google.maps.Marker({
			    position: location,
			    map,
			    animation: google.maps.Animation.DROP,
			    title: "<?php echo ucfirst(translate('your location')); ?>"
			});

			if(loadMarkers == true){
				if(professionalsMarker.length > 0){
					var infowindow = new google.maps.InfoWindow();
			        var marker, i;
			        for (i = 0; i < professionalsMarker.length; i++){
			        	var colorForMarker = "http://maps.google.com/mapfiles/ms/icons/";
  						colorForMarker += markerColors[i + 1] + "-dot.png";
			            marker = new google.maps.Marker({
			                position: {
						    	lat: parseFloat(professionalsMarker[i]['latitude']),
						    	lng: parseFloat(professionalsMarker[i]['longitude'])
						    },
			                map: map,
			                animation: google.maps.Animation.DROP,
			                icon:{
						        url: colorForMarker
						    },
			                title: "<?php echo ucfirst(translate('professional location')); ?>"
			            });
			            // change here to send an Object
			            generateContentForInfoView(professionalsMarker[i].title, professionalsMarker[i].description, professionalsMarker[i].url, professionalsMarker[i].logo, professionalsMarker[i].score, professionalsMarker[i].role)
			            google.maps.event.addListener(marker, 'click', (function(marker, i) {
			              return function() {
			                infowindow.setContent(htmlInfoViewGenerated[i]);
			                infowindow.open(map, marker);
			              }
			            })(marker, i));
			        }
				}
			}
			setMapRadiusInRelationToUser(map, userPositionMarker);
		}else{
			// in case there are no cookies with the location
			// in this case use the iPAddresLocation gathered by the IP api
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
			// try to use user location
			if(navigator.geolocation){
				navigator.geolocation.getCurrentPosition((loc) => {
					location.lat = loc.coords.latitude;
					location.lng = loc.coords.longitude;
					updateLocationCokies(location.lat, location.lng);
					feedProfessionalsOnMap(location.lat, location.lng);
				},
				(err) => {
					// in case user did not allow the use of its location use the location
					// gathered from the IP which shall not be accurate
					updateLocationCokies(location.lat, location.lng);
					feedProfessionalsOnMap(location.lat, location.lng);
				})
			}else{
				// in case user did not allow the use of its location use the location
				// gathered from the IP which shall not be accurate
				updateLocationCokies(location.lat, location.lng);
				feedProfessionalsOnMap(location.lat, location.lng);
			}
		}
		if(askIfLocationIsCorrect == true)
			openConfirmationAlert();
		askIfLocationIsCorrect = null;
	}

	var mapObject = null;
	var userLocationMarkerObject = null;
	var radiusInMeters = 1000;
	// Creates the circle radius on the map accordinally to 'radiusInMeters' variable
	function setMapRadiusInRelationToUser(map = null, userLocationMarker = null){
		mapObject = map;
		userLocationMarkerObject = userLocationMarker;
		// Add circle overlay and bind to marker
		var circle = new google.maps.Circle({
		  map: map,
		  radius: radiusInMeters,
		  fillColor: '#756b6b'
		});
		circle.bindTo('center', userLocationMarker, 'position');
		circle.setOptions({fillColor: "#756b6b", strokeColor: "#756b6b"});
	}

	// try here to clean map markers
	function clearMarkerOnMap() {
	    professionalsMarker = [];
	}

	// think here how to call again just to try to gather the new position
	$('.reLocateMe > div').on('click', function(){
		$.ajax({
		  	url: 'updatecookies/usergeolocation',
		  	type: 'POST',
		  	data: {removeCookies: true},
		  	success: function(result){
		    	//console.log(result);
		    },
		    complete: function(){
		    	askForLocationAndFeedMap();
		    	askIfLocationIsCorrect = true;
		    	setTitleAndMessage("<?php echo ucfirst(translate('is this your correct location?')); ?>");
		    	setButtonsMessage("<?php echo ucfirst(translate('no, it is not')); ?>", "<?php echo ucfirst(translate('yes, it is correct')); ?>");
		    }
		});
	});

	// create this question modal in order to ask person address
	$('.cancelbutton').on('click', function(){
		openQuestionModal();
	});
	$('.confirmbutton').on('click', function(){
		closeConfirmationAlert();
	});

	// triggers new map type accordingly to checkboxes
	$('.option-of-role').on('click', function(){
		$(this).toggleClass('wasClicked');
		if($(this).hasClass('wasClicked') == true)
			return;
		feedProfessionalsOnMap(currentLocation.latitude, currentLocation.longitude);
	});

	let typingTimer;
    let typingInterval = 800;
    $('#search-field').on('input', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(fetchLocations, typingInterval);
    });

    function fetchLocations(){
    	var valueToSearch = $('#search-field').val();
    	if(valueToSearch == ''){
    		return;
    	}
    	openLoader();
    	$.ajax({
		  	url: 'state/search',
		  	type: 'POST',
		  	dataType: 'JSON',
		  	data: {criteria: 'name', value: valueToSearch},
		  	success: function(response){
		  		if(response.success == true){
		  			var elements = response.content;
		  			if(elements.length > 0){
			  			// feed dropdown with tips
			  			var html = '<select class="options-of-items-select">';
			  			for(i = 0; i < elements.length; i++){
			  				html += '<option class="items-to-be-chosen" data-name="'+elements[i]['name']+'" value="'+elements[i]['id']+'">'+elements[i]['name']+'</option>';
			  			}
			  			html += '</select>'; 
			  			$('.itens-to-choose').html(html);
			  			$('.itens-to-choose').append('<div class="confirm-the-selection">confirm</div>');
			  			openModal('modal1');
		  			}else{
		  				openToast("<?php echo ucfirst(translate('nothing found')); ?>");
		  			}
		  		}
		  	},
		  	complete: function(){
		  		openLoader(false);
		  	}
		});
    }

    $(document).on('change', '.itens-to-choose', function(){
    	$(document).find('.confirm-the-selection').click();
    });

    $(document).on('click', '.confirm-the-selection', function(){
    	closeModal();
    	var itemChosenId   = $(document).find('.itens-to-choose > select').val();
    	var itemChosenName = $(document).find('.itens-to-choose').find(':selected').attr('data-name');
    	$('#search-field').val(itemChosenName);
    	feedProfessionalsOnMap();
    });

	function feedProfessionalsOnMap(latitude = currentLocation.latitude, longitude = currentLocation.longitude){
		openLoader();
		var filterBy      = $('.filter-map-by').val();
		var valueOfFilter = $('#search-field').val();
 		var onlyThisRoles = [];
		var elementsOfCheckbox = $('.roleOption:checkbox:checked');
		if(elementsOfCheckbox.length > 0){
			elementsOfCheckbox.each(function(){
				onlyThisRoles.push($(this).attr('data-id'));
			});
		}
		$.ajax({
		  	url: 'person/fetchprofessionalsformap',
		  	type: 'POST',
		  	dataType: 'JSON',
		  	data: {onlyThisRoles: onlyThisRoles, filterBy: filterBy, value: valueOfFilter},
		  	success: function(result){
		  		if(result.success == true){
			  		var content = result.content;
		  			if(content == false && result.followLocation == true){
		  				openToast("<?php echo ucfirst(translate('no professionals found')); ?>");
		  				var locationToGo = result.location
		  				latitudeCookie  = locationToGo[0];
		  				longitudeCookie = locationToGo[1];
		  			}else{
			  			// In case all roles are selected
				  		if(rolesToNotDisplay == null){
				  			<?php foreach($roles as $role){ ?>
					  			for(i = 0; i < content[<?= $role['id'] ?>].length; i++){
					  				var professionalLatitude = content[<?= $role['id'] ?>][i]['latitude'];
					  				var professionalLongitude = content[<?= $role['id'] ?>][i]['longitude'];
					  				var distanceInKM = getDistanceFromLatLonInKm(latitude, longitude, professionalLatitude, professionalLongitude);
					  				if(distanceInKM > parseToKM(radiusInMeters))
					  					continue;
					  				professionalsMarker.push(content[<?= $role['id'] ?>][i]);
					  			}
				  			<?php } ?>
				  		}
				  		if(result.followLocation == true){
			  				var locationToGo = result.location
			  				latitudeCookie  = locationToGo[0];
			  				longitudeCookie = locationToGo[1];
				  		}
			  		}
		  		}
		    },
		    complete: function(){
		    	currentLocation = {
		    		latitude: latitude,
		    		longitude: longitude
		    	}
		    	initMap(true);
		    	openLoader(false);
		    }
		});
	}

	/*
      Generates an array containing <string> html content to be used on the infoView attached to a marker on this position.
      Obs: must be used on a loop, google maps event listener must receive data this way
    */
    var htmlInfoViewGenerated = [];
    function generateContentForInfoView(title = '', description = '', url = '', logo = '', score = '', role = ''){
        var htmlInfo =
            '<div id="content">' +
            '<div id="siteNotice">' +
            "</div>" +
            '<img class="user-photo-map" width=150px src="'+logo+'">' +
            '<div class="user-info">' +
            '<img width=50px src="'+rolesIcon[role - 1]+'">' +
            '<span style="float: right; margin-top:10%; margin-right:10%;">'+score+'</span>' + 
            '</div>' +
            '<h1 id="firstHeading" class="firstHeading">'+title+'</h1>' +
            '<div id="bodyContent">' +
            "<p><b>"+title+"</b>, is a "+description+"</p>" +
            '<p>See my profile <a href="'+url+'" style="text-decoration:none;color:##000">' +
            "Link: "+url+"</a></p>" +
            "</div>" +
            "</div>";
             htmlInfoViewGenerated.push(htmlInfo);
        return htmlInfo;
    }

    // Changing the radius distance
	$("#myRange").change(function(){
		var newDistance = $(this).val();
	    $('#distance-meter-start-mark').val(newDistance);
	    radiusInMeters = parseInt(newDistance);
		radiusInMeters = radiusInMeters * 1000;
		// sets it to empty to force reload of map
		professionalsMarker = [];
		feedProfessionalsOnMap(currentLocation.latitude, currentLocation.longitude, true);
	});

    var rolesIcon = [];
    function feedRoleIconArray(){
    	<?php foreach($roles as $role){ ?>
    		rolesIcon.push("<?php echo URL['iconsPath'].$role['iconUrl'] ?>");
    	<?php } ?>
    }

	function parseToKM(meters){
		var km = meters / 1000;
		return km.toFixed(1);
	}

	function updateLocationCokies(latitude, longitude){
		$.ajax({
		  	url: 'updatecookies/usergeolocation',
		  	type: 'POST',
		  	data: {latitude: latitude, longitude: longitude},
		  	success: function(result){
		    	//console.log(result);
		    }
		});
	}

	// Calculates the distance between two locations(coordinates)
	// the results tends to be not very accurate since it does not takes the streets into account, it
	// just calculates a flying straight line to the location.
	function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
		lat1 = parseFloat(lat1);
		lon1 = parseFloat(lon1);
		lat2 = parseFloat(lat2);
		lon2 = parseFloat(lon2);
	    var R = 6371; // Radius of the earth in km
	    var dLat = deg2rad(lat2-lat1);  // deg2rad below
	    var dLon = deg2rad(lon2-lon1); 
	    var a = 
	        Math.sin(dLat/2) * Math.sin(dLat/2) +
	        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
	        Math.sin(dLon/2) * Math.sin(dLon/2); 
	    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
	    var d = R * c; // Distance in km
	    return d;
	}
	function deg2rad(deg) {
	  return deg * (Math.PI/180)
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
	}
	.top-user-card{
		padding: 2%;
		background: #ffffff;
		border-radius: 5px;
		text-align: center;
		min-width: 200px;
		margin-bottom: 5%;
	}
	.user-personal-page{
		display: flex;
		justify-content: space-between;
	}
	.user-personal-page > img{
		height: 35px;
		width: 35px;
	}
	.user-personal-page{
		text-decoration: none;
		color: gray;
	}
	.top-user-photo{
		height: 100px;
		width: 100px;
		border-radius: 5px;
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
		font-size: 30px;
	}
	.professionals-data{
		display: flex;
		flex-wrap: wrap;
		justify-content: space-around;
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

	/* google map */
	.map-wrapper{
	    height: 800px;
	}
	#google-map-element {
	    height: 100%;
	}

	.km-calculator{
		pointer-events: none;
		opacity: 0.8;
	}

	#content{
		width: 90%;
		text-align: center;
	}
	.user-photo-map{
		width: 100px;
		height: 100px;
		border-radius: 100px;
	}
	.user-info{
		width: 90%;
		justify-content: space-between;
		display: flex;
		margin: auto;
	}
	.user-info > img{
		width: 50px;
		height: 50px;
	}
	.user-info > span{
		float: unset;
		margin-top: unset;
		margin-right: unset;
	}
	.all-professionals-title > h5{
		font-size: 1.2em;
		width: 90%;
		margin: auto;
	}
	.roles-cards{
		flex-wrap: wrap;
	}
	.card-of-role{
		width: 50%;
		margin: 5%;
	}

	.options-of-items-select{
		border-radius: 10px;
		height: 30px;
		width: 300px;
		text-align: center;
		margin: 2%;
		background: #ececec;
		border: 1px solid #eee;
	}
	.confirm-the-selection{
		cursor: pointer;
	}
	.confirm-the-selection: hover{
		opacity: 0.8;
	}

	@media only screen and (max-width: 600px){
		.master-div{
			flex-direction: column;
		}
		.left-options{
			width: 90%;
			margin: auto;
		}
		.left-options > h4{
			margin-left: 5px;
		}
		.right-options{
			width: 90%;
			margin: auto;
		}
		.map-legend{
			height: 50px;
			padding: 2px;
		}
		.map-legend-item{
			font-size: 0.8em;
		}
		.reLocateMe > div{
			height: 25px;
			font-size: 0.8em;
			width: 50%;
			margin-top: 5%;
		}
	}

/*
	input[type="range"]::-moz-range-track {
	    padding: 0 10px;
	    background: repeating-linear-gradient(to right, 
	    #ccc, 
	    #ccc 10%, 
	    #000 10%, 
	    #000 11%, 
	    #ccc 11%, 
	    #ccc 20%);
	}
*/
</style>