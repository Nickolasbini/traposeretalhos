<?php

use Source\Models\Person;
use Source\Models\PersonActivity;
use Source\Helpers\FunctionsClass;

/**
 * 
 */
class Midleware
{
	public static function checkLogin()
	{
		$request = $_SERVER['REQUEST_URI'];
		$request = str_replace('/'.URL['urlDomain'], '', $request);
		$_SESSION['currentRoute'] = $request;
		$routesToIgnore = ['/personactivity/updateandcheckpersonactivities'];
		if(in_array($request, $routesToIgnore)){
			return true;
		}
		$routesToVerify = ['/post/save', '/message/listmessages', '/personalpage/addwork', '/person/updatesomedata', '/messages', '/favorites', '/person/editbyfield', '/person/updatesomedata'];
		foreach($routesToVerify as $routeName){
			if($request == $routeName){
				$result = Midleware::tryToLogin();
				if(!$result){
					$_SESSION['errorMessage'] = ucfirst(translate('please log in first'));
		            $_SESSION['messages'] = ucfirst(translate('please, log in first'));
					header('Location: /'.URL['urlDomain'].'/login');
					exit;
		      	}
		      	break;
			}
		}
		if(FunctionsClass::isPersonLoggedIn()){
			$requestParameters = [
				'url' 	        => URL['realPath'] . '/personactivity/updateandcheckpersonactivities',
				'requestValues' => [
					'personId'     => $_SESSION['personId'],
					'currentRoute' => $request 
				], 
				'asynRequest' => true
			];
			FunctionsClass::sendRequest($requestParameters);
      	}
      	return true;
	}

	/*
		tries to login by session\cookie\email and password parameters
		obs: if manages to find updates cookie and session
		@return true on successs and false on failure
	*/
	public static function tryToLogin()
	{
		$personObj = new Person();
		// check by session
		if(isset($_SESSION['personId']) && !is_null($_SESSION['personId'])){
			$person = $personObj->findById($_SESSION['personId']);
			if($person && $person->getStatus() != PERSON::NOT_VERIFIED_ACCOUNT){
				return true;
			}
		}
		// try to find by cookie
		if(isset($_COOKIE['authenticationToken']) && !is_null($_COOKIE['authenticationToken'])){
			$person = $personObj->getByAuthenticationToken($_COOKIE['authenticationToken']);
			$person = $person ? $person[0] : null;
			if($person && $person->getStatus() != PERSON::NOT_VERIFIED_ACCOUNT){
				// set session variables
				FunctionsClass::setPersonSession($person);
				// set cookie, check how to use this data
				$authenticationToken = FunctionsClass::setPersonCookie();
				$person->setAuthenticationToken($authenticationToken);
				$person->save();
				return true;
			}
		}
		// try to find by email
		$email    = isset($_POST['email']) ? $_POST['email'] : null;
		$password = isset($_POST['password']) ? $_POST['password'] : null;
		if(!$email && !$password){
			$email    = isset($_GET['email']) ? $_GET['email'] : null;
			$password = isset($_GET['email']) ? $_GET['email'] : null;
		}
		if(!is_null($email) && !is_null($password)){
			$person = $personObj->getByEmail($email);
			if($person && $person->getStatus() != PERSON::NOT_VERIFIED_ACCOUNT){
				$personPassword = $person->getPassword();
				$password = FunctionsClass::generateHashValue($password);
				if($personPassword == $password){
					// set session variables
					FunctionsClass::setPersonSession($person);
					// set cookie, check how to use this data
					$authenticationToken = FunctionsClass::setPersonCookie();
					$person->setAuthenticationToken($authenticationToken);
					$person->save();
					return true;
				}
			}
		}
		return false;
	}

	// saves the routes accessed by user
	public static function saveLastRoute()
	{
		// verify here what to use, maybe put just the main routes to be allowed to be saved on the history
		$recordOnlyThesesRoutes = ['/traposeretalhos/', '/traposeretalhos/news', '/traposeretalhos/search', '/traposeretalhos/map', '/traposeretalhos/tips', '/traposeretalhos/courses', '/traposeretalhos/posts', '/message/listmessages'];
		$routes = isset($_SESSION['history']) ? json_decode($_SESSION['history'], true) : [];
		$currentRoute = $_SERVER['REQUEST_URI'];
		if(!in_array($currentRoute, $recordOnlyThesesRoutes))
			return;
		$routes[] = $currentRoute;
		if(count($routes) == 3){
			unset($routes[0]);
			$routes = array_values($routes);
		}
		$_SESSION['history'] = json_encode($routes);
	}

	// check if cookie of login exists, else tries to login
	public static function checkAvaliableFeatures()
	{
		$currentRoute = $_SERVER['REQUEST_URI'];
		$restrictedRoutes = ['/'.URL['urlDomain'].'/courses'];
		if(!ALL_FEATURES && in_array($currentRoute, $restrictedRoutes)){
			header('Location: /'.URL['urlDomain']);
			exit();
		}
	}
}
