<?php

require __DIR__ ."/vendor/autoload.php";

$app = new \Slim\Slim();
use Source\Controllers\CountryController;
use Source\Controllers\LanguageController;
use Source\Controllers\PersonController;
use Source\Controllers\PersonRoleController;
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
	$latitude  = $_POST['latitude'];
	$longitude = $_POST['longitude'];
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
	return true;
});

// set cookies to user current location
$app->post('/systemmessages/clean', function(){
	unset($_SESSION['messages']);
	return true;
});

if(!Midleware::checkLogin()){
	header('Location: '.URL['urlDomain']);
}
// saves a history containing the last accessed route and the current one
Midleware::saveLastRoute();
$app->run();