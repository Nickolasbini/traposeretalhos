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
					<input id="distance-meter-start-mark" class="km-calculator" type="number" value="0" placeholder="0">
					<h6 class="km-simbol">Km</h6>
				</div>
				<div class="km-wrapper">
					<input id="distance-meter-end-mark" class="km-calculator" type="number" value="100"placeholder="100">
					<h6 class="km-simbol">Km</h6>
				</div>				
			</div>
			<div class="slidecontainer">
			    <input type="range" min="1" max="100" value="50" class="slider" id="myRange">
			</div>
			
			<div class="divider"></div>

			<h4><?php echo ucfirst(translate('What do you seek')); ?>?</h4>
			<div class="roles-options">
			<?php if(isset($_SESSION['roles'])){
				$roles = json_decode($_SESSION['roles'], true);
				foreach($roles as $data){?>
					<div class="option-of-role">
					<input class="roleOption" data-id="<?= $data['id'] ?>" data-creation-date="<?= $data['dateOfCreation'] ?>" type="checkbox">
						<label class="name"><?php echo ucfirst(translate($data['roleName'])); ?></label>
					</div>
			<?php }} ?>
			</div>

			<div class="best-professional-group">
				<div class="left-filter-top" style="margin-top: 25px;">
					<h5 style="margin: 10px 0 0 10px;"><?php echo ucfirst(translate('best professionals')); ?></h5>
					<div class="professional-card">
						<img src="">
						<div class="professional-data">
							<h5>Victoria</h5>
							<span><img><img></span>
							<a href="#">my page</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="right-options">
			<div class="upper-filter">
				<h5><?php echo ucfirst(translate('filter by')); ?>:</h5>
				<select class="filter-map-by">
					<option>city</option>
					<option>state</option>
					<option>country</option>
				</select>
				<input id="search-field" type="text" placeholder="<?php echo ucfirst(translate('search')); ?>">
				<img class="search-icon" src="Source/Resourses/External/icons/search.svg">
			</div>

			<div class="map-wrapper">
		 		<div id="google-map-element"></div>
			</div>
		</div>
	</section>

	<!-- The place to set the error -->
	<p id="demo"></p>

	
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBU-TEmfQj4HU2Janr2QIECDF2ciY1HvRY&callback=initMap"></script>
	<div class="reLocateMe">
		<a>Find me</a>
	</div>


	<?php $this->insert('user-footer') ?>
</body>
<link href="Source/Resourses/CSS/maps-view-css.css" rel="stylesheet"></link>

<script src="Source/Resourses/JS-functions/google-map.js"></script>
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
			})
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
			    icon: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
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
	}

	var mapObject = null;
	var userLocationMarkerObject = null;
	var radiusInMeters = 3000;
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

	$('#distance-meter-start-mark').on('input', function(){
		radiusInMeters = parseInt($(this).val());
		radiusInMeters = radiusInMeters * 1000;
		// sets it to empty to force reload of map
		professionalsMarker = [];
		feedProfessionalsOnMap(currentLocation.latitude, currentLocation.longitude, true);
	});

	// try here to clean map markers
	function clearMarkerOnMap() {
	    professionalsMarker = [];
	}

	// think here how to call again just to try to gather the new position
	$('.reLocateMe').on('click', function(){
		$.ajax({
		  	url: 'updatecookies/usergeolocation',
		  	type: 'POST',
		  	data: {removeCookies: removeCookies},
		  	success: function(result){
		    	//console.log(result);
		    },
		    complete: function(){
		    	askForLocationAndFeedMap();
		    }
		});
	});

	function feedProfessionalsOnMap(latitude, longitude){
		$.ajax({
		  	url: 'person/fetchprofessionalsformap',
		  	type: 'POST',
		  	dataType: 'JSON',
		  	success: function(result){
		  		if(result.success == true){
			  		content = result.content;
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
		  		}
		    },
		    complete: function(){
		    	currentLocation = {
		    		latitude: latitude,
		    		longitude: longitude
		    	}
		    	initMap(true);
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
            '<img width=150px src="'+logo+'">' +
            '<img width=150px src="'+rolesIcon[role - 1]+'">' +
            '<span style="float: right; margin-top:10%; margin-right:10%;">'+score+'</span>' + 
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
	#google-map-element {
	    height: 500px;
	}
</style>