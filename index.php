<?php

require __DIR__ ."/vendor/autoload.php";

$app = new \Slim\Slim();
use Source\Controllers\CountryController;
use Source\Controllers\LanguageController;
use Source\Controllers\PersonController;
use Source\Controllers\PersonRoleController;
use Source\Controllers\APIController;
use Source\Helpers\FunctionsClass;

// starts the session
FunctionsClass::startSession();
// verifies the existence of authentificationToken cookies
FunctionsClass::verifyCookies();
// to set the default time zone
FunctionsClass::setTimeZone();
// sets language of user by the ip address location
FunctionsClass::getCountryAndCityByIp();
// sets language of user by the ip address location
FunctionsClass::setCityByCookies();
// sets roles to session
FunctionsClass::getRolesToSession();

// importing routes
require __DIR__ ."/Routes/Midleware.php";
require __DIR__ ."/Routes/PersonRoutes.php";
require __DIR__ ."/Routes/PostFeedRoutes.php";
require __DIR__ ."/Routes/MapAndSearchRoutes.php";
require __DIR__ ."/Routes/ViewsRoutes.php";
require __DIR__ ."/Routes/MessageRoutes.php";
require __DIR__ ."/Routes/PersonActivityRoutes.php";

$app->get('/', function(){
	$userTemplate = new League\Plates\Engine('Source/Resourses/UserViews');
	echo $userTemplate->render('home-page', ['title' => ucfirst(translate('homepage'))]);
});
// View with login form
$app->get('/login', function(){
	$userTemplate = new League\Plates\Engine('Source/Resourses');
	echo $userTemplate->render('login-view', [
		'title' => ucfirst(translate('login')),
	]);
});
// View which send new Code to email
$app->get('login/retrievepassword', function(){
	$userTemplate = new League\Plates\Engine('Source/Resourses');
	echo $userTemplate->render('login-view', [
		'title' => ucfirst(translate('login')),
	]);
});
// View which gather new account Data in order to create a new person 
$app->get('/login/newaccount', function(){
	$userTemplate = new League\Plates\Engine('Source/Resourses');
	echo $userTemplate->render('login-view', [
		'title' => ucfirst(translate('login')),
	]);
});

$app->get('/logout', function(){
	session_destroy();
	session_start();
	$_SESSION['viewMessage'] = ucfirst(translate('until next time'));
	header('Location: /'.URL['urlDomain']);
	exit;
});

// Put a middleware for this methods
$app->get('/admin/procedure/cleancodescache', function(){
	$result = FunctionsClass::cleanCodesCache();
	exit($result);
});


// Sync countries from XML
$app->get('/country/synccountriesfromxml', function(){
	$countryCk = new CountryController();
	exit($countryCt->syncCountriesFromXML());
});

// Sync countries from Unece website
$app->get('/country/syncwithunece', function(){
	$countryCt = new CountryController();
	exit($countryCt->syncWithUnece());
});

// Sync countries from Unece website
$app->get('/country/feedtranslations', function(){
	$countryCt = new CountryController();
	exit($countryCt->feedCountryTranslations());
});

// Sync langauges and relate them to countries
$app->get('/language/synccountrylanguages', function(){
	$languageCk = new LanguageController();
	exit($languageCt->syncCountryLanguages());
});

// sets new langauge of user for view translations
$app->post('/language/changeuserlanguage', function(){
	$languageISO = isset($_POST['languageISO']) ? $_POST['languageISO'] : 'pt';
	$_SESSION['userLanguage'] = $languageISO;
	$_SESSION['choosenUserLanguage'] = true;
	return true;
});

// set cookies to user current location
$app->post('/updatecookies/usergeolocation', function(){
	$latitude  = isset($_POST['latitude'])  ? $_POST['latitude'] : null;
	$longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;
	$removeCookies = isset($_POST['removeCookies']) ? $_POST['removeCookies'] : null;
	if(!is_null($removeCookies)){
		unset($_COOKIE['latitude']);
		unset($_COOKIE['longitude']);
		return true;
	}
	setcookie('latitude', $latitude, time() + (86400 * 30), '/');
	setcookie('longitude', $longitude, time() + (86400 * 30), '/');
	// fetch on API results from this latitude and longitude to gather data
	FunctionsClass::updateSessionUserLocation($latitude, $longitude);
	if(FunctionsClass::isPersonLoggedIn()){
		$personCt = new PersonController;
		$dataToUpdate = [
			'id'		=> $_SESSION['personId'],
			'latitude'  => $latitude,
			'longitude' => $longitude
		];
		$_POST['accountData'] = $dataToUpdate;
		$personCt->save();
	}
	return true;
});

// set cookies to user current location
$app->post('/systemmessages/clean', function(){
	unset($_SESSION['messages']);
	return true;
});

// search at API
$app->post('/apicontroller/getdatafromlocationiq', function(){
	$apiControllerCt = new APIController();
	$result = $apiControllerCt->getDataFromLocationIQ();
	echo $result;
	return;
});

// get data related to number of professionals for each roles 
$app->post('/personrole/getrolesdata', function(){
	$personRoleCt = new PersonRoleController();
	$result = $personRoleCt->getRolesData();
	echo $result;
	return;
});

$app->post('/save/map', function(){
	$image = isset($_FILES['img']) ? $_FILES['img'] : null;
	if(is_null($image)){
		$response = json_encode([
			'success' => false,
			'message' => 'no file sent'
		]);
		echo $response;
		return;
	}
	$pathToStorage = TMPPATH['images'] . 'professionals-map';

	if(!is_dir($pathToStorage))
		mkdir($pathToStorage);
	$fileToSave = $image['tmp_name'];
	$source = file_get_contents($fileToSave);

	$imageWk = new ImageResize($fileToSave);
	$image->quality_jpg = 100;
	$imageWk->crop(200, 200);
	$result = $imageWk->save('image2.jpg', IMAGETYPE_PNG);
	dd($result);
	$fileName = $pathToStorage . '/' . 'professional-map-1848998198.jpeg';
	file_put_contents($fileName, $source);

	return true;
});

$app->get('/refreshMessages', function(){
	if(isset($_SESSION['messageToDisplay'])){
		$_SESSION['messageToDisplay'] = null;
	}
	return;
});

$app->post('/me/test', function(){


	
	$url = "https://maps.googleapis.com/maps/api/staticmap?center=archelau+de+almeida+torres+595&zoom=18&size=600x300&maptype=roadmap&markers=color:red|label:H|-25.5916169,-49.3966099&key=AIzaSyChHsH5OFnAtmXBqldHQDqQAKLYUX-hmhw";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	$answer = curl_exec($ch);

	dd(urldecode($answer));

	//file_put_contents('t.png', $answer);
	exit($answer);


});

if(!Midleware::checkLogin()){
	header('Location: '.URL['urlDomain']);
}
// saves a history containing the last accessed route and the current one
Midleware::saveLastRoute();
// check what to show accordingly to ALL_FEATURES attribute
Midleware::checkAvaliableFeatures();
$app->run();